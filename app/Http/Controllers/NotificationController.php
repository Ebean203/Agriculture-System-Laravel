<?php

namespace App\Http\Controllers;

use App\Models\MaoDistributionLog;
use App\Models\InputCategory;
use App\Models\MaoInventory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get notifications for the authenticated user
     */
    public function getNotifications(Request $request)
    {
        $countOnly = $request->query('count_only') === 'true';
        
        if ($countOnly) {
            // Return only notification count
            $notifications = $this->getAllNotifications();
            $unreadCount = count($notifications);
            $criticalCount = $this->getCriticalCount($notifications);
            
            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount,
                'total_count' => count($notifications),
                'critical_count' => $criticalCount
            ]);
        } else {
            // Return full notifications
            $notifications = $this->getAllNotifications();
            $unreadCount = count($notifications);
            
            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
                'total_count' => count($notifications)
            ]);
        }
    }

    /**
     * Get all notifications (visitation + inventory)
     */
    private function getAllNotifications()
    {
        $notifications = [];
        $today = Carbon::today();
        
        // Get visitation notifications
        $visitationNotifications = $this->getVisitationNotifications($today);
        $notifications = array_merge($notifications, $visitationNotifications);
        
        // Get inventory notifications
        $inventoryNotifications = $this->getInventoryNotifications();
        $notifications = array_merge($notifications, $inventoryNotifications);
        
        // Sort by priority
        usort($notifications, function($a, $b) {
            if ($a['priority'] == $b['priority']) {
                $dateA = $a['date'] ?? '';
                $dateB = $b['date'] ?? '';
                return strcmp($dateA, $dateB);
            }
            return $a['priority'] - $b['priority'];
        });
        
        return $notifications;
    }

    /**
     * Get visitation reminder notifications
     */
    private function getVisitationNotifications($today)
    {
        $notifications = [];
        $reminderDate = Carbon::today()->addDays(5);
        
        // Get upcoming and overdue visitations
        $visitations = MaoDistributionLog::with(['farmer.barangay', 'input'])
            ->whereNotNull('visitation_date')
            ->where('visitation_date', '<=', $reminderDate)
            ->orderBy('visitation_date', 'asc')
            ->get();
        
        foreach ($visitations as $visitation) {
            $daysUntil = Carbon::parse($visitation->visitation_date)->diffInDays($today, false);
            $formattedDate = Carbon::parse($visitation->visitation_date)->format('M j, Y');
            
            // Build farmer name
            $farmerName = $visitation->farmer->first_name;
            if (!empty($visitation->farmer->middle_name) && 
                !in_array(strtolower($visitation->farmer->middle_name), ['n/a', 'na', ''])) {
                $farmerName .= ' ' . $visitation->farmer->middle_name;
            }
            $farmerName .= ' ' . $visitation->farmer->last_name;
            if (!empty($visitation->farmer->suffix) && 
                !in_array(strtolower($visitation->farmer->suffix), ['n/a', 'na', ''])) {
                $farmerName .= ' ' . $visitation->farmer->suffix;
            }
            
            $barangayName = $visitation->farmer->barangay->barangay_name ?? 'Unknown';
            $purpose = $visitation->input->input_name ?? 'Unknown';
            
            if ($daysUntil > 0) {
                // Overdue
                $message = "OVERDUE Visitation (was {$formattedDate}) for {$farmerName} in {$barangayName} - {$purpose} follow-up";
                $priority = 0;
                $type = 'urgent';
                $title = 'Overdue Visitation';
            } elseif ($daysUntil == 0) {
                // Today
                $message = "Visitation TODAY ({$formattedDate}) for {$farmerName} in {$barangayName} - {$purpose} follow-up";
                $priority = 1;
                $type = 'urgent';
                $title = 'Visitation Today';
            } elseif ($daysUntil == -1) {
                // Tomorrow
                $message = "Visitation TOMORROW ({$formattedDate}) for {$farmerName} in {$barangayName} - {$purpose} follow-up";
                $priority = 2;
                $type = 'warning';
                $title = 'Upcoming Visitation';
            } else {
                // Future
                $absDay = abs($daysUntil);
                $message = "Visitation scheduled for {$formattedDate} ({$absDay} days) - {$farmerName} in {$barangayName} - {$purpose} follow-up";
                $priority = 3;
                $type = 'info';
                $title = 'Upcoming Visitation';
            }
            
            $notifications[] = [
                'id' => 'visit_' . $visitation->log_id,
                'type' => $type,
                'category' => 'visitation',
                'title' => $title,
                'message' => $message,
                'date' => $formattedDate,
                'priority' => $priority,
                'icon' => 'fas fa-calendar-check',
                'data' => [
                    'log_id' => $visitation->log_id,
                    'farmer_id' => $visitation->farmer_id,
                    'farmer_name' => $farmerName,
                    'barangay_name' => $barangayName,
                    'input_name' => $purpose,
                    'input_id' => $visitation->input_id,
                    'visitation_date' => $visitation->visitation_date,
                    'days_until_visit' => $daysUntil
                ]
            ];
        }
        
        return $notifications;
    }

    /**
     * Get inventory low stock notifications
     */
    private function getInventoryNotifications()
    {
        $notifications = [];
        
        // Get all input categories with their inventory
        $inputs = InputCategory::with('inventory')->get();
        
        foreach ($inputs as $input) {
            $currentStock = $input->inventory->quantity_on_hand ?? 0;
            
            // Determine minimum level based on input name
            $inputNameLower = strtolower($input->input_name);
            if (str_contains($inputNameLower, 'seed')) {
                $minimumLevel = 20;
            } elseif (str_contains($inputNameLower, 'fertilizer')) {
                $minimumLevel = 30;
            } elseif (str_contains($inputNameLower, 'pesticide')) {
                $minimumLevel = 10;
            } elseif (str_contains($inputNameLower, 'tool')) {
                $minimumLevel = 3;
            } elseif (str_contains($inputNameLower, 'equipment')) {
                $minimumLevel = 1;
            } else {
                $minimumLevel = 5;
            }
            
            // Only notify if stock is at or below minimum
            if ($currentStock <= $minimumLevel) {
                $stockPercentage = $minimumLevel > 0 ? ($currentStock / $minimumLevel) * 100 : 0;
                
                if ($currentStock == 0) {
                    $message = "OUT OF STOCK: {$input->input_name}";
                    $priority = 1;
                    $type = 'urgent';
                } elseif ($stockPercentage <= 25) {
                    $message = "CRITICAL LOW: {$input->input_name} ({$currentStock} {$input->unit} remaining)";
                    $priority = 2;
                    $type = 'urgent';
                } elseif ($stockPercentage <= 50) {
                    $message = "LOW STOCK: {$input->input_name} ({$currentStock} {$input->unit} remaining)";
                    $priority = 3;
                    $type = 'warning';
                } else {
                    // Skip info level notifications
                    continue;
                }
                
                // Only add warning and urgent notifications
                if (in_array($type, ['warning', 'urgent'])) {
                    $notifications[] = [
                        'id' => 'stock_' . str_replace(' ', '_', $input->input_name),
                        'type' => $type,
                        'category' => 'inventory',
                        'title' => 'Inventory Alert',
                        'message' => $message,
                        'date' => $input->inventory->last_updated ?? '',
                        'priority' => $priority,
                        'icon' => 'fas fa-exclamation-triangle',
                        'data' => [
                            'input_id' => $input->input_id,
                            'item_name' => $input->input_name,
                            'current_stock' => $currentStock,
                            'unit' => $input->unit,
                            'minimum_level' => $minimumLevel
                        ]
                    ];
                }
            }
        }
        
        return $notifications;
    }

    /**
     * Get count of critical notifications (urgent and high priority)
     */
    private function getCriticalCount($notifications)
    {
        $count = 0;
        foreach ($notifications as $notification) {
            if ($notification['type'] === 'urgent' || $notification['priority'] <= 2) {
                $count++;
            }
        }
        return $count;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InputCategory;
use App\Models\MaoInventory;
use App\Models\MaoDistributionLog;
use App\Models\Farmer;
use App\Models\Commodity;
use App\Models\CommodityCategory;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        // Get all input categories with their current inventory
        $query = InputCategory::with(['inventory'])
                    ->leftJoin('mao_inventory', 'input_categories.input_id', '=', 'mao_inventory.input_id')
                    ->select(
                        'input_categories.input_id',
                        'input_categories.input_name',
                        'input_categories.unit',
                        DB::raw('COALESCE(mao_inventory.quantity_on_hand, 0) as quantity_on_hand'),
                        'mao_inventory.last_updated'
                    )
                    ->orderBy('input_categories.input_name');

        $inputs = $query->get();

        // Get total distributed amounts for each input
        $distributions = MaoDistributionLog::select('input_id', DB::raw('SUM(quantity_distributed) as total_distributed'))
                            ->groupBy('input_id')
                            ->pluck('total_distributed', 'input_id')
                            ->toArray();

        // Get notifications for inventory categorization
        $notifications = $this->getInventoryNotifications();

        // Categorize items based on notification status
        $urgent_items = [];
        $warning_items = [];
        $normal_items = [];

        // Create lookup arrays for notification statuses
        $notification_lookup = [];
        foreach ($notifications as $notification) {
            if ($notification['category'] == 'inventory') {
                $input_id = $notification['data']['input_id'];
                $notification_lookup[$input_id] = [
                    'type' => $notification['type'],
                    'message' => $notification['message'],
                    'item_name' => $notification['data']['item_name']
                ];
            }
        }

        // Categorize all inventory items
        foreach ($inputs as $row) {
            $input_id = $row->input_id;
            if (isset($notification_lookup[$input_id])) {
                $notif_type = $notification_lookup[$input_id]['type'];
                if ($notif_type == 'urgent') {
                    $urgent_items[] = $row;
                } else if ($notif_type == 'warning') {
                    $warning_items[] = $row;
                } else {
                    $normal_items[] = $row;
                }
            } else {
                $normal_items[] = $row;
            }
        }

        // Calculate summary statistics
        $total_items = $inputs->count();
        $out_of_stock = $inputs->where('quantity_on_hand', 0)->count();
        $low_stock = $inputs->where('quantity_on_hand', '>', 0)->where('quantity_on_hand', '<=', 10)->count();

        // Get last updated date
        $last_updated = $inputs->whereNotNull('last_updated')->max('last_updated');

        // Load commodity categories for modal dropdown
        $categories = \App\Models\CommodityCategory::orderBy('category_name')->get();

        return view('inventory.index', compact(
            'inputs', 'distributions', 'urgent_items', 'warning_items', 'normal_items',
            'total_items', 'out_of_stock', 'low_stock', 'last_updated', 'notification_lookup', 'categories'
        ));
    }

    public function addStock(Request $request)
    {
        $request->validate([
            'input_id' => 'required|exists:input_categories,input_id',
            'add_quantity' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            $inventory = MaoInventory::where('input_id', $request->input_id)->first();
            
            if ($inventory) {
                $inventory->quantity_on_hand += $request->add_quantity;
                $inventory->last_updated = now();
                $inventory->save();
            } else {
                MaoInventory::create([
                    'input_id' => $request->input_id,
                    'quantity_on_hand' => $request->add_quantity,
                    'last_updated' => now()
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Stock added successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Error adding stock: ' . $e->getMessage()]);
        }
    }

    public function updateStock(Request $request)
    {
        $request->validate([
            'input_id' => 'required|exists:input_categories,input_id',
            'new_quantity' => 'required|integer|min:0'
        ]);

        DB::beginTransaction();
        try {
            $inventory = MaoInventory::where('input_id', $request->input_id)->first();
            
            if ($inventory) {
                $inventory->quantity_on_hand = $request->new_quantity;
                $inventory->last_updated = now();
                $inventory->save();
            } else {
                MaoInventory::create([
                    'input_id' => $request->input_id,
                    'quantity_on_hand' => $request->new_quantity,
                    'last_updated' => now()
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Stock updated successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Error updating stock: ' . $e->getMessage()]);
        }
    }

    public function distribute(Request $request)
    {
        $request->validate([
            'input_id' => 'required|exists:input_categories,input_id',
            'farmer_id' => 'required|exists:farmers,farmer_id',
            'quantity_distributed' => 'required|integer|min:1',
            'date_given' => 'required|date',
            'visitation_date' => 'required|date|after_or_equal:date_given'
        ]);

        DB::beginTransaction();
        try {
            // Check if enough stock is available
            $inventory = MaoInventory::where('input_id', $request->input_id)->first();
            if (!$inventory || $inventory->quantity_on_hand < $request->quantity_distributed) {
                return response()->json(['success' => false, 'message' => 'Insufficient stock available']);
            }

            // Create distribution record (DB columns: farmer_id, input_id, quantity_distributed, date_given, visitation_date)
            MaoDistributionLog::create([
                'input_id' => $request->input_id,
                'farmer_id' => $request->farmer_id,
                'quantity_distributed' => $request->quantity_distributed,
                'date_given' => $request->date_given,
                'visitation_date' => $request->visitation_date,
            ]);

            // Update inventory
            $inventory->quantity_on_hand -= $request->quantity_distributed;
            $inventory->last_updated = now();
            $inventory->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Input distributed successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Error distributing input: ' . $e->getMessage()]);
        }
    }

    public function getFarmers()
    {
        $farmers = Farmer::select('farmer_id', 'first_name', 'last_name')
                        ->where('archived', false)
                        ->orderBy('first_name')
                        ->get();
        
        return response()->json($farmers);
    }

    public function addNewInput(Request $request)
    {
        $request->validate([
            'input_name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'quantity_on_hand' => 'required|integer|min:0'
        ]);

        DB::beginTransaction();
        try {
            // Create new input category
            $inputCategory = InputCategory::create([
                'input_name' => $request->input_name,
                'unit' => $request->unit
            ]);

            // Create initial inventory record if quantity > 0
            if ($request->quantity_on_hand > 0) {
                MaoInventory::create([
                    'input_id' => $inputCategory->input_id,
                    'quantity_on_hand' => $request->quantity_on_hand,
                    'last_updated' => now()
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'New input type added successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Error adding new input type: ' . $e->getMessage()]);
        }
    }

    public function addNewCommodity(Request $request)
    {
        $request->validate([
            'commodity_name' => 'required|string|max:100',
            'category_id' => 'required|exists:commodity_categories,category_id',
        ]);

        try {
            $commodity = Commodity::create([
                'commodity_name' => $request->commodity_name,
                'category_id' => $request->category_id,
            ]);

            return response()->json(['success' => true, 'message' => 'New commodity added successfully', 'commodity' => $commodity]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error adding commodity: ' . $e->getMessage()], 500);
        }
    }

    private function getInventoryNotifications()
    {
        $notifications = [];
        
        // Get all input categories with their inventory
        $inputs = InputCategory::with('inventory')->get();
        
        foreach ($inputs as $input) {
            $currentStock = $input->inventory->quantity_on_hand ?? 0;
            
            if ($currentStock == 0) {
                $notifications[] = [
                    'category' => 'inventory',
                    'type' => 'urgent',
                    'message' => "Out of stock - Immediate restocking required",
                    'data' => [
                        'input_id' => $input->input_id,
                        'item_name' => $input->input_name,
                        'current_stock' => $currentStock
                    ]
                ];
            } elseif ($currentStock <= 5) {
                $notifications[] = [
                    'category' => 'inventory',
                    'type' => 'urgent',
                    'message' => "Critical low stock - Only {$currentStock} {$input->unit} remaining",
                    'data' => [
                        'input_id' => $input->input_id,
                        'item_name' => $input->input_name,
                        'current_stock' => $currentStock
                    ]
                ];
            } elseif ($currentStock <= 20) {
                $notifications[] = [
                    'category' => 'inventory',
                    'type' => 'warning',
                    'message' => "Low stock warning - {$currentStock} {$input->unit} remaining",
                    'data' => [
                        'input_id' => $input->input_id,
                        'item_name' => $input->input_name,
                        'current_stock' => $currentStock
                    ]
                ];
            }
        }
        
        return $notifications;
    }
}
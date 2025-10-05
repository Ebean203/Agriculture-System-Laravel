@php
    $quantity = intval($item->quantity_on_hand);
    $distributed = isset($distributions[$item->input_id]) ? intval($distributions[$item->input_id]) : 0;
    
    // Ensure valid values
    if ($quantity < 0) $quantity = 0;
    if ($distributed < 0) $distributed = 0;
    
    // Determine styling based on status
    $status_classes = [
        'urgent' => 'border-red-500 bg-red-50 shadow-red-200',
        'warning' => 'border-yellow-500 bg-yellow-50 shadow-yellow-200',
        'normal' => 'border-green-500 bg-white shadow-gray-200'
    ];
    
    $badge_classes = [
        'urgent' => 'bg-red-600 text-white',
        'warning' => 'bg-yellow-600 text-white',
        'normal' => 'bg-green-600 text-white'
    ];
    
    $status_text = [
        'urgent' => 'CRITICAL',
        'warning' => 'LOW STOCK',
        'normal' => 'NORMAL'
    ];
    
    $card_class = $status_classes[$status];
    $badge_class = $badge_classes[$status];
    $status_display = $status_text[$status];
    
    // Determine category icon
    $input_name_lower = strtolower($item->input_name);
    $icon_bg = 'bg-gray-500';
    $icon = 'fas fa-box';
    
    if (strpos($input_name_lower, 'seed') !== false) {
        $icon_bg = 'bg-green-500';
        $icon = 'fas fa-seedling';
    } elseif (strpos($input_name_lower, 'fertilizer') !== false) {
        $icon_bg = 'bg-blue-500';
        $icon = 'fas fa-leaf';
    } elseif (strpos($input_name_lower, 'pesticide') !== false || strpos($input_name_lower, 'herbicide') !== false) {
        $icon_bg = 'bg-yellow-500';
        $icon = 'fas fa-flask';
    } elseif (strpos($input_name_lower, 'goat') !== false || strpos($input_name_lower, 'chicken') !== false) {
        $icon_bg = 'bg-orange-500';
        $icon = 'fas fa-paw';
    } elseif (strpos($input_name_lower, 'tractor') !== false || strpos($input_name_lower, 'shovel') !== false || strpos($input_name_lower, 'sprayer') !== false || strpos($input_name_lower, 'pump') !== false) {
        $icon_bg = 'bg-purple-500';
        $icon = 'fas fa-tools';
    }
    
    // Get notification message if exists
    $notification_message = '';
    if (isset($notification_lookup[$item->input_id])) {
        $notification_message = $notification_lookup[$item->input_id]['message'];
    }
@endphp

<div class="inventory-card rounded-lg shadow-lg border-2 {{ $card_class }} h-full relative overflow-hidden">
    @if ($status == 'urgent')
        <div class="absolute top-0 right-0 bg-red-600 text-white px-3 py-1 text-xs font-bold transform rotate-12 translate-x-3 -translate-y-2">
            URGENT
        </div>
    @elseif ($status == 'warning')
        <div class="absolute top-0 right-0 bg-yellow-600 text-white px-3 py-1 text-xs font-bold transform rotate-12 translate-x-3 -translate-y-2">
            WARNING
        </div>
    @endif
    
    <div class="p-6">
        <!-- Header -->
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center">
                <div class="w-10 h-10 {{ $icon_bg }} rounded-lg flex items-center justify-center mr-3">
                    <i class="{{ $icon }} text-white"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">{{ $item->input_name }}</h3>
                    <p class="text-sm text-gray-600">{{ $item->unit }}</p>
                </div>
            </div>
            <span class="px-2 py-1 {{ $badge_class }} text-xs font-bold rounded-full">{{ $status_display }}</span>
        </div>
        
        <!-- Notification Alert -->
        @if ($notification_message)
        <div class="mb-4 p-3 bg-red-100 border border-red-300 rounded-lg">
            <div class="flex items-center text-red-700">
                <i class="fas fa-bell mr-2"></i>
                <span class="text-sm font-medium">{{ $notification_message }}</span>
            </div>
        </div>
        @endif
        
        <!-- Statistics -->
        <div class="grid grid-cols-3 gap-4 mb-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-900">{{ $quantity }}</div>
                <div class="text-xs text-gray-500">On Hand</div>
            </div>
            <div class="text-center border-l border-r border-gray-200">
                <div class="text-2xl font-bold text-gray-900">{{ $distributed }}</div>
                <div class="text-xs text-gray-500">Distributed</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-900">{{ $quantity + $distributed }}</div>
                <div class="text-xs text-gray-500">Total</div>
            </div>
        </div>
        
        <!-- Last Updated -->
        @if ($item->last_updated)
        <div class="mb-4 pt-4 border-t border-gray-200">
            <div class="text-xs text-gray-500">
                <i class="fas fa-clock mr-1"></i>
                Last updated: {{ \Carbon\Carbon::parse($item->last_updated)->format('M j, Y g:i A') }}
            </div>
        </div>
        @endif
        
        <!-- Action Buttons -->
        <div class="flex space-x-2">
            <button class="flex-1 bg-blue-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors update-btn"
                    data-input-id="{{ $item->input_id }}"
                    data-input-name="{{ $item->input_name }}"
                    data-quantity="{{ $quantity }}">
                <i class="fas fa-edit mr-1"></i>Update
            </button>
            <button class="flex-1 bg-green-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-green-700 transition-colors distribute-btn"
                    data-input-id="{{ $item->input_id }}"
                    data-input-name="{{ $item->input_name }}"
                    data-quantity="{{ $quantity }}"
                    {{ $quantity == 0 ? 'disabled' : '' }}>
                <i class="fas fa-share-square mr-1"></i>Distribute
            </button>
        </div>
    </div>
</div>
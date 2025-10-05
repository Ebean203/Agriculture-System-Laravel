@extends('layouts.agriculture')

@section('title', 'Manage Inventory')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-warehouse text-agri-green mr-3"></i>
                    Manage Inventory
                </h1>
                <p class="text-gray-600 mt-2">Monitor and manage agricultural inputs available for distribution</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Add Input Dropdown -->
                <div class="relative">
                    <button class="bg-agri-green text-white px-4 py-2 rounded-lg hover:bg-agri-dark transition-colors flex items-center" onclick="toggleAddInputDropdown()">
                        <i class="fas fa-plus mr-2"></i>Add Input
                        <i class="fas fa-chevron-down ml-2 transition-transform duration-200" id="addInputArrow"></i>
                    </button>
                    <div id="addInputDropdown" class="absolute right-0 top-full mt-2 w-52 bg-white rounded-lg shadow-lg border border-gray-200 z-50 hidden">
                        <a href="#" onclick="openAddNewInputTypeModal(); return false;" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-box mr-2 text-green-600"></i>New Input Type
                        </a>
                        <a href="#" onclick="openAddToExistingModal(); return false;" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors border-t border-gray-100">
                            <i class="fas fa-plus-circle mr-2 text-blue-600"></i>Add to Existing
                        </a>
                        <a href="#" onclick="openAddNewCommodityModal(); return false;" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors border-t border-gray-100">
                            <i class="fas fa-seedling mr-2 text-orange-600"></i>New Commodity
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success and Error Messages -->
    <div id="messageContainer" class="mb-6" style="display: none;">
        <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center" style="display: none;">
            <i class="fas fa-check-circle mr-3 text-green-600"></i>
            <div>
                <strong>Success!</strong>
                <span id="successText"></span>
            </div>
            <button onclick="closeMessage('successMessage')" class="ml-auto text-green-700 hover:text-green-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center" style="display: none;">
            <i class="fas fa-exclamation-circle mr-3 text-red-600"></i>
            <div>
                <strong>Error!</strong>
                <span id="errorText"></span>
            </div>
            <button onclick="closeMessage('errorMessage')" class="ml-auto text-red-700 hover:text-red-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Inventory Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Items -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-boxes text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $total_items }}</h3>
                    <p class="text-gray-600">Total Input Types</p>
                </div>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $out_of_stock }}</h3>
                    <p class="text-gray-600">Out of Stock</p>
                </div>
            </div>
        </div>

        <!-- Low Stock -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-chart-line text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $low_stock }}</h3>
                    <p class="text-gray-600">Low Stock Items</p>
                </div>
            </div>
        </div>

        <!-- Last Updated -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-agri-green">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-calendar-day text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">
                        @if($last_updated)
                            {{ \Carbon\Carbon::parse($last_updated)->format('M j, Y') }}
                        @else
                            Never
                        @endif
                    </h3>
                    <p class="text-gray-600">Last Updated</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Segregated Inventory Sections -->
    @if (count($urgent_items) > 0)
    <div class="mb-8">
        <div class="bg-red-50 rounded-xl p-6 border-2 border-red-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-red-800 flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>Critical Items - Immediate Action Required
                </h3>
                <span class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold">
                    {{ count($urgent_items) }} Items
                </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($urgent_items as $item)
                    @include('inventory.partials.inventory-card', ['item' => $item, 'status' => 'urgent'])
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if (count($warning_items) > 0)
    <div class="mb-8">
        <div class="bg-yellow-50 rounded-xl p-6 border-2 border-yellow-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-yellow-800 flex items-center">
                    <i class="fas fa-exclamation-circle text-yellow-600 mr-2"></i>Low Stock Items - Monitor Closely
                </h3>
                <span class="bg-yellow-600 text-white px-4 py-2 rounded-lg font-bold">
                    {{ count($warning_items) }} Items
                </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($warning_items as $item)
                    @include('inventory.partials.inventory-card', ['item' => $item, 'status' => 'warning'])
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="mb-8">
        <div class="bg-green-50 rounded-xl p-6 border-2 border-green-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-green-800 flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-2"></i>Normal Stock Items
                </h3>
                <span class="bg-green-600 text-white px-4 py-2 rounded-lg font-bold">
                    {{ count($normal_items) }} Items
                </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($normal_items as $item)
                    @include('inventory.partials.inventory-card', ['item' => $item, 'status' => 'normal'])
                @endforeach
            </div>
        </div>
    </div>
</div>

@include('inventory.partials.modals')
@endsection

@push('additional-js')
@include('inventory.partials.javascript')
@endpush
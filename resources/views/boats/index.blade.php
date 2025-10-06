@extends('layouts.agriculture')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-ship text-agri-green mr-3"></i>
                    Boat Records
                </h1>
                <p class="text-gray-600 mt-2">Fishing Boat Registration and Management System</p>
                <div class="mt-2 text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-2"></i>
                    Total Boat Registered Farmers: <span class="font-bold text-agri-green">{{ number_format($totalRegistered ?? 0) }}</span>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('reports.index', ['prefill' => 'boats']) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>Export to PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="flex flex-col gap-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-1"></i>Search Boat Records
                    </label>
                    <div class="relative">
                        <input type="text" name="search" id="boat_search" value="{{ $search }}"
                               placeholder="Search by owner name, contact, or registration number..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-agri-green focus:border-agri-green">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-1"></i>Filter by Barangay
                    </label>
                    <select name="barangay" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-agri-green focus:border-agri-green">
                        <option value="">All Barangays</option>
                        @foreach($barangays as $b)
                            <option value="{{ $b->barangay_id }}" @selected($barangay==$b->barangay_id)>{{ $b->barangay_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-agri-green text-white px-6 py-2 rounded-lg hover:bg-agri-dark transition-colors">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                <a href="{{ route('boats') }}" class="px-6 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Clear</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-list-alt mr-2 text-agri-green"></i>
                Registered Fishing Boats
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-agri-green text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            <i class="fas fa-id-card mr-1"></i>Farmer ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            <i class="fas fa-user mr-1"></i>Farmer Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            <i class="fas fa-phone mr-1"></i>Contact
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            <i class="fas fa-map-marker-alt mr-1"></i>Barangay
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            <i class="fas fa-seedling mr-1"></i>Commodities
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            <i class="fas fa-calendar mr-1"></i>Registration Date
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($boats as $boat)
                        <tr>
                            <td class="px-6 py-4 text-sm">{{ $boat->farmer_id }}</td>
                            <td class="px-6 py-4 text-sm">{{ $boat->farmer_name }}</td>
                            <td class="px-6 py-4 text-sm">{{ $boat->contact_number ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $boat->barangay_name }}</td>
                            <td class="px-6 py-4 text-sm">{{ $boat->commodity_name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm">{{ isset($boat->registration_date) ? \Carbon\Carbon::parse($boat->registration_date)->format('M d, Y') : '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-ship text-6xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Boat Records Found</h3>
                                    <p class="text-sm text-gray-600 mb-2">No boats are currently registered in the system</p>
                                    <p class="text-xs text-gray-500">The system is ready to display boat records when boats are registered</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">{{ $boats->withQueryString()->links() }}</div>
    </div>
</div>
@endsection

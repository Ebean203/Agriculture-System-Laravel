@extends('layouts.agriculture')

@section('title', 'All Reports - Lagonglong FARMS')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">All Reports</h1>
                <p class="text-gray-600">Browse all reports you've generated</p>
            </div>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <select name="type" class="border rounded-lg px-3 py-2">
                    <option value="">All Types</option>
                    <option value="farmers_summary" @selected(($filter['type'] ?? '')==='farmers_summary')>Farmers Summary</option>
                    <option value="input_distribution" @selected(($filter['type'] ?? '')==='input_distribution')>Input Distribution</option>
                    <option value="yield_monitoring" @selected(($filter['type'] ?? '')==='yield_monitoring')>Yield Monitoring</option>
                    <option value="inventory_status" @selected(($filter['type'] ?? '')==='inventory_status')>Inventory Status</option>
                    <option value="barangay_analytics" @selected(($filter['type'] ?? '')==='barangay_analytics')>Barangay Analytics</option>
                    <option value="commodity_production" @selected(($filter['type'] ?? '')==='commodity_production')>Commodity Production</option>
                    <option value="registration_analytics" @selected(($filter['type'] ?? '')==='registration_analytics')>Registration Analytics</option>
                    <option value="comprehensive_overview" @selected(($filter['type'] ?? '')==='comprehensive_overview')>Comprehensive Overview</option>
                </select>
                <input type="date" name="from" value="{{ $filter['from'] ?? '' }}" class="border rounded-lg px-3 py-2" />
                <input type="date" name="to" value="{{ $filter['to'] ?? '' }}" class="border rounded-lg px-3 py-2" />
                <button type="submit" class="bg-agri-green text-white rounded-lg px-4 py-2 font-medium">Filter</button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Generated</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reports as $report)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst(str_replace('_',' ',$report->report_type)) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($report->start_date)->format('M d, Y') }} - 
                            {{ \Carbon\Carbon::parse($report->end_date)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($report->timestamp)->format('M d, Y g:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ asset($report->file_path) }}" target="_blank" class="text-agri-green hover:text-agri-dark font-medium">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">No reports found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection
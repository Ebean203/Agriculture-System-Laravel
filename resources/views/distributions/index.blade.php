@extends('layouts.agriculture')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-share-square text-agri-green mr-3"></i>
                    Input Distribution Records
                </h1>
                <p class="text-gray-600 mt-2">Lagonglong FARMS - Input Distribution Management</p>
                <div class="mt-2 text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-2"></i>
                    Total Distribution Records: <span class="font-bold text-agri-green">{{ number_format($totalCount) }}</span>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('reports.index', ['prefill' => 'input_distribution']) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>Export to PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="flex flex-col gap-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-1"></i>Search Distribution Records
                    </label>
                    <div class="relative">
                        <input type="text" name="search" id="distribution_search" value="{{ $search }}"
                               placeholder="Search by farmer name or contact..."
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
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-seedling mr-1"></i>Filter by Input Type
                    </label>
                    <select name="input_id" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-agri-green focus:border-agri-green">
                        <option value="">All Input Types</option>
                        @foreach($inputs as $i)
                            <option value="{{ $i->input_id }}" @selected($inputId==$i->input_id)>{{ $i->input_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-agri-green text-white px-6 py-2 rounded-lg hover:bg-agri-dark transition-colors">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                <a href="{{ route('distributions') }}" class="px-6 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Clear</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-list-alt mr-2 text-agri-green"></i>
                Distribution Records
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-agri-green text-white">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-1"></i>ID
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            <i class="fas fa-user mr-1"></i>Farmer Details
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            <i class="fas fa-seedling mr-1"></i>Input & Quantity
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            <i class="fas fa-calendar mr-1"></i>Dates & Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($records as $r)
                        <tr class="hover:bg-agri-light transition-colors">
                            <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <span class="bg-agri-green text-white px-2 py-1 rounded text-xs">#{{ $r->log_id }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm font-medium text-gray-900 truncate max-w-48">{{ $r->farmer_name }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-phone mr-1"></i>
                                    {{ $r->contact_number ?? '—' }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-map-pin mr-1 text-red-600"></i>
                                    {{ $r->barangay_name }}
                                </div>
                            </td>
                            <td class="px-3 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    <div class="flex items-center mb-2">
                                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-2">
                                            <i class="fas fa-seedling text-green-600 text-xs"></i>
                                        </div>
                                        <span class="text-xs truncate max-w-24">{{ $r->input_name }}</span>
                                    </div>
                                    <div class="text-sm">
                                        <span class="font-bold text-lg text-agri-green">{{ rtrim(rtrim(number_format($r->quantity_distributed, 2), '0'), '.') }}</span>
                                        <span class="text-gray-500 ml-1 text-xs">{{ $r->unit }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-4">
                                <div class="space-y-2">
                                    <div class="text-xs text-gray-900">
                                        <div class="flex items-center mb-1">
                                            <i class="fas fa-calendar mr-1 text-blue-600"></i>
                                            <span class="font-medium">Given:</span>
                                        </div>
                                        <span class="text-xs">{{ \Carbon\Carbon::parse($r->date_given)->format('M d, Y') }}</span>
                                    </div>
                                    @if(!empty($r->visitation_date))
                                    <div class="text-xs text-gray-900">
                                        <div class="flex items-center mb-1">
                                            <i class="fas fa-calendar-check mr-1 text-green-600"></i>
                                            <span class="font-medium">Visit:</span>
                                        </div>
                                        <span class="text-xs">{{ \Carbon\Carbon::parse($r->visitation_date)->format('M d, Y') }}</span>
                                    </div>
                                    @endif
                                    <div class="mt-2">
                                        @php
                                            // No status column in mao_distribution_log; default to Scheduled for legacy parity
                                            $status = 'Scheduled';
                                            $badge = [
                                                'Scheduled' => 'bg-blue-100 text-blue-800',
                                            ][$status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs font-medium flex items-center w-fit {{ $badge }}">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ $status }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-gray-500">
                                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-2"></i>
                                <div>No Distribution Records Found</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">{{ $records->withQueryString()->links() }}</div>
    </div>
</div>
@endsection

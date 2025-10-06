@extends('layouts.agriculture')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-fish text-blue-600 mr-3"></i>
                    FishR Records Management
                </h1>
                <p class="text-gray-600 mt-1">Fisheries Registry System - Manage fisher registrations</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('reports.index', ['prefill' => 'fishr']) }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-64">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Fishers</label>
                <div class="relative">
                        <input type="text" name="search" id="fisher_search" value="{{ $search }}"
                               placeholder="Search by name or contact number..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-agri-green focus:border-agri-green"
                               autocomplete="off"
                               onkeyup="searchFisherAutoSuggest(this.value)"
                               onfocus="if(this.value) searchFisherAutoSuggest(this.value)">
                        <!-- Auto-suggest dropdown -->
                        <div id="fisher_suggestions" class="absolute z-50 w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto hidden"></div>
                </div>
            </div>
            <div class="min-w-48">
                <label for="barangay" class="block text-sm font-medium text-gray-700 mb-2">Filter by Barangay</label>
                <select name="barangay" id="barangay" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-agri-green focus:border-agri-green">
                    <option value="">All Barangays</option>
                    @foreach($barangays as $b)
                        <option value="{{ $b->barangay_id }}" @selected($barangay==$b->barangay_id)>{{ $b->barangay_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-agri-green hover:bg-agri-dark text-white px-6 py-2 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-search mr-2"></i>
                    Search
                </button>
                @if($search || $barangay)
                <a href="{{ route('fishr') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-refresh mr-2"></i>
                    Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- FishR Records Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if(($totalRecords ?? 0) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-agri-green text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Fisher Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Contact & Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Registration Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Commodity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($fishers as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-fish text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ trim(($row->first_name ?? '').' '.($row->middle_name ?? '').' '.($row->last_name ?? '').' '.(in_array(strtolower($row->suffix ?? ''), ['n/a','na']) ? '' : ($row->suffix ?? ''))) }}
                                        </div>
                                        <div class="text-sm text-gray-500">ID: {{ $row->farmer_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $row->contact_number ?? '—' }}</div>
                                <div class="text-sm text-gray-500">{{ $row->barangay_name ?? '—' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $row->registration_date ? \Carbon\Carbon::parse($row->registration_date)->format('M d, Y') : '—' }}
                                </div>
                                <div class="text-sm text-gray-500">Fisherfolk Registration</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if(!empty($row->commodities_info))
                                    @foreach(explode(', ', $row->commodities_info) as $commodity)
                                        <div class="bg-green-100 text-green-800 rounded px-2 py-1 text-xs inline-flex items-center mr-1 mb-1"><i class="fas fa-leaf mr-1"></i>{{ $commodity }}</div>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-fish mr-1"></i>Fisherfolk Registered
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div>{{ $fishers->withQueryString()->links() }}</div>
                <div class="text-sm text-gray-700">Showing <span class="font-medium">{{ ($fishers->currentPage()-1)*$fishers->perPage()+1 }}</span> to <span class="font-medium">{{ min($fishers->currentPage()*$fishers->perPage(), $fishers->total()) }}</span> of <span class="font-medium">{{ $fishers->total() }}</span> results</div>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-fish text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No FishR Records Found</h3>
                <p class="text-gray-500 mb-6">
                    @if($search || $barangay)
                        No fishers match your search criteria. Try adjusting your filters.
                    @else
                        No fishers are currently registered in the FishR system.
                    @endif
                </p>
                @if($search || $barangay)
                <a href="{{ route('fishr') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-agri-green hover:bg-agri-dark">
                    <i class="fas fa-refresh mr-2"></i>
                    Clear Filters
                </a>
                @endif
            </div>
        @endif
    </div>
    @push('additional-js')
    <script>
    // Auto-suggest functionality for fisher search (reuses farmers API with fisherfolk filter)
    function searchFisherAutoSuggest(query) {
        const suggestions = document.getElementById('fisher_suggestions');
        if (!suggestions) return;

        if (!query || query.length < 1) {
            suggestions.innerHTML = '';
            suggestions.classList.add('hidden');
            return;
        }

        suggestions.innerHTML = '<div class="px-3 py-2 text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Searching...</div>';
        suggestions.classList.remove('hidden');

        fetch(`/api/farmers/search?query=${encodeURIComponent(query)}&filter_type=fisherfolk`)
            .then(r => r.json())
            .then(data => {
                if (data.success && data.farmers && data.farmers.length) {
                    let html = '';
                    data.farmers.forEach(farmer => {
                        const safeName = (farmer.full_name || '').replace(/'/g, "\\'");
                        html += `
                            <div class="px-3 py-2 hover:bg-gray-100 cursor-pointer border-b last:border-b-0"
                                 onclick="selectFisherSuggestion('${farmer.farmer_id}', '${safeName}', '${farmer.contact_number || ''}')">
                                <div class="font-medium text-gray-900">${farmer.full_name}</div>
                                <div class="text-sm text-gray-600">ID: ${farmer.farmer_id} | Contact: ${farmer.contact_number || ''}</div>
                                <div class="text-xs text-gray-500">${farmer.barangay_name || ''}</div>
                            </div>
                        `;
                    });
                    suggestions.innerHTML = html;
                } else {
                    suggestions.innerHTML = '<div class="px-3 py-2 text-gray-500">No fishers found matching your search</div>';
                }
            })
            .catch(err => {
                console.error('Suggest error', err);
                suggestions.innerHTML = '<div class="px-3 py-2 text-red-500">Error loading suggestions</div>';
            });
    }

    function selectFisherSuggestion(farmerId, farmerName, contactNumber) {
        const input = document.getElementById('fisher_search');
        input.value = farmerName;
        hideFisherSuggestions();
        const form = input.closest('form');
        if (form) form.submit();
    }

    function hideFisherSuggestions() {
        const suggestions = document.getElementById('fisher_suggestions');
        if (suggestions) {
            setTimeout(() => suggestions.classList.add('hidden'), 200);
        }
    }

    document.addEventListener('click', function(event) {
        const input = document.getElementById('fisher_search');
        const suggestions = document.getElementById('fisher_suggestions');
        if (input && suggestions && !input.contains(event.target) && !suggestions.contains(event.target)) {
            suggestions.classList.add('hidden');
        }
    });

    function exportFishrToPDF() {
        const params = new URLSearchParams(window.location.search);
        let url = '{{ route('reports.index') }}?prefill=fishr';
        const s = params.get('search');
        const b = params.get('barangay');
        if (s) url += '&search=' + encodeURIComponent(s);
        if (b) url += '&barangay=' + encodeURIComponent(b);
        window.open(url, '_blank');
    }
    </script>
    @endpush

</div>
@endsection

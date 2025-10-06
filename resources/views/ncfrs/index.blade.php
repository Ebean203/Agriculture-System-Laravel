@extends('layouts.agriculture')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-id-card text-agri-green mr-3"></i>
                    NCFRS Records
                </h1>
                <p class="text-gray-600 mt-2">National Concessionaire and Financing Registration System</p>
                <div class="mt-2 text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-2"></i>
                    Total NCFRS Registered Farmers: <span class="font-bold text-agri-green">{{ number_format($totalRecords ?? 0) }}</span>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('reports.index', ['prefill' => 'ncfrs']) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center"
                   onclick="event.preventDefault(); exportNcfrsToPDF();">
                    <i class="fas fa-file-pdf mr-2"></i>Export to PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="flex flex-col gap-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-1"></i>Search NCFRS Farmers
                    </label>
                    <div class="relative">
                        <input type="text" name="search" id="ncfrs_search" value="{{ $search }}" 
                               placeholder="Search by name or contact number..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-agri-green focus:border-agri-green"
                               autocomplete="off"
                               onkeyup="searchNCFRSAutoSuggest(this.value)"
                               onfocus="if(this.value) searchNCFRSAutoSuggest(this.value)">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        <!-- Auto-suggest dropdown -->
                        <div id="ncfrs_suggestions" class="absolute z-50 w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto hidden"></div>
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
                @if($search || $barangay)
                <a href="{{ route('ncfrs') }}" class="px-6 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Clear</a>
                @endif
            </div>
        </form>
    </div>

    <!-- NCFRS Records Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-list-alt mr-2 text-agri-green"></i>
                NCFRS Registered Farmers
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
                            <i class="fas fa-user mr-1"></i>Full Name
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
                            <i class="fas fa-calendar mr-1"></i>REGDATE
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            <i class="fas fa-certificate mr-1"></i>Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($farmers as $row)
                    <tr class="hover:bg-agri-light transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <span class="bg-agri-green text-white px-2 py-1 rounded text-xs">#{{ $row->farmer_id }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ trim(($row->first_name ?? '').' '.($row->middle_name ?? '').' '.($row->last_name ?? '').' '.(in_array(strtolower($row->suffix ?? ''), ['n/a','na']) ? '' : ($row->suffix ?? ''))) }}
                            </div>
                            @if(!empty($row->birth_date))
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-birthday-cake mr-1"></i>
                                {{ \Carbon\Carbon::parse($row->birth_date)->format('M d, Y') }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <i class="fas fa-phone mr-1 text-green-600"></i>
                            {{ $row->contact_number ?? '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <i class="fas fa-map-pin mr-1 text-red-600"></i>
                            {{ $row->barangay_name ?? '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if(!empty($row->commodities_info))
                                <div class="flex flex-col gap-1">
                                @foreach(explode(', ', $row->commodities_info) as $commodity)
                                    <div class="bg-green-100 text-green-800 rounded px-2 py-1 text-xs flex items-center"><i class="fas fa-leaf mr-1"></i>{{ $commodity }}</div>
                                @endforeach
                                </div>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $row->registration_date ? \Carbon\Carbon::parse($row->registration_date)->format('M d, Y') : '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">
                                <i class="fas fa-certificate mr-1"></i>NCFRS Registered
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-id-card text-6xl text-gray-300 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No NCFRS Records Found</h3>
                                <p class="text-sm text-gray-600 mb-2">No NCFRS-registered farmers found for your filters</p>
                                <p class="text-xs text-gray-500">Adjust your search or clear filters to see more results</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">{{ $farmers->withQueryString()->links() }}</div>
    </div>
</div>

@push('additional-js')
<script>
function searchNCFRSAutoSuggest(query) {
    const suggestions = document.getElementById('ncfrs_suggestions');
    if (!suggestions) return;
    if (!query || query.length < 1) {
        suggestions.innerHTML = '';
        suggestions.classList.add('hidden');
        return;
    }
    suggestions.innerHTML = '<div class="px-3 py-2 text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Searching...</div>';
    suggestions.classList.remove('hidden');
    fetch(`/api/farmers/search?query=${encodeURIComponent(query)}&filter_type=ncfrs`)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.farmers && data.farmers.length) {
                let html = '';
                data.farmers.forEach(farmer => {
                    const safeName = (farmer.full_name || '').replace(/'/g, "\\'");
                    html += `
                        <div class="px-3 py-2 hover:bg-gray-100 cursor-pointer border-b last:border-b-0"
                             onclick="selectNCFRSSuggestion('${farmer.farmer_id}', '${safeName}', '${farmer.contact_number || ''}')">
                            <div class="font-medium text-gray-900">${farmer.full_name}</div>
                            <div class="text-sm text-gray-600">ID: ${farmer.farmer_id} | Contact: ${farmer.contact_number || ''}</div>
                            <div class="text-xs text-gray-500">${farmer.barangay_name || ''}</div>
                        </div>
                    `;
                });
                suggestions.innerHTML = html;
            } else {
                suggestions.innerHTML = '<div class="px-3 py-2 text-gray-500">No farmers found matching your search</div>';
            }
        })
        .catch(err => {
            console.error('Suggest error', err);
            suggestions.innerHTML = '<div class="px-3 py-2 text-red-500">Error loading suggestions</div>';
        });
}

function selectNCFRSSuggestion(farmerId, farmerName, contactNumber) {
    const input = document.getElementById('ncfrs_search');
    input.value = farmerName;
    setTimeout(() => document.getElementById('ncfrs_suggestions').classList.add('hidden'), 150);
    const form = input.closest('form');
    if (form) form.submit();
}

function exportNcfrsToPDF() {
    const params = new URLSearchParams(window.location.search);
    let url = '{{ route('reports.index') }}?prefill=ncfrs';
    const s = params.get('search');
    const b = params.get('barangay');
    if (s) url += '&search=' + encodeURIComponent(s);
    if (b) url += '&barangay=' + encodeURIComponent(b);
    window.open(url, '_blank');
}
</script>
@endpush

@endsection

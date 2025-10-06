@extends('layouts.agriculture')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-chart-line text-agri-green mr-3"></i>
                Yield Monitoring
            </h1>
            <p class="text-gray-600 mt-2">Track and monitor agricultural yield from distributed inputs</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <button class="bg-agri-green text-white px-4 py-2 rounded-lg hover:bg-agri-dark transition-colors flex items-center" onclick="openModal('addVisitModal')">
                <i class="fas fa-plus mr-2"></i>Record Visit
            </button>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4" id="yieldSuccess">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">{{ $errors->first() }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-eye text-blue-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($summary['total_visits'] ?? 0) }}</h3>
                <p class="text-gray-600">Total Visits</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-wheat-awn text-green-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($summary['agronomic'] ?? 0) }}</h3>
                <p class="text-gray-600">Agronomic Crops</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-apple-alt text-purple-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($summary['high_value'] ?? 0) }}</h3>
                <p class="text-gray-600">High Value Crops</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-paw text-orange-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($summary['livestock_poultry'] ?? 0) }}</h3>
                <p class="text-gray-600">Livestock & Poultry</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-agri-green">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-agri-light rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-seedling text-agri-green text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($summary['average_yield'] ?? 0, 2) }} sacks</h3>
                <p class="text-gray-600">Average Yield</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex flex-wrap gap-3 mb-6">
        <button class="filter-tab bg-white text-gray-700 px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors flex items-center" data-filter="agronomic">
            <i class="fas fa-wheat-awn mr-2"></i>Agronomic Crops
        </button>
        <button class="filter-tab bg-white text-gray-700 px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors flex items-center" data-filter="high-value">
            <i class="fas fa-apple-alt mr-2"></i>High Value Crops
        </button>
        <button class="filter-tab bg-white text-gray-700 px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors flex items-center" data-filter="livestock">
            <i class="fas fa-horse mr-2"></i>Livestock
        </button>
        <button class="filter-tab bg-white text-gray-700 px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors flex items-center" data-filter="poultry">
            <i class="fas fa-egg mr-2"></i>Poultry
        </button>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form id="yieldFilterForm" method="GET" action="{{ route('yield-monitoring') }}" class="flex flex-col gap-4">
        <input type="hidden" name="category_filter" id="hidden_category_filter" value="{{ $categoryId }}">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Commodity</label>
                <select name="commodity_filter" id="commodity_filter" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-agri-green focus:border-transparent">
                    <option value="">All Commodities</option>
                    @foreach($commodities as $c)
                        <option value="{{ $c->commodity_id }}" data-category="{{ $c->category_id }}" {{ (string)$commodityId === (string)$c->commodity_id ? 'selected' : '' }}>{{ $c->commodity_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Search Farmer</label>
                <div class="relative">
                    <input type="text" id="filter_farmer_search" name="farmer_search" placeholder="Search by farmer name..." value="{{ $farmerSearch }}" class="search-input w-full px-4 py-2 pl-10 bg-gray-100 border border-gray-200 rounded-lg focus:ring-2 focus:ring-agri-green focus:border-transparent" autocomplete="off">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    <input type="hidden" id="filter_farmer_id" name="farmer_id" value="{{ $farmerId }}">
                    <div id="filter_farmer_suggestions" class="absolute z-50 w-full bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto mt-1"></div>
                </div>
                <div class="mt-1 text-xs text-gray-500">Tip: Pick from suggestions to filter by exact farmer.</div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Date Range</label>
                <select name="date_filter" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-agri-green focus:border-transparent">
                    <option value="">All Dates</option>
                    <option value="7" {{ $dateFilter==='7'?'selected':'' }}>Last 7 days</option>
                    <option value="30" {{ $dateFilter==='30'?'selected':'' }}>Last 30 days</option>
                    <option value="90" {{ $dateFilter==='90'?'selected':'' }}>Last 3 months</option>
                </select>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="bg-agri-green text-white px-4 py-2 rounded-lg hover:bg-agri-dark transition-colors flex items-center">
                <i class="fas fa-filter mr-2"></i>Apply Filters
            </button>
        </div>
    </form>
    </div>

<div class="bg-white rounded-lg shadow-md p-6">
    @if($yieldRecords->count() === 0)
        <div class="text-center py-12">
            <i class="fas fa-chart-line text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No yield records found</h3>
            <p class="text-gray-500">Start by recording your first visit to track agricultural yield</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barangay</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commodity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Season</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yield Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Recorded</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($yieldRecords as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-agri-light flex items-center justify-center">
                                            <i class="fas fa-user text-agri-green"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ trim(($record->first_name ?? '').' '.($record->last_name ?? '')) }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $record->barangay_name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $record->barangay_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $record->commodity_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $record->season ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($record->yield_amount ?? 0, 2) }} {{ $record->unit ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($record->record_date)->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $yieldRecords->withQueryString()->links() }}</div>
    @endif
</div>

<!-- Yield Recording Modal -->
<div class="modal fade" id="addVisitModal" tabindex="-1" aria-labelledby="addVisitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addVisitModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Record Yield Visit
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="yieldVisitForm" method="POST" action="{{ route('yield.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-seedling me-2"></i>Yield Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="farmer_search" class="form-label">Select Farmer <span class="text-danger">*</span></label>
                                    <div class="relative">
                                        <input type="text" id="farmer_search" class="form-control" placeholder="Type farmer name..." autocomplete="off" required>
                                        <input type="hidden" id="farmer_id" name="farmer_id" required>
                                        <div id="farmer_suggestions" class="absolute z-50 w-full bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="commodity_id" class="form-label">Commodity <span class="text-danger">*</span></label>
                                    <select class="form-select" id="commodity_id" name="commodity_id" required>
                                        <option value="">Select Commodity</option>
                                        @foreach($commodities as $c)
                                            <option value="{{ $c->commodity_id }}" data-category="{{ $c->category_id }}">{{ $c->commodity_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="season" class="form-label">Season <span class="text-danger">*</span></label>
                                    <select class="form-select" id="season" name="season" required>
                                        <option value="">Select Season</option>
                                        <option value="Dry Season">Dry Season</option>
                                        <option value="Wet Season">Wet Season</option>
                                        <option value="First Cropping">First Cropping</option>
                                        <option value="Second Cropping">Second Cropping</option>
                                        <option value="Third Cropping">Third Cropping</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="yield_amount" class="form-label">Yield Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="yield_amount" name="yield_amount" placeholder="0.00" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="distributed_input" class="form-label">Distributed Input</label>
                                    <select class="form-select" id="distributed_input" name="distributed_input">
                                        <option value="">Select input type...</option>
                                        <option value="Urea">Urea</option>
                                        <option value="Complete">Complete</option>
                                        <option value="Ammonium Sulfate">Ammonium Sulfate</option>
                                        <option value="Organic Fertilizer">Organic Fertilizer</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="visit_date" class="form-label">Visit Date</label>
                                    <input type="date" class="form-control" id="visit_date" name="visit_date">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="unit" class="form-label">Unit</label>
                                    <select class="form-select" id="unit" name="unit">
                                        <option value="">Select unit...</option>
                                        <option value="kg">Kilograms</option>
                                        <option value="bags">Bags</option>
                                        <option value="sacks">Sacks</option>
                                        <option value="tons">Tons</option>
                                        <option value="pieces">Pieces</option>
                                        <option value="heads">Heads</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="quality_grade" class="form-label">Quality Grade</label>
                                    <select class="form-select" id="quality_grade" name="quality_grade">
                                        <option value="">Select grade...</option>
                                        <option value="Grade A">Grade A - Excellent</option>
                                        <option value="Grade B">Grade B - Good</option>
                                        <option value="Grade C">Grade C - Fair</option>
                                        <option value="Grade D">Grade D - Poor</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="growth_stage" class="form-label">Growth Stage</label>
                                    <select class="form-select" id="growth_stage" name="growth_stage">
                                        <option value="">Select stage...</option>
                                        <option value="Seedling">Seedling</option>
                                        <option value="Vegetative">Vegetative</option>
                                        <option value="Flowering">Flowering</option>
                                        <option value="Fruiting">Fruiting</option>
                                        <option value="Mature">Mature</option>
                                        <option value="Harvested">Harvested</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="field_conditions" class="form-label">Field Conditions</label>
                                    <select class="form-select" id="field_conditions" name="field_conditions">
                                        <option value="">Select condition...</option>
                                        <option value="Good Weather">Good Weather</option>
                                        <option value="Adequate Water">Adequate Water</option>
                                        <option value="Pest Issues">Pest Issues</option>
                                        <option value="Disease Present">Disease Present</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="visit_notes" class="form-label">Visit Notes</label>
                                    <textarea class="form-control" id="visit_notes" name="visit_notes" rows="2" placeholder="Additional notes..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <small><i class="fas fa-info-circle me-1"></i>Fields marked with <span class="text-danger">*</span> are required.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>Record Visit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('additional-js')
<script>
function openModal(id){ const modal=new bootstrap.Modal(document.getElementById(id)); modal.show(); }

// Farmer autocomplete using Laravel endpoint
function initializeFarmerAutocomplete() {
    const farmerSearch = document.getElementById('farmer_search');
    const farmerId = document.getElementById('farmer_id');
    const suggestions = document.getElementById('farmer_suggestions');
    let searchTimeout;
    if (!farmerSearch) return;

    farmerSearch.addEventListener('input', function() {
        const query = this.value.trim();
        clearTimeout(searchTimeout);
        if (query.length < 1) {
            suggestions.innerHTML = '';
            suggestions.classList.add('hidden');
            farmerId.value = '';
            return;
        }
        suggestions.innerHTML = '<div class="px-3 py-2 text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Searching...</div>';
        suggestions.classList.remove('hidden');
        searchTimeout = setTimeout(() => {
            fetch('{{ route('farmers.search') }}?query=' + encodeURIComponent(query))
                .then(r => r.json())
                .then(data => {
                    suggestions.innerHTML='';
                    if (data.success && data.farmers && data.farmers.length>0){
                        data.farmers.forEach(farmer => {
                            const item=document.createElement('div');
                            item.className='px-3 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-200 last:border-b-0';
                            item.innerHTML='<div class="font-medium text-gray-900">'+farmer.full_name+'</div>'+
                                '<div class="text-sm text-gray-600">ID: '+farmer.farmer_id+' | Contact: '+(farmer.contact_number||'N/A')+'</div>'+
                                '<div class="text-xs text-gray-500">'+(farmer.barangay_name||'N/A')+'</div>';
                            item.addEventListener('click', function(){
                                farmerSearch.value = farmer.full_name;
                                farmerId.value = farmer.farmer_id;
                                suggestions.classList.add('hidden');
                            });
                            suggestions.appendChild(item);
                        });
                    } else {
                        const no=document.createElement('div');
                        no.className='px-3 py-2 text-gray-500 text-center';
                        no.textContent='No farmers found matching your search';
                        suggestions.appendChild(no);
                    }
                })
                .catch(() => {
                    suggestions.innerHTML = '<div class="px-3 py-2 text-red-500">Error loading suggestions</div>';
                });
        }, 300);
    });
    document.addEventListener('click', function(e){
        if (!farmerSearch.contains(e.target) && !suggestions.contains(e.target)){
            setTimeout(()=>{ suggestions.classList.add('hidden'); },200);
        }
    });
}

function initializeFilterTabs(){
    const filterTabs=document.querySelectorAll('.filter-tab');
    filterTabs.forEach(tab=>{
        tab.addEventListener('click',function(){
            filterTabs.forEach(t=>{ t.classList.remove('active','bg-agri-green','text-white'); t.classList.add('bg-white','text-gray-700','border','border-gray-300'); });
            this.classList.add('active','bg-agri-green','text-white');
            this.classList.remove('bg-white','text-gray-700','border','border-gray-300');
            const map={ 'agronomic':'1','high-value':'2','livestock':'3','poultry':'4' };
            const val=map[this.getAttribute('data-filter')];
            document.getElementById('hidden_category_filter').value = val || '';
            filterCommodityDropdown(val);
            // Auto-submit filters to mirror legacy interaction
            if (!window.__suppressAutoSubmit) {
                document.getElementById('yieldFilterForm')?.submit();
            }
        });
    });
}

function filterCommodityDropdown(categoryId){
    const select=document.getElementById('commodity_filter');
    if (!select) return;
    const opts=select.querySelectorAll('option');
    // Reset only if current selection doesn't match selected category
    const selected = select.options[select.selectedIndex];
    if (selected && selected.value && selected.getAttribute('data-category') !== categoryId) {
        select.value='';
    }
    opts.forEach(opt=>{
        if (opt.value==='') {
            opt.hidden=false; opt.disabled=false;
        } else {
            const c=opt.getAttribute('data-category');
            const show = (!categoryId || c===categoryId);
            opt.hidden = !show;
            opt.disabled = !show;
        }
    });
}

document.addEventListener('DOMContentLoaded', function(){
    initializeFarmerAutocomplete();
    // Filter farmer autocomplete (header search)
    (function(){
        const input = document.getElementById('filter_farmer_search');
        const hiddenId = document.getElementById('filter_farmer_id');
        const box = document.getElementById('filter_farmer_suggestions');
        if (!input || !hiddenId || !box) return;
        let timer;
        function render(items){
            box.innerHTML='';
            if (!items || items.length===0){
                box.innerHTML='<div class="px-3 py-2 text-gray-500 text-center">No farmers found</div>';
                box.classList.remove('hidden');
                return;
            }
            items.forEach(f => {
                const el = document.createElement('div');
                el.className='px-3 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-200 last:border-b-0';
                const full = f.full_name || `${f.first_name||''} ${f.last_name||''}`.trim();
                el.innerHTML = `<div class="font-medium text-gray-900">${full}</div>
                                <div class=\"text-xs text-gray-600\">ID: ${f.farmer_id} | ${f.barangay_name||'N/A'}</div>`;
                el.addEventListener('click', () => {
                    input.value = full;
                    hiddenId.value = f.farmer_id;
                    box.classList.add('hidden');
                    // Submit the filter form immediately for fast filtering
                    document.getElementById('yieldFilterForm')?.submit();
                });
                box.appendChild(el);
            });
            box.classList.remove('hidden');
        }
        input.addEventListener('input', function(){
            const q = this.value.trim();
            clearTimeout(timer);
            hiddenId.value = '';
            if (q.length<1){ box.classList.add('hidden'); return; }
            box.innerHTML = '<div class="px-3 py-2 text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Searching...</div>';
            box.classList.remove('hidden');
            timer = setTimeout(() => {
                fetch(`{{ route('farmers.search') }}?query=${encodeURIComponent(q)}`)
                    .then(r => r.json())
                    .then(data => render((data && data.farmers) ? data.farmers : []))
                    .catch(() => { box.innerHTML='<div class="px-3 py-2 text-red-500">Search error</div>'; });
            }, 250);
        });
        document.addEventListener('click', function(e){
            if (!box.contains(e.target) && !input.contains(e.target)){
                setTimeout(()=> box.classList.add('hidden'), 150);
            }
        });
    })();
    initializeFilterTabs();
    const currentCategory='{{ $categoryId }}';
    if (currentCategory){
        filterCommodityDropdown(currentCategory);
        // Set active tab visually
        const map={ '1':'agronomic','2':'high-value','3':'livestock','4':'poultry' };
        const selector = `.filter-tab[data-filter="${map[currentCategory]}"]`;
        const tab = document.querySelector(selector);
        if (tab){
            // Prevent redundant submit on initial activation
            window.__suppressAutoSubmit = true;
            tab.click();
            setTimeout(()=>{ window.__suppressAutoSubmit = false; }, 0);
        }
    }
    else {
        // Default no active tab; all commodities visible
    }
    // Auto-hide success banner
    const success = document.getElementById('yieldSuccess');
    if (success){ setTimeout(()=>{ success.style.transition='opacity .5s'; success.style.opacity='0'; setTimeout(()=>success.remove(), 500); }, 1500); }
});
</script>
<style>
.filter-tab{ cursor:pointer; transition:all .3s ease; position:relative; }
.filter-tab:hover{ transform: translateY(-1px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
.filter-tab.active{ transform: translateY(-1px); box-shadow: 0 4px 12px rgba(22,163,74,0.3); }
.search-input{ background-color:#f3f4f6; border:1px solid #e5e7eb; color:#374151; }
.search-input:focus{ background-color:#fff; border-color:#16a34a; box-shadow:0 0 0 3px rgba(22,163,74,0.1); }
</style>
@endpush
@endsection

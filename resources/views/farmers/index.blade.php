@extends('layouts.agriculture')

@section('title', 'Farmers Management')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center gap-6">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-users text-green-600 mr-3"></i>
                    Farmers Management
                </h1>
                <p class="text-gray-600 mt-2">Manage and monitor all registered farmers</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 min-w-fit">
                <div class="flex gap-3 whitespace-nowrap">
                    <button onclick="exportToPDF()" 
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center">
                        <i class="fas fa-file-pdf mr-2"></i>Export to PDF
                    </button>
                    
                    <button onclick="openGeotaggingModal()" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>Geo-tag Farmer
                    </button>
                    
                    <button onclick="openFarmerModal()" 
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center">
                        <i class="fas fa-plus mr-2"></i>Add New Farmer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('farmers.index') }}" class="flex flex-col gap-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Farmers</label>
                    <div class="relative">
                        <input type="text" name="search" id="farmer_search" value="{{ request('search') }}" 
                               placeholder="Search by name, mobile, or Farmer ID..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                               autocomplete="off"
                               onkeyup="searchFarmersAutoSuggest(this.value)"
                               onfocus="if(this.value) searchFarmersAutoSuggest(this.value)">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        
                        <!-- Auto-suggest dropdown -->
                        <div id="farmer_suggestions" class="absolute z-50 w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto hidden">
                            <!-- Suggestions will be populated here -->
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Barangay</label>
                    <select name="barangay" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                        <option value="">All Barangays</option>
                        @foreach($barangays as $barangay)
                            <option value="{{ $barangay->barangay_id }}" {{ request('barangay') == $barangay->barangay_id ? 'selected' : '' }}>
                                {{ $barangay->barangay_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                @if(request('search') || request('barangay') || request('program'))
                    <a href="{{ route('farmers.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-times mr-2"></i>Clear All
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Farmers Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                Farmers List
                @if(request('search') || request('barangay'))
                    <span class="text-sm font-normal text-gray-600">
                        - Filtered by: 
                        @if(request('search'))
                            Search "{{ request('search') }}"
                        @endif
                        @if(request('search') && request('barangay')) & @endif
                        @if(request('barangay'))
                            Barangay "{{ $barangays->firstWhere('barangay_id', request('barangay'))->barangay_name ?? '' }}"
                        @endif
                    </span>
                @endif
            </h3>
        </div>

        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-green-600">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase tracking-wider" style="width: 20%;">Farmer</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase tracking-wider" style="width: 12%;">Contact</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase tracking-wider" style="width: 22%;">Location</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase tracking-wider" style="width: 18%;">Commodities</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase tracking-wider" style="width: 15%;">Reg. Date</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase tracking-wider" style="width: 13%;">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($farmers as $farmer)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-3 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-green-600 flex items-center justify-center">
                                            <span class="text-white font-medium text-sm">
                                                {{ strtoupper(substr($farmer->first_name, 0, 1) . substr($farmer->last_name, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $farmer->first_name }} {{ $farmer->middle_name }} {{ $farmer->last_name }}
                                            @if($farmer->suffix && strtolower($farmer->suffix) !== 'n/a')
                                                {{ $farmer->suffix }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $farmer->farmer_id }} • {{ ucfirst($farmer->gender) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-4">
                                <div class="text-sm text-gray-900">
                                    @if($farmer->contact_number)
                                        <div class="flex items-center">
                                            <i class="fas fa-mobile-alt mr-1 text-gray-400 text-xs"></i>
                                            <span class="text-xs">{{ $farmer->contact_number }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">No contact</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-3 py-4">
                                <div class="text-sm text-gray-900 font-medium">
                                    {{ $farmer->barangay->barangay_name ?? 'Not specified' }}
                                </div>
                                @if($farmer->address_details)
                                    <div class="text-xs text-gray-500 break-words max-w-xs">
                                        {{ $farmer->address_details }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-3 py-4">
                                @if($farmer->commodities->count() > 0)
                                    <div class="flex flex-col gap-1">
                                        @foreach($farmer->commodities as $commodity)
                                            <div class="bg-green-100 text-green-800 rounded px-2 py-1 text-xs flex items-center">
                                                <i class="fas fa-leaf mr-1"></i>{{ $commodity->commodity_name }}
                                                @if($commodity->pivot->is_primary)
                                                    <span class="ml-1 text-green-600">★</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        Not specified
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-4 text-xs text-gray-500">
                                @if($farmer->registration_date)
                                    {{ $farmer->registration_date->format('M j, Y') }}<br>
                                    <span class="text-gray-400">{{ $farmer->registration_date->format('g:i A') }}</span>
                                @else
                                    Not available
                                @endif
                            </td>
                            <td class="px-3 py-4 text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="viewFarmer('{{ $farmer->farmer_id }}')" 
                                            class="text-blue-600 hover:text-blue-900 transition-colors p-2 rounded hover:bg-blue-50" title="View">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>
                                    <button onclick="editFarmer('{{ $farmer->farmer_id }}')" 
                                            class="text-green-600 hover:text-green-800 transition-colors p-2 rounded hover:bg-green-50" title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </button>
                                    <button onclick="archiveFarmer('{{ $farmer->farmer_id }}', '{{ $farmer->first_name }} {{ $farmer->last_name }}')" 
                                            class="text-orange-600 hover:text-orange-900 transition-colors p-2 rounded hover:bg-orange-50" title="Archive">
                                        <i class="fas fa-archive text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center py-8">
                                    <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg">No farmers found</p>
                                    <p class="text-sm mt-2">Try adjusting your search or filters</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($farmers->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $farmers->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Include Farmer Registration Modal -->
@include('modals.farmer-modal')

<!-- Archive Modal -->
<!-- Archive Farmer Modal -->
<div id="archiveModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[99999]">
    <div class="relative top-20 mx-auto p-0 w-full max-w-md shadow-lg rounded-lg bg-white">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200 rounded-t-lg px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <i class="fas fa-archive text-orange-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Archive Farmer</h3>
                </div>
                <button onclick="closeArchiveModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <form id="archiveForm" method="POST" class="p-6">
            @csrf
            <input type="hidden" id="archive_farmer_id" name="farmer_id">
            
            <!-- Message -->
            <div class="mb-4">
                <p class="text-sm text-gray-600">
                    Are you sure you want to archive 
                    <strong class="text-gray-900" id="archive_farmer_name"></strong>?
                </p>
                <p class="text-xs text-gray-500 mt-2">
                    This farmer will be moved to the archive but can be restored later.
                </p>
            </div>

            <!-- Reason Input -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Reason for archiving <span class="text-red-500">*</span>
                </label>
                <textarea name="archive_reason" id="archive_reason" rows="3" required 
                          placeholder="e.g., Farmer relocated, Duplicate entry, etc."
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeArchiveModal()" 
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition-colors">
                    <i class="fas fa-archive mr-2"></i>Archive
                </button>
            </div>
        </form>
    </div>
</div>

@push('additional-js')
<script>
// Open farmer registration modal
function openFarmerModal() {
    const modal = new bootstrap.Modal(document.getElementById('farmerModal'));
    modal.show();
}

// Open geo-tagging modal
function openGeotaggingModal() {
    const modal = new bootstrap.Modal(document.getElementById('geotaggingModal'));
    modal.show();
}

// View farmer details
function viewFarmer(farmerId) {
    fetch(`/farmers/${farmerId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('viewFarmerContent').innerHTML = data.html;
                new bootstrap.Modal(document.getElementById('viewFarmerModal')).show();
            } else {
                alert('Error loading farmer details: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading farmer details');
        });
}

function updateRegistrationBadge(badgeId, isRegistered, programName, colorClass, yesText = 'Registered', noText = 'Not Registered') {
    const badge = document.getElementById(badgeId);
    if (isRegistered) {
        badge.classList.remove('bg-light');
        badge.classList.add(`bg-${colorClass}-subtle`, `text-${colorClass}`);
        badge.querySelector('.badge').classList.remove('bg-secondary');
        badge.querySelector('.badge').classList.add(`bg-${colorClass}`);
        badge.querySelector('.badge').textContent = yesText;
    } else {
        badge.classList.remove(`bg-${colorClass}-subtle`, `text-${colorClass}`);
        badge.classList.add('bg-light');
        badge.querySelector('.badge').classList.remove(`bg-${colorClass}`);
        badge.querySelector('.badge').classList.add('bg-secondary');
        badge.querySelector('.badge').textContent = noText;
    }
}

// ===== EDIT FARMER FUNCTIONALITY - LEGACY STYLE =====
// Initialize global variables for edit
window.editCommodities = [];
window.editCommodityOptions = @json($commodities);

async function editFarmer(farmerId) {
    try {
        const response = await fetch(`/farmers/${farmerId}/edit`);
        if (!response.ok) throw new Error('Failed to fetch farmer data');
        
        const data = await response.json();
        const farmer = data.farmer;
        
        // Populate form fields directly - EXACTLY like legacy code
        document.getElementById('edit_farmer_id').value = farmer.farmer_id;
        document.getElementById('edit_first_name').value = farmer.first_name || '';
        document.getElementById('edit_middle_name').value = farmer.middle_name || '';
        document.getElementById('edit_last_name').value = farmer.last_name || '';
        document.getElementById('edit_suffix').value = farmer.suffix || '';
        document.getElementById('edit_gender').value = farmer.gender || '';
        document.getElementById('edit_birth_date').value = farmer.birth_date || '';
        document.getElementById('edit_contact_number').value = farmer.contact_number || '';
        document.getElementById('edit_barangay_id').value = farmer.barangay_id || '';
        document.getElementById('edit_address_details').value = farmer.address_details || '';
        document.getElementById('edit_civil_status').value = farmer.civil_status || '';
        document.getElementById('edit_spouse_name').value = farmer.spouse_name || '';
        document.getElementById('edit_household_size').value = farmer.household_size || 1;
        document.getElementById('edit_education_level').value = farmer.education_level || '';
        document.getElementById('edit_occupation').value = farmer.occupation || '';
        document.getElementById('edit_other_income_source').value = farmer.other_income_source || '';
        document.getElementById('edit_land_area_hectares').value = farmer.land_area_hectares || '';
        
        // Checkboxes
        document.getElementById('edit_is_member_of_4ps').checked = farmer.is_member_of_4ps || false;
        document.getElementById('edit_is_ip').checked = farmer.is_ip || false;
        document.getElementById('edit_is_rsbsa').checked = farmer.is_rsbsa || false;
        document.getElementById('edit_is_ncfrs').checked = farmer.is_ncfrs || false;
        document.getElementById('edit_is_fisherfolk').checked = farmer.is_fisherfolk || false;
        document.getElementById('edit_is_boat').checked = farmer.is_boat || false;
        
        // Toggle spouse field
        toggleEditSpouseField();
        
        // Load commodities
        window.editCommodities = farmer.commodities || [];
        renderEditCommodities();
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('editFarmerModal'));
        modal.show();
        
    } catch (error) {
        console.error('Error loading farmer:', error);
        alert('Failed to load farmer data');
    }
}

// Render commodities - EXACTLY like legacy
function renderEditCommodities() {
    const container = document.getElementById('editCommoditiesContainer');
    let html = '';
    
    if (window.editCommodities.length === 0) {
        window.editCommodities.push({
            commodity_id: '',
            land_area_hectares: 0,
            years_farming: 0,
            is_primary: 1
        });
    }
    
    window.editCommodities.forEach((commodity, index) => {
        html += `<div class="row mb-3 commodity-row" data-index="${index}">`;
        html += `<div class="col-md-4">`;
        html += `<select class="form-select" name="commodities[${index}][commodity_id]" required>`;
        html += `<option value="">Select Commodity</option>`;
        window.editCommodityOptions.forEach(opt => {
            const selected = commodity.commodity_id == opt.commodity_id ? 'selected' : '';
            html += `<option value="${opt.commodity_id}" ${selected}>${opt.commodity_name}</option>`;
        });
        html += `</select></div>`;
        html += `<div class="col-md-3"><input type="number" class="form-control" name="commodities[${index}][land_area_hectares]" value="${commodity.land_area_hectares || 0}" step="0.01" placeholder="Land Area (Ha)" required></div>`;
        html += `<div class="col-md-2"><input type="number" class="form-control" name="commodities[${index}][years_farming]" value="${commodity.years_farming || 0}" placeholder="Years" required></div>`;
        html += `<div class="col-md-2"><input type="radio" name="primary_commodity_index" value="${index}" ${commodity.is_primary ? 'checked' : ''}> Primary</div>`;
        html += `<div class="col-md-1"><button type="button" class="btn btn-sm btn-danger" onclick="removeEditCommodity(${index})" ${index === 0 ? 'disabled' : ''}><i class="fas fa-times"></i></button></div>`;
        html += `</div>`;
    });
    
    container.innerHTML = html;
}

function addEditCommodity() {
    window.editCommodities.push({
        commodity_id: '',
        land_area_hectares: 0,
        years_farming: 0,
        is_primary: 0
    });
    renderEditCommodities();
}

function removeEditCommodity(index) {
    if (window.editCommodities.length > 1) {
        window.editCommodities.splice(index, 1);
        renderEditCommodities();
    }
}

function toggleEditSpouseField() {
    const civilStatus = document.getElementById('edit_civil_status').value;
    const spouseField = document.getElementById('edit_spouse_field');
    const spouseInput = document.getElementById('edit_spouse_name');
    
    if (civilStatus === 'Married') {
        spouseField.style.display = 'block';
        spouseInput.required = true;
    } else {
        spouseField.style.display = 'none';
        spouseInput.required = false;
        spouseInput.value = '';
    }
}

// Form submission - EXACTLY like legacy
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editFarmerForm');
    if (editForm) {
        editForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(editForm);
            const farmerId = document.getElementById('edit_farmer_id').value;
            
            const submitBtn = editForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Updating...';
            submitBtn.disabled = true;
            
            try {
                const response = await fetch(`/farmers/${farmerId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert(data.message);
                    bootstrap.Modal.getInstance(document.getElementById('editFarmerModal')).hide();
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error updating farmer');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    }
});

// View photo in new tab
function viewPhoto(photoUrl) {
    window.open(photoUrl, '_blank');
}

// Archive farmer

function archiveFarmer(farmerId, farmerName) {
    document.getElementById('archive_farmer_id').value = farmerId;
    document.getElementById('archive_farmer_name').textContent = farmerName;
    document.getElementById('archiveForm').action = `/farmers/${farmerId}/archive`;
    document.getElementById('archiveModal').classList.remove('hidden');
}

function closeArchiveModal() {
    document.getElementById('archiveModal').classList.add('hidden');
    document.getElementById('archive_reason').value = '';
}

// Export to PDF
function exportToPDF() {
    // Get current URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const search = urlParams.get('search') || '';
    const barangay = urlParams.get('barangay') || '';
    
    // Build export URL with filters
    let exportUrl = '{{ route("farmers.export-pdf") }}';
    const params = [];
    
    if (search) {
        params.push('search=' + encodeURIComponent(search));
    }
    
    if (barangay) {
        params.push('barangay=' + encodeURIComponent(barangay));
    }
    
    if (params.length > 0) {
        exportUrl += '?' + params.join('&');
    }
    
    // Show loading indicator on button
    const exportBtn = event.target;
    const originalText = exportBtn.innerHTML;
    exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating PDF...';
    exportBtn.disabled = true;
    
    // Open PDF in new window
    window.open(exportUrl, '_blank');
    
    // Reset button after 3 seconds
    setTimeout(() => {
        exportBtn.innerHTML = originalText;
        exportBtn.disabled = false;
    }, 3000);
}

// Auto-suggest functionality for farmer search
function searchFarmersAutoSuggest(query) {
    const suggestions = document.getElementById('farmer_suggestions');
    
    if (!suggestions) return;

    if (query.length < 1) {
        suggestions.innerHTML = '';
        suggestions.classList.add('hidden');
        return;
    }

    // Show loading indicator
    suggestions.innerHTML = '<div class="px-3 py-2 text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Searching...</div>';
    suggestions.classList.remove('hidden');

    // Make AJAX request to get farmer suggestions
    fetch('/api/farmers/search?query=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            if (data.success && data.farmers && data.farmers.length > 0) {
                let html = '';
                data.farmers.forEach(farmer => {
                    html += `
                        <div class="px-3 py-2 hover:bg-gray-100 cursor-pointer border-b last:border-b-0 farmer-suggestion-item" 
                             onclick="selectFarmerSuggestion('${farmer.farmer_id}', '${farmer.full_name.replace(/'/g, "\\'")}', '${farmer.contact_number}')">
                            <div class="font-medium text-gray-900">${farmer.full_name}</div>
                            <div class="text-sm text-gray-600">ID: ${farmer.farmer_id} | Contact: ${farmer.contact_number}</div>
                            <div class="text-xs text-gray-500">${farmer.barangay_name}</div>
                        </div>
                    `;
                });
                suggestions.innerHTML = html;
                suggestions.classList.remove('hidden');
            } else {
                suggestions.innerHTML = '<div class="px-3 py-2 text-gray-500">No farmers found matching your search</div>';
                suggestions.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            suggestions.innerHTML = '<div class="px-3 py-2 text-red-500">Error loading suggestions</div>';
            suggestions.classList.remove('hidden');
        });
}

function selectFarmerSuggestion(farmerId, farmerName, contactNumber) {
    const searchInput = document.getElementById('farmer_search');
    searchInput.value = farmerName;
    hideFarmerSuggestions();
    
    // Trigger form submission
    const form = searchInput.closest('form');
    if (form) {
        form.submit();
    }
}

function hideFarmerSuggestions() {
    const suggestions = document.getElementById('farmer_suggestions');
    if (suggestions) {
        setTimeout(() => {
            suggestions.classList.add('hidden');
        }, 200);
    }
}

// Close suggestions when clicking outside
document.addEventListener('click', function(event) {
    const searchInput = document.getElementById('farmer_search');
    const suggestions = document.getElementById('farmer_suggestions');
    
    if (searchInput && suggestions && !searchInput.contains(event.target) && !suggestions.contains(event.target)) {
        suggestions.classList.add('hidden');
    }
});
</script>
@endpush

<!-- Farmer Registration Modal -->
@include('modals.farmer-modal')

<!-- Farmer View Modal -->
@include('modals.farmer-view-modal')

<!-- Farmer Edit Modal -->
@include('modals.farmer-edit-modal')

<!-- Geo-tagging Modal -->
@include('modals.geo-tagging-modal')

@endsection

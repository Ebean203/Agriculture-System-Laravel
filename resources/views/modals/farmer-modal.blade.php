<!-- Farmer Registration Modal -->
<div class="modal fade" id="farmerModal" tabindex="-1" aria-labelledby="farmerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="farmerModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Register New Farmer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="farmerRegistrationForm" method="POST" action="{{ route('farmers.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Personal Information Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="middle_name" class="form-label">Middle Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="middle_name" name="middle_name" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="suffix" class="form-label">Suffix <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="suffix" name="suffix" placeholder="Jr., Sr., III, etc." required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="birth_date" class="form-label">Birth Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Registrations Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-certificate me-2"></i>Registrations</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" value="1" id="is_rsbsa" name="is_rsbsa">
                                        <label class="form-check-label" for="is_rsbsa">RSBSA Registered</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" value="1" id="is_ncfrs" name="is_ncfrs">
                                        <label class="form-check-label" for="is_ncfrs">NCFRS Registered</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" value="1" id="is_fisherfolk" name="is_fisherfolk">
                                        <label class="form-check-label" for="is_fisherfolk">Fisherfolk Registered</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" value="1" id="is_boat" name="is_boat">
                                        <label class="form-check-label" for="is_boat">Has Boat</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-address-book me-2"></i>Contact Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="contact_number" name="contact_number" required placeholder="09xxxxxxxxx">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="barangay" class="form-label">Barangay <span class="text-danger">*</span></label>
                                    <select class="form-select" id="barangay" name="barangay_id" required>
                                        <option value="">Select Barangay</option>
                                        @foreach($barangays as $barangay)
                                            <option value="{{ $barangay->barangay_id }}">{{ $barangay->barangay_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address_details" class="form-label">Full Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="address_details" name="address_details" rows="2" required placeholder="Complete residential address"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" value="1" id="is_member_of_4ps" name="is_member_of_4ps">
                                        <label class="form-check-label" for="is_member_of_4ps">Member of 4Ps</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" value="1" id="is_ip" name="is_ip">
                                        <label class="form-check-label" for="is_ip">Indigenous People (IP)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="other_income_source" class="form-label">Other Income Source <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="other_income_source" name="other_income_source" rows="2" placeholder="e.g., fishing, vending, etc." required></textarea>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="land_area_hectares" class="form-label">Total Land Area (Hectares)</label>
                                    <input type="number" class="form-control" id="land_area_hectares" name="land_area_hectares" step="0.01" min="0" placeholder="0.00">
                                    <small class="form-text text-muted">Total land area owned</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Household Information Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-home me-2"></i>Household Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="civil_status" class="form-label">Civil Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="civil_status" name="civil_status" required onchange="toggleSpouseField()">
                                        <option value="">Select</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Widowed">Widowed</option>
                                        <option value="Separated">Separated</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3" id="spouse_field" style="display:none;">
                                    <label for="spouse_name" class="form-label">Spouse Name</label>
                                    <input type="text" class="form-control" id="spouse_name" name="spouse_name">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="household_size" class="form-label">Household Size</label>
                                    <input type="number" min="0" class="form-control" id="household_size" name="household_size">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="education_level" class="form-label">Highest Education <span class="text-danger">*</span></label>
                                    <select class="form-select" id="education_level" name="education_level" required>
                                        <option value="">Select Education Level</option>
                                        <option value="Elementary">Elementary</option>
                                        <option value="Highschool">High School</option>
                                        <option value="College">College</option>
                                        <option value="Vocational">Vocational</option>
                                        <option value="Graduate">Graduate</option>
                                        <option value="Not Specified">Not Specified</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="occupation" class="form-label">Primary Occupation <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="occupation" name="occupation" placeholder="e.g., Farmer, Fisherman" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Farming Details Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-tractor me-2"></i>Farming Details</h6>
                            <button type="button" class="btn btn-sm btn-success" id="addCommodityBtn">
                                <i class="fas fa-plus me-1"></i>Add Commodity
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2 border-bottom pb-2">
                                <div class="col-md-5"><strong>Commodity</strong></div>
                                <div class="col-md-3 text-center"><strong>Years Experience</strong></div>
                                <div class="col-md-2 text-center"><strong>Primary</strong></div>
                                <div class="col-md-2 text-center"><strong>Action</strong></div>
                            </div>
                            
                            <div id="commoditiesContainer">
                                <div class="commodity-row mb-3 p-3 border rounded bg-light" data-commodity-index="0">
                                    <div class="row align-items-end">
                                        <div class="col-md-5 mb-3">
                                            <select class="form-select commodity-select" name="commodities[0][commodity_id]" required>
                                                <option value="">Select Commodity</option>
                                                @foreach($commodities as $commodity)
                                                    <option value="{{ $commodity->commodity_id }}">{{ $commodity->commodity_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <input type="number" min="0" max="100" class="form-control text-center" name="commodities[0][years_farming]" placeholder="0" required>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <div class="form-check mt-2 text-center">
                                                <input class="form-check-input primary-commodity-radio" type="radio" name="primary_commodity_index" value="0" checked required>
                                                <label class="form-check-label text-success fw-bold d-block">
                                                    <i class="fas fa-star me-1"></i>Primary
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <div class="mt-2 text-center">
                                                <span class="text-muted small">
                                                    <i class="fas fa-lock me-1"></i>Required
                                                </span>
                                            </div>
                                        </div>
                                    </div>
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
                        <i class="fas fa-save me-1"></i>Register Farmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('additional-js')
<script>
function toggleSpouseField() {
    const civilStatus = document.getElementById('civil_status').value;
    const spouseField = document.getElementById('spouse_field');
    const spouseInput = document.getElementById('spouse_name');
    
    if (civilStatus === 'Single') {
        spouseField.style.display = 'none';
        spouseInput.removeAttribute('required');
        spouseInput.value = '';
    } else if (civilStatus === 'Married') {
        spouseField.style.display = 'block';
        spouseInput.setAttribute('required', 'required');
    } else {
        spouseField.style.display = 'block';
        spouseInput.removeAttribute('required');
    }
}

let commodityIndex = 1;

function addCommodityRow() {
    const container = document.getElementById('commoditiesContainer');
    const commodityOptions = `
        <option value="">Select Commodity</option>
        @foreach($commodities as $commodity)
            <option value="{{ $commodity->commodity_id }}">{{ $commodity->commodity_name }}</option>
        @endforeach
    `;
    
    const newRow = document.createElement('div');
    newRow.className = 'commodity-row mb-3 p-3 border rounded';
    newRow.setAttribute('data-commodity-index', commodityIndex);
    
    newRow.innerHTML = `
        <div class="row align-items-end">
            <div class="col-md-5 mb-3">
                <select class="form-select commodity-select" name="commodities[${commodityIndex}][commodity_id]" required>
                    ${commodityOptions}
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <input type="number" min="0" max="100" class="form-control text-center" name="commodities[${commodityIndex}][years_farming]" placeholder="0" required>
            </div>
            <div class="col-md-2 mb-3">
                <div class="form-check mt-2 text-center">
                    <input class="form-check-input primary-commodity-radio" type="radio" name="primary_commodity_index" value="${commodityIndex}">
                    <label class="form-check-label d-block">
                        <i class="fas fa-star me-1"></i>Primary
                    </label>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="mt-2 text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-commodity-btn">
                        <i class="fas fa-trash me-1"></i>Remove
                    </button>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(newRow);
    commodityIndex++;
    
    newRow.querySelector('.remove-commodity-btn').addEventListener('click', function() {
        const rows = container.querySelectorAll('.commodity-row');
        if (rows.length <= 1) {
            alert('At least one commodity is required.');
            return;
        }
        const radio = newRow.querySelector('.primary-commodity-radio');
        if (radio && radio.checked) {
            const firstRow = container.querySelector('.commodity-row');
            firstRow.querySelector('.primary-commodity-radio').checked = true;
        }
        newRow.remove();
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const addCommodityBtn = document.getElementById('addCommodityBtn');
    if (addCommodityBtn) {
        addCommodityBtn.addEventListener('click', addCommodityRow);
    }
    
    // Initialize remove button listeners for existing rows
    document.querySelectorAll('.remove-commodity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const container = document.getElementById('commoditiesContainer');
            const rows = container.querySelectorAll('.commodity-row');
            if (rows.length <= 1) {
                alert('At least one commodity is required.');
                return;
            }
            const row = btn.closest('.commodity-row');
            const radio = row.querySelector('.primary-commodity-radio');
            if (radio && radio.checked) {
                const firstRow = container.querySelector('.commodity-row');
                firstRow.querySelector('.primary-commodity-radio').checked = true;
            }
            row.remove();
        });
    });
});
</script>
@endpush

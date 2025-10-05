<!-- Edit Farmer Modal -->
<div class="modal fade" id="editFarmerModal" tabindex="-1" aria-labelledby="editFarmerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="editFarmerModalLabel">
                    <i class="fas fa-user-edit me-2"></i>Edit Farmer Information
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editFarmerForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="farmer_id" id="edit_farmer_id" value="">
                    
                    <!-- Personal Information Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="edit_first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="edit_middle_name" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" id="edit_middle_name" name="middle_name">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="edit_last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="edit_suffix" class="form-label">Suffix</label>
                                    <input type="text" class="form-control" id="edit_suffix" name="suffix" placeholder="Jr., Sr., III, etc.">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_birth_date" class="form-label">Birth Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="edit_birth_date" name="birth_date" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select class="form-select" id="edit_gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
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
                                    <label for="edit_contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="edit_contact_number" name="contact_number" required placeholder="09xxxxxxxxx">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_barangay_id" class="form-label">Barangay <span class="text-danger">*</span></label>
                                    <select class="form-select" id="edit_barangay_id" name="barangay_id" required>
                                        <option value="">Select Barangay</option>
                                        @foreach($barangays as $barangay)
                                            <option value="{{ $barangay->barangay_id }}">{{ $barangay->barangay_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_address_details" class="form-label">Full Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="edit_address_details" name="address_details" rows="2" required placeholder="Complete residential address"></textarea>
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
                                <div class="col-md-6 mb-3">
                                    <label for="edit_civil_status" class="form-label">Civil Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="edit_civil_status" name="civil_status" required onchange="toggleEditSpouseField()">
                                        <option value="">Select</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Widowed">Widowed</option>
                                        <option value="Separated">Separated</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3" id="edit_spouse_field">
                                    <label for="edit_spouse_name" class="form-label">Spouse Name</label>
                                    <input type="text" class="form-control" id="edit_spouse_name" name="spouse_name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="edit_household_size" class="form-label">Household Size <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="edit_household_size" name="household_size" min="1" value="1" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="edit_education_level" class="form-label">Education Level <span class="text-danger">*</span></label>
                                    <select class="form-select" id="edit_education_level" name="education_level" required>
                                        <option value="">Select Education Level</option>
                                        <option value="Elementary">Elementary</option>
                                        <option value="Highschool">High School</option>
                                        <option value="College">College</option>
                                        <option value="Vocational">Vocational</option>
                                        <option value="Graduate">Graduate</option>
                                        <option value="Not Specified">Not Specified</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="edit_occupation" class="form-label">Occupation <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_occupation" name="occupation" value="Farmer" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <label for="edit_other_income_source" class="form-label">Other Income Source</label>
                                    <input type="text" class="form-control" id="edit_other_income_source" name="other_income_source" placeholder="e.g., OFW allotment, business">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="edit_land_area_hectares" class="form-label">Land Area (Ha)</label>
                                    <input type="number" class="form-control" id="edit_land_area_hectares" name="land_area_hectares" step="0.01" min="0" placeholder="0.00">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="edit_is_member_of_4ps" name="is_member_of_4ps">
                                        <label class="form-check-label" for="edit_is_member_of_4ps">Member of 4Ps</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="edit_is_ip" name="is_ip">
                                        <label class="form-check-label" for="edit_is_ip">Indigenous Peoples (IP)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="edit_is_rsbsa" name="is_rsbsa">
                                        <label class="form-check-label" for="edit_is_rsbsa">RSBSA Registered</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="edit_is_ncfrs" name="is_ncfrs">
                                        <label class="form-check-label" for="edit_is_ncfrs">NCFRS Registered</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="edit_is_fisherfolk" name="is_fisherfolk">
                                        <label class="form-check-label" for="edit_is_fisherfolk">Fisherfolk Registered</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="edit_is_boat" name="is_boat">
                                        <label class="form-check-label" for="edit_is_boat">Has Boat</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Farming Details Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-tractor me-2"></i>Farming Details</h6>
                            <button type="button" class="btn btn-sm btn-success" id="editAddCommodityBtn">
                                <i class="fas fa-plus me-1"></i>Add Commodity
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2 border-bottom pb-2">
                                <div class="col-md-4"><strong>Commodity</strong></div>
                                <div class="col-md-2 text-center"><strong>Land Area (ha)</strong></div>
                                <div class="col-md-2 text-center"><strong>Years Experience</strong></div>
                                <div class="col-md-2 text-center"><strong>Primary</strong></div>
                                <div class="col-md-2 text-center"><strong>Action</strong></div>
                            </div>
                            <div id="editCommoditiesContainer">
                                <!-- Commodity rows will be dynamically inserted here by JS -->
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
                        <i class="fas fa-save me-1"></i>Update Farmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

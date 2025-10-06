@extends('layouts.agriculture')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div class="flex items-start">
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center mr-3">
                    <i class="fas fa-users-cog text-agri-green text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Manage Staff</h1>
                    <p class="text-gray-600">Manage Municipal Agriculture Office personnel</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="openAddStaffModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center font-medium">
                    <i class="fas fa-user-plus mr-2"></i>Add Staff
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-4">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">
            <ul class="list-disc ml-6">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Staff Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-agri-green text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><i class="fas fa-user mr-1"></i>Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><i class="fas fa-briefcase mr-1"></i>Position</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><i class="fas fa-phone mr-1"></i>Contact Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><i class="fas fa-user-tag mr-1"></i>Role</th>
                        <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider"><i class="fas fa-cogs mr-1"></i>Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($staff as $s)
                    <tr class="hover:bg-agri-light transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $s->first_name }} {{ $s->last_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-900">{{ $s->position }}</div></td>
                        <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-900">{{ $s->contact_number ?? 'N/A' }}</div></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white {{ strtolower($s->role_name) === 'admin' ? 'bg-red-600' : 'bg-blue-600' }}">
                                <i class="fas fa-user-shield mr-1"></i>{{ $s->role_name ?? '—' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <button 
                                data-staff-id="{{ $s->staff_id }}"
                                data-first-name="{{ $s->first_name }}"
                                data-last-name="{{ $s->last_name }}"
                                data-position="{{ $s->position }}"
                                data-contact-number="{{ $s->contact_number }}"
                                data-username="{{ $s->username }}"
                                data-role-name="{{ $s->role_name }}"
                                onclick="viewStaff({staff_id:this.dataset.staffId,first_name:this.dataset.firstName,last_name:this.dataset.lastName,position:this.dataset.position,contact_number:this.dataset.contactNumber,username:this.dataset.username,role_name:this.dataset.roleName})"
                                class="text-blue-600 hover:text-blue-900 mr-3 transition-colors duration-200">
                                <i class="fas fa-eye text-lg"></i>
                            </button>
                            <button onclick="archiveStaff({{ $s->staff_id }}, '{{ addslashes($s->first_name.' '.$s->last_name) }}')" class="text-orange-600 hover:text-orange-900 transition-colors duration-200">
                                <i class="fas fa-archive text-lg"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-users-slash text-3xl mb-3"></i>
                            <div>No staff found.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Staff Modal -->
<div id="viewStaffModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title text-success"><i class="fas fa-user-circle me-2"></i>Staff Details</h5>
                <button type="button" class="btn-close" onclick="closeViewModal()"></button>
            </div>
            <div class="modal-body p-0" id="viewStaffContent"></div>
        </div>
    </div>
    </div>

<!-- Archive Confirmation Modal -->
<div id="archiveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 mb-4">
                <i class="fas fa-archive text-orange-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Archive Staff Member</h3>
            <p class="text-sm text-gray-500 mb-4">Are you sure you want to archive <strong id="archive_staff_name"></strong>? This will move them to the archived staff list.</p>
            <div class="flex justify-center space-x-3">
                <button onclick="closeArchiveModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">Cancel</button>
                <button onclick="confirmArchive()" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors"><i class="fas fa-archive mr-2"></i>Archive</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Staff Modal -->
<div class="modal fade" id="addStaffModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title text-primary"><i class="fas fa-user-plus me-2"></i>Add New Staff Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addStaffForm" action="{{ route('staff.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" id="first_name" name="first_name" required class="form-control" placeholder="Enter first name">
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" id="last_name" name="last_name" required class="form-control" placeholder="Enter last name">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                        <input type="text" id="position" name="position" required class="form-control" placeholder="e.g., Agricultural Officer, MAO Head">
                    </div>
                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                        <input type="tel" id="contact_number" name="contact_number" required class="form-control" placeholder="09xxxxxxxxx">
                    </div>
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                        <select id="role_id" name="role_id" required class="form-select">
                            <option value="">Select Role</option>
                            @foreach($roles as $r)
                                <option value='{{ $r->role_id }}'>{{ $r->role }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" id="username" name="username" required class="form-control" placeholder="Enter username">
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" id="password" name="password" required class="form-control" placeholder="Enter password">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cancel</button>
                <button type="submit" form="addStaffForm" class="btn btn-primary"><i class="fas fa-user-plus me-2"></i>Add Staff Member</button>
            </div>
        </div>
    </div>
</div>

@push('additional-js')
<script>
function openAddStaffModal(){ new bootstrap.Modal(document.getElementById('addStaffModal')).show(); }
function closeAddStaffModal(){ bootstrap.Modal.getInstance(document.getElementById('addStaffModal'))?.hide(); }
function viewStaff(staff){
    const html = `
        <div class="container-fluid p-0">
            <div class="text-white p-3 rounded-top mb-3" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="h-16 w-16 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-user text-gray-600 fa-2x"></i>
                        </div>
                    </div>
                    <div class="col">
                        <h4 class="mb-1"><i class="fas fa-user-circle me-2"></i>${staff.first_name} ${staff.last_name}</h4>
                        <small class="opacity-75"><i class="fas fa-id-card me-1"></i>Staff ID: ${staff.staff_id ?? ''}</small>
                    </div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light border-0">
                            <h6 class="card-title mb-0 text-success"><i class="fas fa-user me-2"></i>Personal Information</h6>
                        </div>
                        <div class="card-body">
                            <div><strong>Contact:</strong> ${staff.contact_number ?? ''}</div>
                            <div><strong>Username:</strong> ${staff.username ?? ''}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light border-0">
                            <h6 class="card-title mb-0 text-info"><i class="fas fa-briefcase me-2"></i>Work Information</h6>
                        </div>
                        <div class="card-body">
                            <div><strong>Position:</strong> ${staff.position ?? ''}</div>
                            <div><strong>Role:</strong> ${staff.role_name ?? staff.role ?? ''}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    document.getElementById('viewStaffContent').innerHTML = html;
    new bootstrap.Modal(document.getElementById('viewStaffModal')).show();
}
function closeViewModal(){ const inst = bootstrap.Modal.getInstance(document.getElementById('viewStaffModal')); if(inst){inst.hide();}}
let staffToArchive=null;
function archiveStaff(id,name){ staffToArchive=id; document.getElementById('archive_staff_name').textContent=name; document.getElementById('archiveModal').classList.remove('hidden'); }
function closeArchiveModal(){ document.getElementById('archiveModal').classList.add('hidden'); staffToArchive=null; }
function confirmArchive(){ if(staffToArchive){ alert('Staff member archived successfully! (placeholder)'); closeArchiveModal(); }}
</script>
@endpush

@endsection

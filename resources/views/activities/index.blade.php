@extends('layouts.agriculture')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center gap-6">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-calendar-check text-agri-green mr-3"></i>
                    MAO Activities Management
                </h1>
                <p class="text-gray-600 mt-2">Manage and track Municipal Agriculture Office activities</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 min-w-fit">
                <button onclick="openAddModal()" class="bg-agri-green text-white px-4 py-2 rounded-lg hover:bg-agri-dark transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i>Add New Activity
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search activities..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-agri-green focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Activity Type</label>
                    <select name="activity_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-agri-green focus:border-transparent">
                        <option value="">All Types</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ $activityType===$type?'selected':'' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-agri-green focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-agri-green focus:border-transparent">
                </div>
            </div>
            <div class="flex space-x-3">
                <button type="submit" class="bg-agri-green text-white px-4 py-2 rounded-md hover:bg-green-600 transition-colors flex items-center">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </div>
        </form>
    </div>

    <div class="space-y-6">
        @if($activities->count() === 0)
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-500 mb-2">No Activities Found</h3>
                <p class="text-gray-400">There are no activities matching your search criteria.</p>
            </div>
        @else
            @foreach($activities as $a)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <h3 class="text-xl font-bold text-gray-900">{{ $a->title }}</h3>
                                <span class="text-sm text-gray-500">{{ $a->activity_type }}</span>
                            </div>
                            @if(!empty($a->description))
                                <p class="text-gray-600 mt-2">{{ $a->description }}</p>
                            @endif
                            <div class="flex items-center gap-8 mt-3 text-gray-700">
                                <div class="flex items-center gap-2" data-bs-toggle="tooltip" title="Activity Date">
                                    <i class="fas fa-calendar text-agri-green"></i>
                                    <span>{{ \Carbon\Carbon::parse($a->activity_date)->format('F d, Y') }}</span>
                                </div>
                                <div class="flex items-center gap-2" data-bs-toggle="tooltip" title="Location">
                                    <i class="fas fa-map-marker-alt text-agri-green"></i>
                                    <span>{{ $a->location }}</span>
                                </div>
                                <div class="flex items-center gap-2" data-bs-toggle="tooltip" title="Staff">
                                    <i class="fas fa-user text-agri-green"></i>
                                    <span>{{ $a->staff_name ?: 'Unassigned' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button class="px-3 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white shadow" data-bs-toggle="tooltip" title="View activity" onclick='openViewModal(@json($a))'>
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow" data-bs-toggle="tooltip" title="Edit activity" onclick='openEditModal(@json($a))'>
                                <i class="fas fa-pen"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="mt-4">{{ $activities->withQueryString()->links() }}</div>
        @endif
    </div>
</div>

<!-- View Activity Modal -->
<div class="modal fade" id="viewActivityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-agri-green text-white">
                <h5 class="modal-title"><i class="fas fa-eye mr-2"></i>View Activity Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-semibold text-gray-700">Staff Member</label>
                        <p id="view_staff_name" class="form-control-plaintext border rounded px-3 py-2 bg-gray-50"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-semibold text-gray-700">Activity Type</label>
                        <p id="view_activity_type" class="form-control-plaintext border rounded px-3 py-2 bg-gray-50"></p>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label font-semibold text-gray-700">Activity Title</label>
                    <p id="view_title" class="form-control-plaintext border rounded px-3 py-2 bg-gray-50"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label font-semibold text-gray-700">Description</label>
                    <p id="view_description" class="form-control-plaintext border rounded px-3 py-2 bg-gray-50" style="min-height: 100px; white-space: pre-wrap;"></p>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-semibold text-gray-700">Activity Date</label>
                        <p id="view_activity_date" class="form-control-plaintext border rounded px-3 py-2 bg-gray-50"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-semibold text-gray-700">Location</label>
                        <p id="view_location" class="form-control-plaintext border rounded px-3 py-2 bg-gray-50"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-semibold text-gray-700">Created At</label>
                        <p id="view_created_at" class="form-control-plaintext border rounded px-3 py-2 bg-gray-50"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-semibold text-gray-700">Last Updated</label>
                        <p id="view_updated_at" class="form-control-plaintext border rounded px-3 py-2 bg-gray-50"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="closeViewAndOpenEdit()"><i class="fas fa-edit mr-2"></i>Edit Activity</button>
            </div>
        </div>
    </div>
    </div>

<!-- Add Activity Modal -->
<div class="modal fade" id="addActivityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-agri-green text-white">
                <h5 class="modal-title"><i class="fas fa-plus mr-2"></i>Add New Activity</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('activities.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Staff Member *</label>
                            <select name="staff_id" class="form-select">
                                <option value="">Select Staff Member</option>
                                @foreach($staff as $s)
                                    <option value="{{ $s->staff_id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Activity Type *</label>
                            <select name="activity_type" class="form-select" required>
                                <option value="">Select Type</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Activity Title *</label>
                        <input type="text" name="title" class="form-control" required placeholder="Enter activity title">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Enter activity description"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Activity Date *</label>
                            <input type="date" name="activity_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Location *</label>
                            <input type="text" name="location" class="form-control" required placeholder="Enter location">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save mr-2"></i>Add Activity</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Activity Modal -->
<div class="modal fade" id="editActivityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-blue-600 text-white">
                <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>Edit Activity</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editActivityForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="activity_id" id="edit_activity_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Staff Member *</label>
                            <select name="staff_id" id="edit_staff_id" class="form-select">
                                <option value="">Select Staff Member</option>
                                @foreach($staff as $s)
                                    <option value="{{ $s->staff_id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Activity Type *</label>
                            <select name="activity_type" id="edit_activity_type" class="form-select" required>
                                <option value="">Select Type</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Activity Title *</label>
                        <input type="text" name="title" id="edit_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Activity Date *</label>
                            <input type="date" name="activity_date" id="edit_activity_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Location *</label>
                            <input type="text" name="location" id="edit_location" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Update Activity</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('additional-js')
<script>
let currentActivityData = null;

function openAddModal(){ new bootstrap.Modal(document.getElementById('addActivityModal')).show(); }

function openViewModal(activity){
    document.getElementById('view_staff_name').textContent = activity.staff_name || 'Unassigned';
    document.getElementById('view_activity_type').textContent = activity.activity_type;
    document.getElementById('view_title').textContent = activity.title;
    document.getElementById('view_description').textContent = activity.description || 'No description provided';
    document.getElementById('view_activity_date').textContent = formatDate(activity.activity_date);
    document.getElementById('view_location').textContent = activity.location;
    document.getElementById('view_created_at').textContent = formatDateTime(activity.created_at);
    document.getElementById('view_updated_at').textContent = formatDateTime(activity.updated_at);
    currentActivityData = activity;
    new bootstrap.Modal(document.getElementById('viewActivityModal')).show();
}

function openEditModal(activity){
    document.getElementById('edit_activity_id').value = activity.activity_id;
    document.getElementById('edit_staff_id').value = activity.staff_id || '';
    document.getElementById('edit_activity_type').value = activity.activity_type;
    document.getElementById('edit_title').value = activity.title;
    document.getElementById('edit_description').value = activity.description || '';
    document.getElementById('edit_activity_date').value = activity.activity_date;
    document.getElementById('edit_location').value = activity.location;
    const form = document.getElementById('editActivityForm');
    form.action = `{{ url('/activities') }}/${activity.activity_id}`;
    new bootstrap.Modal(document.getElementById('editActivityModal')).show();
}

function closeViewAndOpenEdit(){
    const vm = bootstrap.Modal.getInstance(document.getElementById('viewActivityModal'));
    vm.hide();
    setTimeout(()=>{ if(currentActivityData){ openEditModal(currentActivityData); } }, 300);
}

function formatDate(d){ const dt = new Date(d); return dt.toLocaleDateString('en-US',{year:'numeric',month:'long',day:'numeric'}); }
function formatDateTime(d){ const dt = new Date(d); return dt.toLocaleString('en-US',{year:'numeric',month:'long',day:'numeric',hour:'2-digit',minute:'2-digit'}); }

document.addEventListener('DOMContentLoaded', ()=>{
    const successAlert = document.querySelector('.alert-success');
    if (successAlert){ setTimeout(()=>{ successAlert.remove(); }, 2000); }
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
@endsection

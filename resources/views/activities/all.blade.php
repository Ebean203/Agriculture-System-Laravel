@extends('layouts.agriculture')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-start">
            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center mr-3">
                <i class="fas fa-list text-gray-600 text-xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-gray-900">All Activities</h2>
                <p class="text-gray-600">Recent system activities and user actions across the application</p>
                <div class="flex items-center text-gray-500 mt-2">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    <span>{{ now()->format('l, F j, Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search activities..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-agri-green focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Activity Type</label>
                <select name="activity_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-agri-green focus:border-transparent">
                    <option value="">All Activities</option>
                    @foreach($types as $t)
                        <option value="{{ $t }}" @selected($type===$t)>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-agri-green focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-agri-green focus:border-transparent">
            </div>
            <div class="md:col-span-5 flex gap-3">
                <button type="submit" class="bg-agri-green text-white px-4 py-2 rounded-md hover:bg-green-600 transition-colors flex items-center">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('activities.all') }}" class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50">Clear</a>
            </div>
        </form>
    </div>

    <!-- Activities list -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-lg font-semibold">
                <i class="fas fa-clock text-agri-green mr-2"></i>
                Recent Activities
                <span class="ml-2 text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">{{ number_format($activities->total()) }}</span>
            </h5>
        </div>

        <div class="max-h-[520px] overflow-y-auto">
            @forelse($activities as $a)
                @php
                    $icon = 'fas fa-info-circle';
                    $iconBg = 'bg-gray-400';
                    switch($a->action_type){
                        case 'login': $icon = 'fas fa-sign-in-alt'; $iconBg = 'bg-blue-500'; break;
                        case 'farmer': $icon = 'fas fa-user-plus'; $iconBg = 'bg-green-600'; break;
                        case 'rsbsa': $icon = 'fas fa-certificate'; $iconBg = 'bg-emerald-600'; break;
                        case 'yield': $icon = 'fas fa-chart-line'; $iconBg = 'bg-orange-500'; break;
                        case 'commodity': $icon = 'fas fa-apple-alt'; $iconBg = 'bg-purple-500'; break;
                        case 'input': $icon = 'fas fa-boxes'; $iconBg = 'bg-indigo-600'; break;
                        case 'farmer_registration': $icon = 'fas fa-id-card'; $iconBg = 'bg-teal-600'; break;
                    }
                @endphp
                <div class="flex items-start p-3 mb-3 bg-white border border-gray-200 rounded-md hover:shadow-sm transition-all border-l-4 border-agri-green">
                    <div class="w-9 h-9 rounded-full {{ $iconBg }} flex items-center justify-center text-white mr-3 flex-shrink-0" title="{{ ucfirst($a->action_type) }}">
                        <i class="{{ $icon }} text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium capitalize text-gray-800">{{ $a->action_type }}</span>
                            <small class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($a->timestamp)->format('M j, Y g:i A') }}</small>
                        </div>
                        <div class="text-sm text-gray-700 leading-relaxed">{{ $a->action }}@if(!empty($a->details)): {{ $a->details }}@endif</div>
                        <div class="text-xs text-gray-500 mt-1 flex items-center">
                            <i class="fas fa-user mr-1"></i>
                            <span>{{ $a->staff_name ?? 'System' }}</span>
                            @if(!empty($a->username))
                                <span class="ml-2 px-2 py-0.5 bg-gray-100 rounded text-gray-700">{{ $a->username }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 text-gray-500">
                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-2"></i>
                    <div>No activities found</div>
                </div>
            @endforelse
        </div>

        <div class="pt-4">{{ $activities->withQueryString()->links() }}</div>
    </div>
</div>
@endsection

@extends('layouts.agriculture')

@section('title', 'Reports System - Lagonglong FARMS')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex-1">
                <h1 class="text-4xl font-bold text-gray-900 flex items-center mb-3">
                    <div class="bg-gradient-to-r from-agri-green to-agri-dark p-3 rounded-xl mr-4">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    Reports System
                </h1>
                <p class="text-gray-600 text-lg">Generate comprehensive reports for agricultural management and analytics</p>
                <div class="flex items-center mt-4 text-sm text-gray-500">
                    <i class="fas fa-user mr-2"></i>
                    Logged in as: <span class="font-semibold ml-1">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    <span class="mx-3">•</span>
                    <i class="fas fa-clock mr-2"></i>
                    {{ now()->format('F d, Y h:i A') }}
                </div>
            </div>
            <div class="bg-gradient-to-r from-agri-green to-agri-dark p-6 rounded-xl text-white text-center">
                <div class="text-3xl font-bold"><span id="savedReportsCount">{{ $reportsCount }}</span></div>
                <div class="text-sm opacity-90">Saved Reports</div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- Report Generation Form -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="border-b border-gray-200 pb-6 mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                        <div class="bg-agri-green p-2 rounded-lg mr-3">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                        Generate New Report
                    </h3>
                    <p class="text-gray-600 mt-2">Select report type and date range to generate comprehensive analytics</p>
                </div>
                
                <form id="reportForm" method="POST" action="{{ route('reports.generate') }}" target="_blank" class="space-y-8">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-list text-agri-green mr-2"></i>Report Type
                        </label>
                        <select name="report_type" required 
                                class="w-full py-4 px-4 border border-gray-300 rounded-xl focus:ring-agri-green focus:border-agri-green input-focus text-gray-900 bg-white shadow-sm">
                            <option value="">Choose a report type...</option>
                            <option value="farmers_summary">👥 Farmers Summary Report</option>
                            <option value="input_distribution">📦 Input Distribution Report</option>
                            <option value="yield_monitoring">🌾 Yield Monitoring Report</option>
                            <option value="inventory_status">📋 Current Inventory Status</option>
                            <option value="barangay_analytics">🗺️ Barangay Analytics Report</option>
                            <option value="commodity_production">🌱 Commodity Production Report</option>
                            <option value="registration_analytics">📋 Registration Analytics Report</option>
                            <option value="comprehensive_overview">📈 Comprehensive Overview Report</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-calendar-alt text-agri-green mr-2"></i>Start Date
                            </label>
                            <input type="date" name="start_date" required 
                                   value="{{ now()->subDays(30)->format('Y-m-d') }}"
                                   class="w-full py-4 px-4 border border-gray-300 rounded-xl focus:ring-agri-green focus:border-agri-green input-focus shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-calendar-check text-agri-green mr-2"></i>End Date
                            </label>
                            <input type="date" name="end_date" required 
                                   value="{{ now()->format('Y-m-d') }}"
                                   class="w-full py-4 px-4 border border-gray-300 rounded-xl focus:ring-agri-green focus:border-agri-green input-focus shadow-sm">
                        </div>
                    </div>
                    
                    <div class="bg-agri-light p-6 rounded-xl border border-agri-green/20">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-agri-green mr-3 mt-1"></i>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-save text-agri-green mr-2"></i>Report Auto-Save Information
                                </label>
                                <p class="text-sm text-gray-600 mt-1">All generated reports are automatically saved to the database and reports folder for future reference and download</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-4 pt-4">
                        <button type="submit" 
                                class="btn-generate text-white px-8 py-4 rounded-xl font-semibold flex items-center text-lg shadow-lg bg-agri-green hover:bg-agri-dark transition-colors">
                            <i class="fas fa-chart-bar mr-3"></i>Generate Report
                        </button>
                        <button type="reset" 
                                class="bg-gray-100 text-gray-700 px-8 py-4 rounded-xl font-semibold hover:bg-gray-200 transition-colors flex items-center text-lg border border-gray-300">
                            <i class="fas fa-undo mr-3"></i>Reset Form
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Report History (Compact) -->
        <div>
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="border-b border-gray-200 pb-3 mb-3">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <div class="bg-agri-green p-1.5 rounded mr-2">
                            <i class="fas fa-history text-white text-sm"></i>
                        </div>
                        Recent Reports
                    </h3>
                    <p class="text-gray-600 text-sm mt-1">Previously generated reports</p>
                </div>
                
                <div id="recentReportsContainer">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin text-agri-green mb-2"></i>
                        <p class="text-sm text-gray-500">Loading reports...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('additional-js')
<script>
// Load recent reports on page load
$(document).ready(function() {
    refreshRecentReports();
});

// Form submission handler
$('#reportForm').on('submit', function(e) {
    // Allow default form submission (opens in new tab)
    // After a delay, refresh the reports list
    setTimeout(function() {
        refreshRecentReports();
        refreshSavedReportsCount();
    }, 2000);
});

function refreshRecentReports() {
    $.ajax({
        url: '{{ route('reports.saved') }}',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success && response.reports.length > 0) {
                let html = '<div class="space-y-2 max-h-72 overflow-y-auto">';
                
                response.reports.forEach(function(report) {
                    const startDate = new Date(report.start_date);
                    const endDate = new Date(report.end_date);
                    const timestamp = new Date(report.timestamp);
                    
                    html += `
                        <div class="recent-report-item border border-gray-200 rounded-md p-3 hover:shadow-sm transition-all">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900 text-sm mb-1">
                                        ${report.report_type}
                                    </div>
                                    <div class="text-xs text-gray-600 mb-1">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        ${startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })} - 
                                        ${endDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <i class="fas fa-user mr-1"></i>
                                        ${report.generated_by}
                                        <span class="mx-1">•</span>
                                        <i class="fas fa-clock mr-1"></i>
                                        ${timestamp.toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' })}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 pt-2 border-t border-gray-100">
                                <a href="${report.file_path}" 
                                   target="_blank"
                                   class="inline-flex items-center text-agri-green hover:text-agri-dark text-xs font-medium transition-colors">
                                    <i class="fas fa-external-link-alt mr-1"></i>View Report
                                </a>
                            </div>
                        </div>
                    `;
                });
                
                html += '</div>';
                html += `
                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <a href="{{ route('reports.all') }}" class="text-agri-green hover:text-agri-dark font-medium text-xs flex items-center justify-center">
                            <i class="fas fa-archive mr-1"></i>View All Reports
                        </a>
                    </div>
                `;
                
                $('#recentReportsContainer').html(html);
            } else {
                $('#recentReportsContainer').html('<div class="text-gray-500 text-sm text-center py-4">No reports found.</div>');
            }
        },
        error: function() {
            $('#recentReportsContainer').html('<div class="text-red-500 text-sm text-center py-4">Failed to load reports.</div>');
        }
    });
}

function refreshSavedReportsCount() {
    $.ajax({
        url: '{{ route('reports.count') }}',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#savedReportsCount').text(response.count);
            }
        }
    });
}
</script>
@endpush
@endsection

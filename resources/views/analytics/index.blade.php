@extends('layouts.agriculture')

@section('title', 'Analytics Dashboard')

@push('additional-css')
<style>
    .chart-container {
        position: relative;
        height: 400px;
        width: 100%;
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .chart-type-btn {
        transition: all 0.3s ease;
    }
    .chart-type-btn:hover {
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div class="flex items-start">
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
                    <p class="text-gray-600">Interactive data visualization and insights</p>
                    <div class="flex items-center text-gray-500 mt-2">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <span>Data Range: {{ date('M j, Y', strtotime($start_date)) }} - {{ date('M j, Y', strtotime($end_date)) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Farmers (Period)</p>
                    <p class="text-2xl font-bold">{{ number_format($summary['total_farmers']) }}</p>
                </div>
                <i class="fas fa-users text-3xl text-blue-200"></i>
            </div>
        </div>
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-md p-6 text-white animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Total Yield (kg)</p>
                    <p class="text-2xl font-bold">{{ number_format($summary['total_yield'], 1) }}</p>
                </div>
                <i class="fas fa-weight-hanging text-3xl text-green-200"></i>
            </div>
        </div>
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-md p-6 text-white animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Boats Registered</p>
                    <p class="text-2xl font-bold">{{ number_format($summary['total_boats']) }}</p>
                </div>
                <i class="fas fa-ship text-3xl text-purple-200"></i>
            </div>
        </div>
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-md p-6 text-white animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">Active Commodities</p>
                    <p class="text-2xl font-bold">{{ number_format($summary['total_commodities']) }}</p>
                </div>
                <i class="fas fa-wheat-awn text-3xl text-orange-200"></i>
            </div>
        </div>
    </div>

    <!-- Controls Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-sliders-h text-green-600 mr-3"></i>Analytics Controls
        </h3>
        <form method="GET" action="{{ route('analytics.index') }}" class="space-y-4">
            <!-- Report Type Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Choose a report type...</label>
                <select name="report_type" id="reportType" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Select Report Type...</option>
                    <option value="farmer_registrations" {{ $report_type === 'farmer_registrations' ? 'selected' : '' }}>👥 Farmer Registrations Over Time</option>
                    <option value="yield_monitoring" {{ $report_type === 'yield_monitoring' ? 'selected' : '' }}>🌾 Yield Monitoring Report</option>
                    <option value="commodity_distribution" {{ $report_type === 'commodity_distribution' ? 'selected' : '' }}>🌽 Commodity Production Report</option>
                    <option value="barangay_analytics" {{ $report_type === 'barangay_analytics' ? 'selected' : '' }}>🏘️ Barangay Analytics Report</option>
                    <option value="registration_status" {{ $report_type === 'registration_status' ? 'selected' : '' }}>📋 Registration Analytics Report</option>
                </select>
            </div>

            <!-- Filters Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Barangay Filter</label>
                    <select name="barangay_filter" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">All Barangays</option>
                        @foreach($barangays as $barangay)
                            <option value="{{ $barangay->barangay_id }}" {{ $barangay_filter == $barangay->barangay_id ? 'selected' : '' }}>
                                {{ $barangay->barangay_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ $start_date }}" 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ $end_date }}" 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center">
                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>Generate Analytics
                </button>
            </div>
        </form>
    </div>

    <!-- Chart Display -->
    @if($report_type && $chartData && count($chartData['labels']) > 0)
        <div class="bg-white rounded-lg shadow-md p-6 mb-8 animate-fade-in">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <i class="fas fa-chart-area text-green-600 mr-3"></i>
                    <span id="chartTitle">
                        @php
                            $titles = [
                                'farmer_registrations' => 'Farmer Registrations Over Time',
                                'yield_monitoring' => 'Yield Production Over Time',
                                'commodity_distribution' => 'Commodity Distribution',
                                'barangay_analytics' => 'Farmers by Barangay',
                                'registration_status' => 'Registration Status Comparison'
                            ];
                        @endphp
                        {{ $titles[$report_type] ?? 'Analytics Chart' }}
                    </span>
                </h3>
                <div class="flex space-x-2">
                    <button onclick="downloadChart()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center">
                        <i class="fas fa-download mr-2"></i>Download
                    </button>
                    <button onclick="toggleFullscreen()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>

            <!-- Chart Type Switcher Buttons -->
            <div class="flex justify-center mb-4">
                <div class="bg-gray-100 rounded-lg p-1 flex space-x-1">
                    <button type="button" onclick="switchChartType('line')" id="btn-line" class="chart-type-btn px-4 py-2 rounded-md transition-colors flex items-center">
                        <i class="fas fa-chart-line mr-2"></i>Line
                    </button>
                    <button type="button" onclick="switchChartType('bar')" id="btn-bar" class="chart-type-btn px-4 py-2 rounded-md transition-colors flex items-center">
                        <i class="fas fa-chart-bar mr-2"></i>Bar
                    </button>
                    <button type="button" onclick="switchChartType('pie')" id="btn-pie" class="chart-type-btn px-4 py-2 rounded-md transition-colors flex items-center">
                        <i class="fas fa-chart-pie mr-2"></i>Pie
                    </button>
                    <button type="button" onclick="switchChartType('doughnut')" id="btn-doughnut" class="chart-type-btn px-4 py-2 rounded-md transition-colors flex items-center">
                        <i class="fas fa-circle-notch mr-2"></i>Doughnut
                    </button>
                </div>
            </div>

            <div class="chart-container">
                <canvas id="analyticsChart"></canvas>
            </div>
        </div>
    @elseif($report_type)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                <p class="text-yellow-800">No data available for the selected report type and date range. Try adjusting your filters or adding more data.</p>
            </div>
        </div>
    @else
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                <p class="text-blue-800">Please select a report type to begin generating analytics.</p>
            </div>
        </div>
    @endif
</div>

@push('additional-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script>
let analyticsChart = null;
let currentChartType = 'line';

@if(isset($chartData) && $chartData && count($chartData['labels'] ?? []) > 0)
const chartData = {!! json_encode($chartData) !!};
@else
const chartData = null;
@endif

const colorSchemes = {
    blue: ['#3B82F6', '#1E40AF', '#1D4ED8', '#2563EB', '#3730A3'],
    green: ['#10B981', '#059669', '#047857', '#065F46', '#064E3B'],
    purple: ['#8B5CF6', '#7C3AED', '#6D28D9', '#5B21B6', '#4C1D95'],
    mixed: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4', '#84CC16', '#F97316']
};

function switchChartType(newType) {
    if (!chartData) return;
    
    currentChartType = newType;
    
    // Update button states
    document.querySelectorAll('.chart-type-btn').forEach(btn => {
        btn.classList.remove('bg-green-600', 'text-white');
        btn.classList.add('text-gray-600', 'hover:bg-gray-200');
    });
    
    document.getElementById('btn-' + newType).classList.remove('text-gray-600', 'hover:bg-gray-200');
    document.getElementById('btn-' + newType).classList.add('bg-green-600', 'text-white');
    
    initChart();
}

function initChart() {
    if (!chartData) {
        return;
    }
    
    // Check if there's actual data
    if (!chartData.labels || chartData.labels.length === 0) {
        const canvas = document.getElementById('analyticsChart');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            ctx.font = '16px Arial';
            ctx.fillStyle = '#6B7280';
            ctx.textAlign = 'center';
            ctx.fillText('No data available for the selected date range', canvas.width / 2, canvas.height / 2);
        }
        return;
    }
    
    const canvas = document.getElementById('analyticsChart');
    if (!canvas) {
        return;
    }
    
    const ctx = canvas.getContext('2d');
    
    if (analyticsChart) {
        analyticsChart.destroy();
    }

    const config = {
        type: currentChartType,
        data: {
            labels: chartData.labels,
            datasets: [{
                label: chartData.label,
                data: chartData.data,
                backgroundColor: currentChartType === 'line' ? 'rgba(59, 130, 246, 0.1)' : colorSchemes.mixed,
                borderColor: currentChartType === 'line' ? '#3B82F6' : colorSchemes.mixed,
                borderWidth: 2,
                fill: currentChartType === 'line',
                tension: 0.4
            }]
        },
        plugins: [ChartDataLabels],
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: '#3B82F6',
                    borderWidth: 1,
                    callbacks: {
                        afterLabel: function(context) {
                            if (currentChartType === 'pie' || currentChartType === 'doughnut') {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.raw / total) * 100).toFixed(1);
                                return `Percentage: ${percentage}%`;
                            }
                            return '';
                        }
                    }
                },
                datalabels: {
                    display: function(context) {
                        return currentChartType === 'pie' || currentChartType === 'doughnut';
                    },
                    color: 'white',
                    font: { weight: 'bold', size: 14 },
                    formatter: function(value, context) {
                        if (currentChartType !== 'pie' && currentChartType !== 'doughnut') return null;
                        
                        const dataset = context.dataset;
                        const total = dataset.data.reduce((sum, val) => sum + Number(val), 0);
                        const percentage = total > 0 ? ((Number(value) / total) * 100).toFixed(1) : 0;
                        
                        return parseFloat(percentage) === 0 ? null : percentage + '%';
                    },
                    anchor: 'center',
                    align: 'center'
                }
            },
            layout: currentChartType === 'pie' || currentChartType === 'doughnut' ? {
                padding: {
                    top: 20,
                    bottom: 20,
                    left: 20,
                    right: 20
                }
            } : {},
            scales: currentChartType === 'line' || currentChartType === 'bar' ? {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.1)' }
                },
                x: {
                    grid: { display: false }
                }
            } : {},
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    };

    analyticsChart = new Chart(ctx, config);
}

function downloadChart() {
    if (analyticsChart) {
        const link = document.createElement('a');
        link.download = 'analytics-chart.png';
        link.href = analyticsChart.toBase64Image();
        link.click();
    }
}

function toggleFullscreen() {
    const chartContainer = document.querySelector('.chart-container').parentElement;
    if (document.fullscreenElement) {
        document.exitFullscreen();
    } else {
        chartContainer.requestFullscreen();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart !== 'undefined' && typeof ChartDataLabels !== 'undefined') {
        Chart.register(ChartDataLabels);
    }
    
    setTimeout(function() {
        if (chartData) {
            switchChartType('line');
        }
    }, 100);
});
</script>
@endpush
@endsection

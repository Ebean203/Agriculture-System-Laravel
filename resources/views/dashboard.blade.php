@extends('layouts.agriculture')

@section('content')
<div class="max-w-7xl mx-auto py-2">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-agri-green to-agri-dark rounded-xl card-shadow p-6 mb-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-10 rounded-full -ml-12 -mb-12"></div>
        <div class="relative">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="heading-xl mb-2">Welcome to Lagonglong FARMS</h2>
                    <p class="text-base text-green-100 mb-3">Empowering the agriculture office of Lagonglong to better serve its farmers through comprehensive agricultural services and resources</p>
                    <div class="flex items-center text-green-200">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <span>{{ date('l, F j, Y') }}</span>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <i class="fas fa-seedling text-6xl text-white opacity-20"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Main Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
        <!-- Farmers -->
        <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-center items-center h-40">
            <div class="flex items-center mb-2">
                <i class="fas fa-user-friends text-agri-green text-2xl mr-2"></i>
                <span class="font-semibold text-gray-700">FARMERS</span>
            </div>
            <div class="text-2xl font-bold text-agri-green">{{ number_format($total_farmers) }}</div>
        </div>
        <!-- RSBSA -->
        <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-center items-center h-40">
            <div class="flex items-center mb-2">
                <i class="fas fa-shield-alt text-blue-600 text-2xl mr-2"></i>
                <span class="font-semibold text-gray-700">RSBSA</span>
            </div>
            <div class="text-2xl font-bold text-blue-600">{{ number_format($rsbsa_registered) }}</div>
        </div>
        <!-- NCFRS -->
        <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-center items-center h-40">
            <div class="flex items-center mb-2">
                <i class="fas fa-file-alt text-purple-600 text-2xl mr-2"></i>
                <span class="font-semibold text-gray-700">NCFRS</span>
            </div>
            <div class="text-2xl font-bold text-purple-600">{{ number_format($ncfrs_registered) }}</div>
        </div>
        <!-- Weather (wider card) -->
        <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-center items-center xl:row-span-2 xl:h-[352px] h-[352px] xl:col-span-1 xl:w-full" style="min-width:0;">
            <div class="flex items-center mb-2">
                <i class="fas fa-cloud-sun text-gray-500 text-2xl mr-2"></i>
                <span class="font-semibold text-gray-700">Weather Today <span class="text-xs text-gray-400">Lagonglong</span></span>
            </div>
            <div class="text-3xl font-bold text-gray-700">31°C</div>
            <div class="text-sm text-gray-500 mb-2">Partly Cloudy</div>
            <div class="flex space-x-2 text-xs text-gray-400">
                <div>Mon<br>32°/25°</div>
                <div>Tue<br>30°/24°</div>
                <div>Wed<br>28°/23°</div>
                <div>Thu<br>31°/25°</div>
                <div>Fri<br>33°/26°</div>
            </div>
        </div>
        <!-- Commodities -->
        <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-center items-center h-40">
            <div class="flex items-center mb-2">
                <i class="fas fa-box-open text-orange-500 text-2xl mr-2"></i>
                <span class="font-semibold text-gray-700">COMMODITIES</span>
            </div>
            <div class="text-2xl font-bold text-orange-500">{{ number_format($total_commodities) }}</div>
        </div>
        <!-- Registered Boats -->
        <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-center items-center h-40">
            <div class="flex items-center mb-2">
                <i class="fas fa-ship text-yellow-600 text-2xl mr-2"></i>
                <span class="font-semibold text-gray-700">REGISTERED BOATS</span>
            </div>
            <div class="text-2xl font-bold text-yellow-600">{{ number_format($total_boats) }}</div>
        </div>
        <!-- Inventory -->
        <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-center items-center h-40">
            <div class="flex items-center mb-2">
                <i class="fas fa-cube text-agri-green text-2xl mr-2"></i>
                <span class="font-semibold text-gray-700">INVENTORY</span>
            </div>
            <div class="text-2xl font-bold text-agri-green">{{ number_format($total_inventory) }}</div>
            <div class="text-xs text-gray-400">items in stock</div>
        </div>
    </div>

    <!-- Yield Monitoring and Quick Actions -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
        <!-- Yield Monitoring Chart -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-xl card-shadow p-6 h-full">
                <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center">
                    <i class="fas fa-chart-line text-agri-green mr-2"></i>
                    <span>Yield Monitoring</span>
                </h3>
                <canvas id="yieldChart" height="120"></canvas>
            </div>
        </div>
        <!-- Quick Actions -->
        <div>
            <div class="bg-white rounded-xl card-shadow p-6 h-full">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-bolt text-yellow-500 mr-2"></i>Quick Actions
                </h3>
                <div class="space-y-4">
                    <button data-bs-toggle="modal" data-bs-target="#farmerModal" class="w-full flex items-center p-4 bg-agri-green text-white rounded-lg hover:bg-green-700 transition-all duration-300">
                        <i class="fas fa-user-plus text-white text-2xl mr-3"></i>
                        <span class="font-medium text-base leading-tight">Add New Farmer</span>
                    </button>
                    <button onclick="openDistributeModal()" class="w-full flex items-center p-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300">
                        <i class="fas fa-truck text-white text-2xl mr-3"></i>
                        <span class="font-medium text-base leading-tight">Distribute Inputs</span>
                    </button>
                    <button onclick="openYieldModal()" class="w-full flex items-center p-4 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-all duration-300">
                        <i class="fas fa-clipboard-list text-white text-2xl mr-3"></i>
                        <span class="font-medium text-base leading-tight">Record Yield</span>
                    </button>
                    <button onclick="navigateTo('{{ route('activities.all') }}')" class="w-full flex items-center p-4 bg-yellow-400 text-white rounded-lg hover:bg-yellow-500 transition-all duration-300">
                        <i class="fas fa-list-alt text-white text-2xl mr-3"></i>
                        <span class="font-medium text-base leading-tight">All Activities</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities and Farmers by Program -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
        <!-- Recent Activities -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-xl card-shadow p-6 h-full">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-history text-agri-green mr-2"></i>Recent Activities
                </h3>
                <ul class="space-y-2">
                    @if($recent_activities->count() > 0)
                        @foreach($recent_activities as $activity)
                            <li class="flex items-center justify-between py-2 border-b border-gray-100">
                                <span class="flex items-center">
                                    @php
                                        $icon = 'fas fa-info-circle';
                                        $icon_color = 'text-agri-green';
                                        switch(strtolower($activity->action_type ?? '')) {
                                            case 'farmer':
                                                $icon = 'fas fa-user-plus';
                                                $icon_color = 'text-green-600';
                                                break;
                                            case 'inventory':
                                                $icon = 'fas fa-boxes';
                                                $icon_color = 'text-blue-600';
                                                break;
                                            case 'distribution':
                                                $icon = 'fas fa-truck';
                                                $icon_color = 'text-orange-600';
                                                break;
                                            case 'yield':
                                                $icon = 'fas fa-seedling';
                                                $icon_color = 'text-green-500';
                                                break;
                                            case 'staff':
                                                $icon = 'fas fa-user-tie';
                                                $icon_color = 'text-purple-600';
                                                break;
                                            case 'commodity':
                                                $icon = 'fas fa-leaf';
                                                $icon_color = 'text-yellow-600';
                                                break;
                                            default:
                                                $icon = 'fas fa-check-circle';
                                                $icon_color = 'text-agri-green';
                                        }
                                    @endphp
                                    <i class="{{ $icon }} {{ $icon_color }} mr-2"></i>
                                    <span class="text-sm">
                                        {{ $activity->action }}
                                        @if($activity->staff)
                                            <span class="text-gray-600 text-xs">
                                                by {{ $activity->staff->first_name }} {{ $activity->staff->last_name }}
                                            </span>
                                        @endif
                                    </span>
                                </span>
                                <span class="text-xs text-gray-400">
                                    {{ date('M j, Y', strtotime($activity->timestamp)) }}
                                </span>
                            </li>
                        @endforeach
                    @else
                        <li class="flex items-center justify-center py-8 text-gray-500">
                            <div class="text-center">
                                <i class="fas fa-history text-2xl mb-2"></i>
                                <p class="text-sm">No recent activities found</p>
                                <p class="text-xs">Activities will appear here as they are logged</p>
                            </div>
                        </li>
                    @endif
                </ul>
                @if($recent_activities->count() > 0)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('activities') }}" class="text-agri-green hover:text-agri-dark font-medium text-sm flex items-center justify-center transition-colors">
                            <span>View All Activities</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
        <!-- Farmers by Program -->
        <div>
            <div class="bg-white rounded-xl card-shadow p-6 h-full flex flex-col items-center justify-center">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-users text-agri-green mr-2"></i>Farmers by Program
                </h3>
                <canvas id="farmersPieChart" width="180" height="180"></canvas>
                <div class="flex justify-center mt-4 space-x-4 flex-wrap">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-blue-600 mr-1"></span>
                        <span class="text-xs">RSBSA</span>
                        <span class="ml-1 font-bold text-blue-600 text-sm">{{ number_format($rsbsa_registered) }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-purple-600 mr-1"></span>
                        <span class="text-xs">NCFRS</span>
                        <span class="ml-1 font-bold text-purple-600 text-sm">{{ number_format($ncfrs_registered) }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-cyan-600 mr-1"></span>
                        <span class="text-xs">FISH-R</span>
                        <span class="ml-1 font-bold text-cyan-600 text-sm">{{ number_format($fisherfolk_registered) }}</span>
                    </div>
                </div>
                <div class="w-full mt-6">
                    <h4 class="text-md font-semibold text-gray-700 mb-2 text-center">Yield Records per Barangay</h4>
                    <ul class="text-sm text-gray-600 divide-y divide-gray-100" style="max-height: 200px; overflow-y: auto;">
                        @if($yield_records_per_barangay->count() > 0)
                            @foreach($yield_records_per_barangay as $row)
                                <li class="flex justify-between py-1 px-2">
                                    <span>{{ $row->barangay_name }}</span>
                                    <span class="font-bold text-agri-green">{{ number_format($row->record_count) }}</span>
                                </li>
                            @endforeach
                        @else
                            <li class="py-1 px-2 text-gray-400 text-center">No yield records found.</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('additional-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function navigateTo(url) {
        window.location.href = url;
    }

    // Yield Monitoring Line Chart
    document.addEventListener('DOMContentLoaded', function() {
        fetch('{{ route("api.yield-data") }}')
            .then(res => res.json())
            .then(data => {
                updateYieldChart(data.labels, data.data);
            })
            .catch(error => {
                console.error('Error loading yield data:', error);
                updateYieldChart([], []);
            });
    });

    let yieldChartInstance = null;
    function updateYieldChart(labels, chartData) {
        const ctx = document.getElementById('yieldChart').getContext('2d');
        if (yieldChartInstance) {
            yieldChartInstance.destroy();
        }
        yieldChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Yearly Yield per Barangay',
                    data: chartData,
                    backgroundColor: 'rgba(16,185,129,0.15)',
                    borderColor: '#10b981',
                    borderWidth: 2,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#10b981',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }

    // Farmers by Program Pie Chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('farmersPieChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['RSBSA', 'NCFRS', 'FISH-R'],
                datasets: [{
                    data: [
                        {{ $rsbsa_registered ?? 0 }},
                        {{ $ncfrs_registered ?? 0 }},
                        {{ $fisherfolk_registered ?? 0 }}
                    ],
                    backgroundColor: ['#3B82F6', '#8B5CF6', '#06B6D4'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });

    // Animate statistics
    document.addEventListener('DOMContentLoaded', function() {
        const stats = document.querySelectorAll('.text-2xl.font-bold');
        stats.forEach(stat => {
            const text = stat.textContent.replace(/,/g, '');
            const finalValue = parseInt(text);
            if (isNaN(finalValue)) return;
            let currentValue = 0;
            const increment = finalValue / 30;
            const timer = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    currentValue = finalValue;
                    clearInterval(timer);
                }
                stat.textContent = Math.floor(currentValue).toLocaleString();
            }, 50);
        });
    });

    // Modal functions for quick actions
    function openFarmerModal() {
        const modal = document.getElementById('farmerModal');
        if (modal) {
            modal.style.display = 'flex';
            modal.classList.remove('hidden');
        }
    }

    function openDistributeModal() {
        const modal = document.getElementById('distributeModal');
        if (modal) {
            modal.style.display = 'flex';
            modal.classList.remove('hidden');
        }
    }

    function openYieldModal() {
        const modal = document.getElementById('yieldModal');
        if (modal) {
            modal.style.display = 'flex';
            modal.classList.remove('hidden');
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
            modal.classList.add('hidden');
        }
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modals = ['farmerModal', 'distributeModal', 'yieldModal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (event.target === modal) {
                closeModal(modalId);
            }
        });
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal('farmerModal');
            closeModal('distributeModal');
            closeModal('yieldModal');
        }
    });
</script>
@endpush

<!-- Include Modals -->
@include('modals.farmer-modal')
@include('modals.distribute-modal')
@include('modals.yield-modal')

@endsection

@extends('layouts.admin')

@section('header')
    Dashboard
@endsection

@section('content')
    @if(!auth()->guard('admin')->user()->setup_completed)
        @include('admin.setup-wizard')
    @else
        <div class="space-y-6">
            <!-- Top Bar with Time and Dark Mode Toggle -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                    Good {{ now()->format('H') < 12 ? 'Morning' : (now()->format('H') < 17 ? 'Afternoon' : 'Evening') }},
                    {{ auth()->guard('admin')->user()->name }}
                </h2>
                <div class="flex items-center space-x-4">
                    <!-- Time Display -->
                    <div id="time" class="text-lg font-semibold text-gray-600 dark:text-gray-400"></div>
                    <!-- Dark Mode Toggle -->
                    <button id="darkModeToggle" class="text-gray-600 dark:text-gray-400">
                        <svg id="darkModeIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-8.66h-1M4.34 12H3m15.66 4.66l-.7-.7M6.34 6.34l-.7-.7m12.02 12.02l-.7-.7M6.34 17.66l-.7-.7M12 5a7 7 0 100 14 7 7 0 000-14z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Analytics Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- KYC Applications by Location -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">KYC Applications by Location</h3>
                    <div id="locationChart"></div>
                </div>

                <!-- KYC Applications by Age -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">KYC Applications by Age</h3>
                    <div id="ageChart"></div>
                </div>

                <!-- KYC Applications by Gender -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">KYC Applications by Gender</h3>
                    <div id="genderChart"></div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Recent Activities</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @foreach($recentActivities as $activity)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $activity->created_at->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $activity->customer_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $activity->service }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($activity->status == 'completed') bg-green-100 text-green-800
                                            @elseif($activity->status == 'in_progress') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            // Time Display
            function updateTime() {
                const timeElement = document.getElementById('time');
                const now = new Date();
                timeElement.textContent = now.toLocaleTimeString();
            }
            setInterval(updateTime, 1000);
            updateTime();

            // Dark Mode Toggle
            const darkModeToggle = document.getElementById('darkModeToggle');
            const darkModeIcon = document.getElementById('darkModeIcon');
            darkModeToggle.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark');
                darkModeIcon.classList.toggle('moon');
            });

            // Charts
            const locationChart = new ApexCharts(document.querySelector("#locationChart"), {
                series: [{
                    data: [300, 300, 200, 409]
                }],
                chart: {
                    type: 'pie',
                    height: 350
                },
                labels: ['freetown', 'makenu', 'kabala', 'pujun'],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            });
            locationChart.render();

            const ageChart = new ApexCharts(document.querySelector("#ageChart"), {
                series: [{
                    data: [200, 300, 250, 150, 100]
                }],
                chart: {
                    type: 'bar',
                    height: 350
                },
                xaxis: {
                    categories: ['18-25', '26-35', '36-45', '46-55', '56+']
                }
            });
            ageChart.render();

            const genderChart = new ApexCharts(document.querySelector("#genderChart"), {
                series: [420, 540],
                chart: {
                    type: 'pie',
                    height: 350
                },
                labels: ['Male', 'Female'],
                colors: ['#4299E1', '#ED64A6']
            });
            genderChart.render();
        </script>
        @endpush
    @endif
@endsection


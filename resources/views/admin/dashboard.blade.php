@extends('layouts.admin')

@section('header')
    <span class="text-gray-900 dark:text-white">Dashboard</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Greeting -->
    <div class="text-gray-900 dark:text-white">
        Good {{ now()->format('A') }}, {{ auth()->guard('admin')->user()->name }}
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- KYC Applications by Location -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">KYC Applications by Location</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="locationChart"></canvas>
            </div>
            <div class="mt-4 flex flex-wrap gap-2">
                <span class="inline-flex items-center text-sm text-gray-600 dark:text-gray-300">
                    <span class="w-3 h-3 inline-block mr-1 rounded-full bg-indigo-400"></span>
                    Freetown
                </span>
                <span class="inline-flex items-center text-sm text-gray-600 dark:text-gray-300">
                    <span class="w-3 h-3 inline-block mr-1 rounded-full bg-blue-400"></span>
                    Makeni
                </span>
                <span class="inline-flex items-center text-sm text-gray-600 dark:text-gray-300">
                    <span class="w-3 h-3 inline-block mr-1 rounded-full bg-purple-400"></span>
                    Kabala
                </span>
                <span class="inline-flex items-center text-sm text-gray-600 dark:text-gray-300">
                    <span class="w-3 h-3 inline-block mr-1 rounded-full bg-pink-400"></span>
                    Pujun
                </span>
            </div>
        </div>

        <!-- KYC Applications by Age -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">KYC Applications by Age</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="ageChart"></canvas>
            </div>
        </div>

        <!-- KYC Applications by Gender -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">KYC Applications by Gender</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="genderChart"></canvas>
            </div>
            <div class="mt-4 flex justify-center gap-4">
                <span class="inline-flex items-center text-sm text-gray-600 dark:text-gray-300">
                    <span class="w-3 h-3 inline-block mr-1 rounded-full bg-yellow-400"></span>
                    Male
                </span>
                <span class="inline-flex items-center text-sm text-gray-600 dark:text-gray-300">
                    <span class="w-3 h-3 inline-block mr-1 rounded-full bg-yellow-200"></span>
                    Female
                </span>
            </div>
        </div>
    </div>

    <!-- Recent Applications Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Recent KYC Applications</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($recentApplications as $application)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $application->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $application->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($application->status === 'Approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($application->status === 'Rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @elseif($application->status === 'In Review') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                    {{ $application->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $application->progress }}%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">View</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get current theme
    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? '#D1D5DB' : '#374151';

    // Location Chart
    new Chart(document.getElementById('locationChart'), {
        type: 'pie',
        data: {
            labels: ['Freetown', 'Makeni', 'Kabala', 'Pujun'],
            datasets: [{
                data: [300, 200, 409, 300],
                backgroundColor: ['#818cf8', '#60a5fa', '#a78bfa', '#f472b6']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Age Chart
    new Chart(document.getElementById('ageChart'), {
        type: 'bar',
        data: {
            labels: ['18-25', '26-35', '36-45', '46-55', '56+'],
            datasets: [{
                data: [200, 300, 250, 100, 100],
                backgroundColor: '#4ade80'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 300,
                    ticks: {
                        color: textColor
                    },
                    grid: {
                        color: isDarkMode ? '#374151' : '#E5E7EB'
                    }
                },
                x: {
                    ticks: {
                        color: textColor
                    },
                    grid: {
                        color: isDarkMode ? '#374151' : '#E5E7EB'
                    }
                }
            }
        }
    });

    // Gender Chart
    new Chart(document.getElementById('genderChart'), {
        type: 'pie',
        data: {
            labels: ['Male', 'Female'],
            datasets: [{
                data: [540, 420],
                backgroundColor: ['#fbbf24', '#fde68a']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush
@endsection


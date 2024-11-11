<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Models\Customer;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = auth()->guard('admin')->user();

        // If setup is not completed, return view with just the wizard
        if (!$admin->setup_completed) {
            return view('admin.dashboard');
        }

        // Otherwise, get all the dashboard data
        $totalUsers = Customer::whereDate('created_at', Carbon::today())->count();
        $activeQueues = Queue::where('status', 'waiting')->count();
        $averageWaitTime = Queue::where('status', 'completed')
            ->whereDate('created_at', Carbon::today())
            ->avg('wait_time') ?? 0;
        $completedServices = Queue::where('status', 'completed')
            ->whereDate('created_at', Carbon::today())
            ->count();

        // Sample data for demographics chart
        $demographicsData = [150, 250, 180, 120, 90]; // Replace with actual data

        // Sample data for location chart
        $locationData = [30, 25, 20, 15, 10]; // Replace with actual data
        $locationLabels = ['Western Area', 'Eastern Area', 'Northern Area', 'Southern Area', 'Other'];

        $recentActivities = Queue::with('customer')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($queue) {
                return (object)[
                    'created_at' => $queue->created_at,
                    'customer_name' => $queue->customer->name,
                    'service' => $queue->service_type,
                    'status' => $queue->status
                ];
            });

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeQueues',
            'averageWaitTime',
            'completedServices',
            'demographicsData',
            'locationData',
            'locationLabels',
            'recentActivities'
        ));
    }
}

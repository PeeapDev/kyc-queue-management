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

        if (!$admin->setup_completed) {
            return view('admin.dashboard');
        }

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

        return view('admin.dashboard', compact('recentActivities'));
    }
}

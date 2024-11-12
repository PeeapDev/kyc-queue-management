<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycApplication;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Sample data - replace with actual data from your database
        $recentApplications = [
            (object)[
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'status' => 'Pending',
                'progress' => 25
            ],
            (object)[
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'status' => 'Approved',
                'progress' => 100
            ],
            // Add more sample data as needed
        ];

        return view('admin.dashboard', compact('recentApplications'));
    }
}

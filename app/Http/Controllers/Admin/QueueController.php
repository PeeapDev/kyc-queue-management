<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function index()
    {
        $queueItems = Queue::orderBy('created_at', 'desc')->get();
        return view('admin.queue.index', compact('queueItems'));
    }

    public function create()
    {
        return view('admin.queue.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
        ]);

        $queue = Queue::create([
            'queue_number' => Queue::generateQueueNumber(),
            'name' => $validated['name'],
            'phone_number' => $validated['phone_number'],
            'status' => 'waiting'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Added to queue successfully',
            'queue' => $queue
        ]);
    }

    public function updateStatus(Request $request, Queue $queue)
    {
        $validated = $request->validate([
            'status' => 'required|in:waiting,serving,completed,cancelled',
            'counter_id' => 'nullable|integer'
        ]);

        $queue->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Queue status updated successfully',
            'queue' => $queue
        ]);
    }

    public function analytics()
    {
        $analytics = [
            'total_today' => Queue::whereDate('created_at', today())->count(),
            'waiting' => Queue::where('status', 'waiting')->count(),
            'serving' => Queue::where('status', 'serving')->count(),
            'completed' => Queue::where('status', 'completed')->count(),
            'cancelled' => Queue::where('status', 'cancelled')->count(),
        ];

        return view('admin.queue.analytics', compact('analytics'));
    }

    public function tracking()
    {
        $activeQueues = Queue::whereIn('status', ['waiting', 'serving'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.queue.tracking', compact('activeQueues'));
    }
}

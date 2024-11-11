<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function index()
    {
        $queues = Queue::with(['customer', 'counter'])->latest()->paginate(10);
        return view('admin.queues.index', compact('queues'));
    }

    public function create()
    {
        return view('admin.queues.create');
    }

    public function store(Request $request)
    {
        // Implementation coming soon
    }

    public function show(Queue $queue)
    {
        return view('admin.queues.show', compact('queue'));
    }

    public function edit(Queue $queue)
    {
        return view('admin.queues.edit', compact('queue'));
    }

    public function update(Request $request, Queue $queue)
    {
        // Implementation coming soon
    }

    public function destroy(Queue $queue)
    {
        // Implementation coming soon
    }
}

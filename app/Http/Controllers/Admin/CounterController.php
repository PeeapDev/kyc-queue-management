<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    public function index()
    {
        $counters = Counter::latest()->get();
        return view('admin.counters.index', compact('counters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'show_on_display' => 'sometimes|boolean',
        ]);

        $counter = Counter::create([
            'name' => $validated['name'],
            'is_active' => true,
            'show_on_display' => $request->show_on_display ?? false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Counter created successfully.',
            'counter' => $counter
        ]);
    }

    public function edit(Counter $counter)
    {
        return view('admin.counters.edit', compact('counter'));
    }

    public function update(Request $request, Counter $counter)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'show_on_display' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        $counter->update([
            'name' => $validated['name'],
            'show_on_display' => $request->has('show_on_display'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.counters.index')
            ->with('success', 'Counter updated successfully.');
    }

    public function destroy(Counter $counter)
    {
        $counter->delete();
        return response()->json(['success' => true]);
    }
}

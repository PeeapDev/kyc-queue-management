<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Counter;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::with(['counter', 'categories'])->latest()->paginate(10);
        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        $counters = Counter::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();
        return view('admin.staff.create', compact('counters', 'categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'contact' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255|unique:staff,email',
                'username' => 'required|string|max:255|unique:staff',
                'password' => 'required|string|min:8|confirmed',
                'address' => 'nullable|string',
                'unique_id' => 'required|string|size:6|unique:staff',
                'role' => 'required|string|in:counter_staff,supervisor,manager',
                'counter_id' => 'nullable|exists:counters,id',
                'categories' => 'required|array',
                'categories.*' => 'exists:categories,id',
            ]);

            DB::beginTransaction();

            $staff = Staff::create([
                'name' => $validated['name'],
                'contact' => $validated['contact'],
                'email' => $validated['email'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'address' => $validated['address'],
                'unique_id' => $validated['unique_id'] ?? str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT),
                'role' => $validated['role'],
                'counter_id' => $validated['counter_id'],
                'show_next_button' => $request->has('show_next_button'),
                'desktop_notifications' => $request->has('desktop_notifications'),
            ]);

            if ($request->has('categories')) {
                $staff->categories()->attach($request->categories);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Staff member created successfully.',
                'redirect' => route('admin.staff.index')
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Staff creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating staff member: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Staff $staff)
    {
        $counters = Counter::where('is_active', true)->get();
        return view('admin.staff.edit', compact('staff', 'counters'));
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $staff->id,
            'phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:255',
            'counter_id' => 'nullable|exists:counters,id',
            'is_active' => 'boolean',
        ]);

        $staff->update($validated);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member updated successfully.');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }

    public function toggleStatus(Staff $staff)
    {
        $staff->update([
            'is_active' => !$staff->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Staff status updated successfully.',
            'is_active' => $staff->is_active
        ]);
    }
}

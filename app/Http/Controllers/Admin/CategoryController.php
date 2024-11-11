<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'name_other' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'acronym' => 'required|string|max:10',
                'level' => 'required|string|in:Level 1,Level 2,Level 3',
                'priority' => 'required|integer|min:1',
            ]);

            // Create category with explicit boolean values and defaults
            $category = Category::create([
                'name' => $validated['name'],
                'name_other' => $validated['name_other'] ?? null,
                'description' => $validated['description'] ?? null,
                'acronym' => $validated['acronym'],
                'level' => $validated['level'],
                'priority' => $validated['priority'],
                'display_on_transfer' => $request->boolean('display_on_transfer'),
                'display_on_ticket' => $request->boolean('display_on_ticket'),
                'display_on_backend' => $request->boolean('display_on_backend'),
                'is_active' => true,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully.',
                'category' => $category
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Category creation error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error creating category: ' . $e->getMessage()
            ], 422);
        }
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_other' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'acronym' => 'required|string|max:10',
            'level' => 'required|string|in:Level 1,Level 2,Level 3',
            'priority' => 'required|integer|min:1',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['success' => true]);
    }
}

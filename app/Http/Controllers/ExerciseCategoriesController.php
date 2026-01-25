<?php

namespace App\Http\Controllers;

use App\Models\ExerciseCategories;
use Illuminate\Http\Request;

class ExerciseCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ExerciseCategories::with('exercises')->get();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
        ]);

        $category = ExerciseCategories::create($validated);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ExerciseCategories $exerciseCategory)
    {
        return response()->json($exerciseCategory->load('exercises'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExerciseCategories $exerciseCategory)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
        ]);

        $exerciseCategory->update($validated);

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $exerciseCategory
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExerciseCategories $exerciseCategory)
    {
        $exerciseCategory->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\ExerciseCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exercises = Exercise::with('category')->get();
        return response()->json($exercises);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'nullable|exists:exercise_categories,id',
            'category_name' => 'nullable|string|max:255',
            'category_description' => 'nullable|string',
            'category_icon' => 'nullable|string',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'muscle_group' => 'required|string|max:100',
            'equipment' => 'nullable|string|max:100',
            'video_url' => 'nullable|url',
            'image' => 'nullable|string',
        ]);

        // Handle category creation or retrieval
        if (!isset($validated['category_id']) || empty($validated['category_id'])) {
            if (isset($validated['category_name']) && !empty($validated['category_name'])) {
                // Create new category
                $category = ExerciseCategories::create([
                    'name' => $validated['category_name'],
                    'description' => $validated['category_description'] ?? null,
                    'icon' => $validated['category_icon'] ?? null,
                ]);
                $validated['category_id'] = $category->id;
            } else {
                return response()->json([
                    'message' => 'Either category_id or category_name must be provided'
                ], 400);
            }
        }

        // Remove category fields from validated data
        unset($validated['category_name'], $validated['category_description'], $validated['category_icon']);

        $validated['created_by'] = Auth::id();

        $exercise = Exercise::create($validated);

        return response()->json([
            'message' => 'Exercise created successfully',
            'exercise' => $exercise->load('category')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Exercise $exercise)
    {
        return response()->json($exercise->load('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exercise $exercise)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'category_id' => 'nullable|exists:exercise_categories,id',
            'category_name' => 'nullable|string|max:255',
            'category_description' => 'nullable|string',
            'category_icon' => 'nullable|string',
            'difficulty' => 'sometimes|in:beginner,intermediate,advanced',
            'muscle_group' => 'sometimes|string|max:100',
            'equipment' => 'nullable|string|max:100',
            'video_url' => 'nullable|url',
            'image' => 'nullable|string',
        ]);

        // Handle category creation or retrieval
        if (isset($validated['category_name']) && !empty($validated['category_name'])) {
            $category = ExerciseCategories::create([
                'name' => $validated['category_name'],
                'description' => $validated['category_description'] ?? null,
                'icon' => $validated['category_icon'] ?? null,
            ]);
            $validated['category_id'] = $category->id;
        }

        // Remove category fields from validated data
        unset($validated['category_name'], $validated['category_description'], $validated['category_icon']);

        $exercise->update($validated);

        return response()->json([
            'message' => 'Exercise updated successfully',
            'exercise' => $exercise->load('category')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exercise $exercise)
    {
        $exercise->delete();

          return response()->json([
            'message' => 'Exercise deleted successfully'
        ]);
    }

    /**
     * Get exercises by category
     */
    public function showByCategory($categoryId)
    {
        $exercises = Exercise::where('category_id', $categoryId)
            ->with('category')
            ->get();

        return response()->json($exercises);
    }
}

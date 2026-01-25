<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlans;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SubscriptionPlansController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $plans = SubscriptionPlans::where('is_active', true)->get();
        return response()->json($plans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_months' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|json',
            'is_active' => 'boolean',
        ]);

        $plan = SubscriptionPlans::create($request->all());

        return response()->json($plan, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SubscriptionPlans $subscriptionPlan): JsonResponse
    {
        return response()->json($subscriptionPlan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubscriptionPlans $subscriptionPlan): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_months' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|json',
            'is_active' => 'boolean',
        ]);

        $subscriptionPlan->update($request->all());

        return response()->json($subscriptionPlan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubscriptionPlans $subscriptionPlan): JsonResponse
    {
        $subscriptionPlan->delete();

        return response()->json(['message' => 'Subscription plan deleted successfully']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlans;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SubscriptionPlansController extends Controller
{
    public function index(): JsonResponse
    {
        $plans = SubscriptionPlans::where('is_active', true)->get();
        return response()->json(['success' => true, 'data' => $plans]);
    }

    public function show($id): JsonResponse
    {
        $plan = SubscriptionPlans::findOrFail($id);
        return response()->json(['success' => true, 'data' => $plan]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subscription_plans,name',
            'description' => 'required|string',
            'duration_months' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $plan = SubscriptionPlans::create($validated);
        return response()->json(['success' => true, 'message' => 'Plan créé avec succès', 'data' => $plan],);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $plan = SubscriptionPlans::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:subscription_plans,name,' . $id,
            'description' => 'sometimes|required|string',
            'duration_months' => 'sometimes|required|integer|min:1',
            'price' => 'sometimes|required|numeric|min:0',
            'features' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $plan->update($validated);
        return response()->json(['success' => true, 'message' => 'Plan modifié avec succès', 'data' => $plan]);
    }

    public function destroy($id): JsonResponse
    {
        $plan = SubscriptionPlans::findOrFail($id);
        $plan->delete();
        return response()->json(['success' => true, 'message' => 'Plan supprimé avec succès']);
    }

}


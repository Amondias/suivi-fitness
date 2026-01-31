<?php

namespace App\Http\Controllers;

use App\Models\Subscriptions;
use App\Models\SubscriptionPlans;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class SubscriptionsController extends Controller
{

    public function index(): JsonResponse
    {
        $subscriptions = Subscriptions::with('user', 'plan')->paginate(5);
        return response()->json($subscriptions);
    }


    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:subscription_plans,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,expired,cancelled,pending',
        ]);

        $subscription = Subscriptions::create($request->all());

        return response()->json($subscription->load('user', 'plan'),);
    }
  
    //afficher une un abonnement spécifique
    public function show(Subscriptions $subscription): JsonResponse
    {
        return response()->json($subscription->load('user', 'plan'));
    }


    public function update(Request $request, Subscriptions $subscription): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:subscription_plans,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,expired,cancelled,pending',
        ]);

        $subscription->update($request->all());

        return response()->json($subscription->load('user', 'plan'));
    }


    public function destroy(Subscriptions $subscription): JsonResponse
    {
        $subscription->delete();

        return response()->json(['message' => 'Subscription deleted successfully']);
    }

  
    public function mySubscription(Request $request): JsonResponse
    {
        $user = $request->user();
        $subscription = Subscriptions::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('plan')
            ->first();

        if (!$subscription) {
            return response()->json(['message' => 'No active subscription found'], 404);
        }

        return response()->json($subscription);
    }


    public function expiringSoon(): JsonResponse
    {
        $expiringSoon = Subscriptions::where('status', 'active')
            ->where('end_date', '<=', Carbon::now()->addDays(30))
            ->with('user', 'plan')
            ->orderBy('end_date')
            ->get();

        return response()->json($expiringSoon);
    }


    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $user = $request->user();
        $plan = SubscriptionPlans::find($request->plan_id);

 
        $existingSubscription = Subscriptions::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if ($existingSubscription) {
            return response()->json(['message' => 'User already has an active subscription'],);
        }

        $startDate = Carbon::now();
        $endDate = Carbon::now()->addMonths($plan->duration_months);

        $subscription = Subscriptions::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'pending',
        ]);

        return response()->json($subscription->load('plan'),);
    }
}

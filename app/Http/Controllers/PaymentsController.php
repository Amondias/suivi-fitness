<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use App\Models\User;
use App\Models\Subscriptions;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $payments = Payments::with('user', 'subscription')->paginate(10);
        return response()->json($payments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subscription_id' => 'required|exists:subscriptions,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,mobile_money,bank_transfer',
            'transaction_id' => 'nullable|string',
            'status' => 'required|in:pending,completed,failed,refunded',
            'paid_at' => 'nullable|date',
        ]);

        $payment = Payments::create($request->all());

        return response()->json($payment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payments $payment): JsonResponse
    {
        return response()->json($payment->load('user', 'subscription'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payments $payment): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subscription_id' => 'required|exists:subscriptions,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,mobile_money,bank_transfer',
            'transaction_id' => 'nullable|string',
            'status' => 'required|in:pending,completed,failed,refunded',
            'paid_at' => 'nullable|date',
        ]);

        $payment->update($request->all());

        return response()->json($payment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payments $payment): JsonResponse
    {
        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully']);
    }

    /**
     * Get payments for the authenticated user.
     */
    public function myPayments(Request $request): JsonResponse
    {
        $user = $request->user();
        $payments = Payments::where('user_id', $user->id)
            ->with('subscription')
            ->paginate(10);

        return response()->json($payments);
    }

    /**
     * Process a payment (simulate payment processing).
     */
    public function processPayment(Request $request): JsonResponse
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'payment_method' => 'required|in:cash,card,mobile_money,bank_transfer',
            'transaction_id' => 'nullable|string',
        ]);

        $user = $request->user();
        $subscription = Subscriptions::find($request->subscription_id);

        // Check if user already has an active subscription for this plan
        $existingSubscription = Subscriptions::where('user_id', $user->id)
            ->where('subscription_plan_id', $subscription->subscription_plan_id)
            ->where('status', 'active')
            ->first();

        if ($existingSubscription) {
            return response()->json(['message' => 'User already has an active subscription for this plan'], 400);
        }

        // Create payment
        $payment = Payments::create([
            'user_id' => $user->id,
            'subscription_id' => $request->subscription_id,
            'amount' => $subscription->plan->price,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'status' => 'completed', // Simulate successful payment
            'paid_at' => now(),
        ]);

        // Update subscription status
        $subscription->update(['status' => 'active']);

        return response()->json($payment, 201);
    }

    /**
     * Refund a payment.
     */
    public function refund(Request $request, Payments $payment): JsonResponse
    {
        if ($payment->status !== 'completed') {
            return response()->json(['message' => 'Only completed payments can be refunded'], 400);
        }

        $payment->update([
            'status' => 'refunded',
            'paid_at' => null,
        ]);

        // Update subscription status
        $payment->subscription->update(['status' => 'cancelled']);

        return response()->json($payment);
    }
}

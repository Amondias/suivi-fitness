<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use App\Models\Subscriptions;
use App\Models\SubscriptionPlans;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Get financial report.
     */
    public function financialReport(Request $request): JsonResponse
    {
        $startDate = $request->query('start_date') ? Carbon::parse($request->query('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->query('end_date') ? Carbon::parse($request->query('end_date')) : Carbon::now()->endOfMonth();

        // Total revenue
        $totalRevenue = Payments::where('status', 'completed')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('amount');

        // Revenue by payment method
        $revenueByMethod = Payments::where('status', 'completed')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        // Monthly revenue for the last 12 months
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenue = Payments::where('status', 'completed')
                ->whereYear('paid_at', $month->year)
                ->whereMonth('paid_at', $month->month)
                ->sum('amount');
            $monthlyRevenue[] = [
                'month' => $month->format('Y-m'),
                'revenue' => $revenue
            ];
        }

        // Pending payments
        $pendingPayments = Payments::where('status', 'pending')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // Failed payments count
        $failedPaymentsCount = Payments::where('status', 'failed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Refunded amount
        $refundedAmount = Payments::where('status', 'refunded')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->sum('amount');

        // Active subscriptions count
        $activeSubscriptions = Subscriptions::where('status', 'active')->count();

        // Revenue by subscription plan
        $revenueByPlan = Payments::where('status', 'completed')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->join('subscriptions', 'payments.subscription_id', '=', 'subscriptions.id')
            ->join('subscription_plans', 'subscriptions.plan_id', '=', 'subscription_plans.id')
            ->selectRaw('subscription_plans.name, SUM(payments.amount) as total')
            ->groupBy('subscription_plans.name')
            ->get();

        // Top paying users
        $topUsers = Payments::where('status', 'completed')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->join('users', 'payments.user_id', '=', 'users.id')
            ->selectRaw('users.name, users.email, SUM(payments.amount) as total_spent')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'period' => [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString()
            ],
            'summary' => [
                'total_revenue' => $totalRevenue,
                'pending_payments' => $pendingPayments,
                'failed_payments_count' => $failedPaymentsCount,
                'refunded_amount' => $refundedAmount,
                'active_subscriptions' => $activeSubscriptions
            ],
            'revenue_by_method' => $revenueByMethod,
            'revenue_by_plan' => $revenueByPlan,
            'monthly_revenue' => $monthlyRevenue,
            'top_users' => $topUsers
        ]);
    }

    /**
     * Get payment statistics.
     */
    public function paymentStats(): JsonResponse
    {
        $totalPayments = Payments::count();
        $completedPayments = Payments::where('status', 'completed')->count();
        $pendingPayments = Payments::where('status', 'pending')->count();
        $failedPayments = Payments::where('status', 'failed')->count();
        $refundedPayments = Payments::where('status', 'refunded')->count();

        $totalRevenue = Payments::where('status', 'completed')->sum('amount');
        $averagePayment = $completedPayments > 0 ? $totalRevenue / $completedPayments : 0;

        return response()->json([
            'payment_counts' => [
                'total' => $totalPayments,
                'completed' => $completedPayments,
                'pending' => $pendingPayments,
                'failed' => $failedPayments,
                'refunded' => $refundedPayments
            ],
            'financial_metrics' => [
                'total_revenue' => $totalRevenue,
                'average_payment' => round($averagePayment, 2)
            ]
        ]);
    }

    /**
     * Get subscription analytics.
     */
    public function subscriptionAnalytics(): JsonResponse
    {
        $totalSubscriptions = Subscriptions::count();
        $activeSubscriptions = Subscriptions::where('status', 'active')->count();
        $expiredSubscriptions = Subscriptions::where('status', 'expired')->count();
        $cancelledSubscriptions = Subscriptions::where('status', 'cancelled')->count();

        $plans = SubscriptionPlans::withCount(['subscriptions' => function ($query) {
            $query->where('status', 'active');
        }])->get();

        return response()->json([
            'subscription_counts' => [
                'total' => $totalSubscriptions,
                'active' => $activeSubscriptions,
                'expired' => $expiredSubscriptions,
                'cancelled' => $cancelledSubscriptions
            ],
            'plans_popularity' => $plans->map(function ($plan) {
                return [
                    'plan_name' => $plan->name,
                    'active_subscriptions' => $plan->subscriptions_count,
                    'price' => $plan->price
                ];
            })
        ]);
    }
}
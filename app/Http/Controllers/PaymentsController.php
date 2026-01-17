<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use App\Models\User;
use App\Models\Subscriptions;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payments::with('user', 'subscription')->paginate(10);
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $subscriptions = Subscriptions::all();
        return view('payments.create', compact('users', 'subscriptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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

        Payments::create($request->all());

        return redirect()->route('payments.index')->with('success', 'Paiement créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payments $payment)
    {
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payments $payment)
    {
        $users = User::all();
        $subscriptions = Subscriptions::all();
        return view('payments.edit', compact('payment', 'users', 'subscriptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payments $payment)
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

        return redirect()->route('payments.index')->with('success', 'Paiement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payments $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Paiement supprimé avec succès.');
    }

    /**
     * Rapport financier.
     */
    public function report()
    {
        $totalPayments = Payments::where('status', 'completed')->sum('amount');
        $pendingPayments = Payments::where('status', 'pending')->sum('amount');
        $failedPayments = Payments::where('status', 'failed')->count();
        $monthlyRevenue = Payments::where('status', 'completed')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        return view('payments.report', compact('totalPayments', 'pendingPayments', 'failedPayments', 'monthlyRevenue'));
    }
}

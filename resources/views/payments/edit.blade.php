@extends('layouts.app')

@section('title', 'Éditer le Paiement')

@section('content')
<h1>Éditer le Paiement</h1>
<form action="{{ route('payments.update', $payment) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="user_id">Utilisateur</label>
        <select name="user_id" id="user_id" required>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ $payment->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="subscription_id">Abonnement</label>
        <select name="subscription_id" id="subscription_id" required>
            @foreach($subscriptions as $subscription)
                <option value="{{ $subscription->id }}" {{ $payment->subscription_id == $subscription->id ? 'selected' : '' }}>{{ $subscription->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="amount">Montant</label>
        <input type="number" step="0.01" name="amount" id="amount" value="{{ $payment->amount }}" required>
    </div>
    <div class="form-group">
        <label for="payment_method">Méthode de Paiement</label>
        <select name="payment_method" id="payment_method" required>
            <option value="cash" {{ $payment->payment_method == 'cash' ? 'selected' : '' }}>Espèces</option>
            <option value="card" {{ $payment->payment_method == 'card' ? 'selected' : '' }}>Carte</option>
            <option value="mobile_money" {{ $payment->payment_method == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
            <option value="bank_transfer" {{ $payment->payment_method == 'bank_transfer' ? 'selected' : '' }}>Virement Bancaire</option>
        </select>
    </div>
    <div class="form-group">
        <label for="transaction_id">ID Transaction</label>
        <input type="text" name="transaction_id" id="transaction_id" value="{{ $payment->transaction_id }}">
    </div>
    <div class="form-group">
        <label for="status">Statut</label>
        <select name="status" id="status" required>
            <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>En Attente</option>
            <option value="completed" {{ $payment->status == 'completed' ? 'selected' : '' }}>Complété</option>
            <option value="failed" {{ $payment->status == 'failed' ? 'selected' : '' }}>Échoué</option>
            <option value="refunded" {{ $payment->status == 'refunded' ? 'selected' : '' }}>Remboursé</option>
        </select>
    </div>
    <div class="form-group">
        <label for="paid_at">Date de Paiement</label>
        <input type="datetime-local" name="paid_at" id="paid_at" value="{{ $payment->paid_at ? $payment->paid_at->format('Y-m-d\TH:i') : '' }}">
    </div>
    <button type="submit" class="btn">Mettre à Jour</button>
</form>
@endsection
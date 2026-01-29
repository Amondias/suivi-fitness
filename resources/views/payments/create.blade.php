@extends('layouts.app')

@section('title', 'Créer un Paiement')

@section('content')
<h1>Créer un Paiement</h1>
<form action="{{ route('payments.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="user_id">Utilisateur</label>
        <select name="user_id" id="user_id" required>
            <option value="">Sélectionner un utilisateur</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="subscription_id">Abonnement</label>
        <select name="subscription_id" id="subscription_id" required>
            <option value="">Sélectionner un abonnement</option>
            @foreach($subscriptions as $subscription)
                <option value="{{ $subscription->id }}">{{ $subscription->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="amount">Montant</label>
        <input type="number" step="0.01" name="amount" id="amount" required>
    </div>
    <div class="form-group">
        <label for="payment_method">Méthode de Paiement</label>
        <select name="payment_method" id="payment_method" required>
            <option value="cash">Espèces</option>
            <option value="card">Carte</option>
            <option value="mobile_money">Mobile Money</option>
            <option value="bank_transfer">Virement Bancaire</option>
        </select>
    </div>
    <div class="form-group">
        <label for="transaction_id">ID Transaction</label>
        <input type="text" name="transaction_id" id="transaction_id">
    </div>
    <div class="form-group">
        <label for="status">Statut</label>
        <select name="status" id="status" required>
            <option value="pending">En Attente</option>
            <option value="completed">Complété</option>
            <option value="failed">Échoué</option>
            <option value="refunded">Remboursé</option>
        </select>
    </div>
    <div class="form-group">
        <label for="paid_at">Date de Paiement</label>
        <input type="datetime-local" name="paid_at" id="paid_at">
    </div>
    <button type="submit" class="btn">Créer</button>
</form>
@endsection
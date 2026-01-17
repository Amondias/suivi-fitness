@extends('layouts.app')

@section('title', 'Détails du Paiement')

@section('content')
<h1>Détails du Paiement</h1>
<p><strong>ID:</strong> {{ $payment->id }}</p>
<p><strong>Utilisateur:</strong> {{ $payment->user->name }}</p>
<p><strong>Abonnement:</strong> {{ $payment->subscription->name ?? 'N/A' }}</p>
<p><strong>Montant:</strong> {{ $payment->amount }} €</p>
<p><strong>Méthode:</strong> {{ $payment->payment_method }}</p>
<p><strong>ID Transaction:</strong> {{ $payment->transaction_id ?? 'N/A' }}</p>
<p><strong>Statut:</strong> {{ $payment->status }}</p>
<p><strong>Date de Paiement:</strong> {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : 'N/A' }}</p>
<a href="{{ route('payments.index') }}" class="btn">Retour</a>
@endsection
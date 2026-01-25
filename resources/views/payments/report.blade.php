@extends('layouts.app')

@section('title', 'Rapport Financier')

@section('content')
<h1>Rapport Financier</h1>
<div>
    <h2>Résumé</h2>
    <p><strong>Total des Paiements Complétés:</strong> {{ $totalPayments }} €</p>
    <p><strong>Paiements en Attente:</strong> {{ $pendingPayments }} €</p>
    <p><strong>Nombre de Paiements Échoués:</strong> {{ $failedPayments }}</p>
    <p><strong>Revenus du Mois en Cours:</strong> {{ $monthlyRevenue }} €</p>
</div>
<a href="{{ route('payments.index') }}" class="btn">Retour aux Paiements</a>
@endsection
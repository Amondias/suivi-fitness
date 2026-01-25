@extends('layouts.app')

@section('title', 'Liste des Paiements')

@section('content')
<h1>Liste des Paiements</h1>
<a href="{{ route('payments.create') }}" class="btn">Ajouter un Paiement</a>
<a href="{{ route('payments.report') }}" class="btn">Rapport Financier</a>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Utilisateur</th>
            <th>Abonnement</th>
            <th>Montant</th>
            <th>Méthode</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $payment)
        <tr>
            <td>{{ $payment->id }}</td>
            <td>{{ $payment->user->name }}</td>
            <td>{{ $payment->subscription->name ?? 'N/A' }}</td>
            <td>{{ $payment->amount }} €</td>
            <td>{{ $payment->payment_method }}</td>
            <td>{{ $payment->status }}</td>
            <td>
                <a href="{{ route('payments.show', $payment) }}" class="btn">Voir</a>
                <a href="{{ route('payments.edit', $payment) }}" class="btn">Éditer</a>
                <form action="{{ route('payments.destroy', $payment) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $payments->links() }}
@endsection
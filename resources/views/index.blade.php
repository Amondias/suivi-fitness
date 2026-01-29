@extends('layouts.app')

@section('title', 'Accueil - Suivi Fitness')

@section('content')
<h1>Bienvenue sur Suivi Fitness</h1>
<p>Cette application vous aide à suivre vos entraînements et paiements.</p>
<p><a href="{{ route('payments.index') }}" class="btn">Voir les Paiements</a></p>
@endsection
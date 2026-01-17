<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Suivi Fitness')</title>
    <link href="{{ asset('css/global.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="{{ route('home') }}">Accueil</a></li>
                <li><a href="{{ route('payments.index') }}">Paiements</a></li>
                <li><a href="{{ route('payments.report') }}">Rapport Financier</a></li>
                <!-- Ajouter d'autres liens si nécessaire -->
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; 2026 Suivi Fitness. Tous droits réservés.</p>
    </footer>

    @stack('scripts')
</body>
</html>
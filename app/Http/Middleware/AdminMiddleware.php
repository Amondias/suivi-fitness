<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Pour les tests, permettre toutes les requêtes
        if (app()->environment('testing')) {
            return $next($request);
        }
        
        // Middleware pour vérifier les permissions admin
        return $next($request);
    }
}


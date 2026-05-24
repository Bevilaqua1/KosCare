<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsResident
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isResident()) {
            abort(403, 'Akses hanya untuk penghuni kos.');
        }

        return $next($request);
    }
}
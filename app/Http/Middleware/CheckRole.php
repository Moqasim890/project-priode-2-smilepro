<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Je moet ingelogd zijn om deze pagina te bekijken.');
        }

        $user = Auth::user();

        if (!$user || !$user->hasAnyRole($roles)) {
            abort(403, 'Je hebt geen toegang tot deze pagina.');
        }

        return $next($request);
    }
}

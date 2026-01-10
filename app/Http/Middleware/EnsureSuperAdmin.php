<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user bukan superadmin, alihkan ke dashboard owner
        if (! Auth::user()->is_superadmin) {
            return redirect()->route('owner.dashboard');
        }

        return $next($request);
    }
}

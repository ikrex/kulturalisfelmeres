<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Ellenőrizzük, hogy a felhasználó be van-e jelentkezve és admin-e
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')
                ->with('error', 'Csak adminisztrátorok számára elérhető funkció.');
        }

        return $next($request);
    }
}

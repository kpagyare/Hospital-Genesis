<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Your account has been deactivated.']);
        }

        if (empty($roles) || in_array($user->role, $roles) || $user->role === 'super_admin') {
            return $next($request);
        }

        abort(403, 'You do not have permission to access this page.');
    }
}

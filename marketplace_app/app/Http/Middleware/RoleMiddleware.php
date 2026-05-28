<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if the route is an admin route
        $isAdminRoute = $request->is('admin/*') || $request->is('admin');
        
        $user = $request->user();

        if ($isAdminRoute) {
            // For admin routes, non-admins (guests/customers) get a 404 (Security Obfuscation)
            if (!$user || $user->role !== 'admin') {
                abort(404);
            }
        } else {
            // For standard non-admin protected routes
            if (!$user) {
                return redirect()->route('login');
            }

            if ($request->user()->role !== $role) {
                // Redirect depending on their actual role
                if ($request->user()->isAdmin()) {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
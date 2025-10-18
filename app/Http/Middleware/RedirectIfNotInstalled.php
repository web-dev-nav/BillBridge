<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if application is installed (check both possible lock files)
        $isInstalled = file_exists(storage_path('installed')) ||
                       file_exists(storage_path('installer.lock'));

        if (!$isInstalled) {
            // Allow installer routes
            if (!$request->is('installer*')) {
                return redirect()->route('installer.welcome');
            }
        } else {
            // If installed, redirect away from installer routes
            if ($request->is('installer*')) {
                return redirect('/');
            }
        }

        return $next($request);
    }
}

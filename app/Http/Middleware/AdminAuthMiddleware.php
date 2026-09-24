<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('admin.login')
                ->with('error', 'Akses ditolak. Halaman ini memerlukan hak akses Administrator.');
        }

        return $next($request);
    }
}

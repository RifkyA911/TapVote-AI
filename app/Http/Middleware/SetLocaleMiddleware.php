<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale') 
            ?? $request->cookie('app_locale') 
            ?? config('app.locale', 'en');

        if (!in_array($locale, ['en', 'id'])) {
            $locale = 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}

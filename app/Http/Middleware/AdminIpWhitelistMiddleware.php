<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminIpWhitelistMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIpsConfig = env('ADMIN_ALLOWED_IPS', '127.0.0.1,::1,192.168.1.5');
        
        // If set to wildcard '*', bypass check
        if (trim($allowedIpsConfig) === '*') {
            return $next($request);
        }

        $allowedIps = array_filter(array_map('trim', explode(',', $allowedIpsConfig)));

        // Always allow localhost loopbacks
        $allowedIps[] = '127.0.0.1';
        $allowedIps[] = '::1';
        $allowedIps = array_unique($allowedIps);

        $clientIp = $request->ip();

        // Check if client IP or forwarded IP matches any allowed IP
        $isAllowed = in_array($clientIp, $allowedIps, true);

        // Also check header forwarded IP if behind proxy
        if (!$isAllowed && $request->header('X-Forwarded-For')) {
            $forwarded = explode(',', $request->header('X-Forwarded-For'));
            foreach ($forwarded as $fIp) {
                if (in_array(trim($fIp), $allowedIps, true)) {
                    $isAllowed = true;
                    break;
                }
            }
        }

        if (!$isAllowed) {
            // Log security incident to activity log
            ActivityLog::log(
                'SECURITY_UNAUTHORIZED_IP',
                'FIREWALL',
                "Upaya akses ilegal ke Admin Panel dari IP: {$clientIp} pada URL: {$request->fullUrl()}"
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => '403 Forbidden: Akses ke Admin Panel ditolak untuk IP Anda (' . $clientIp . ').',
                    'client_ip' => $clientIp,
                ], 403);
            }

            return response()->view('errors.403_mocking', [
                'clientIp' => $clientIp,
                'targetUrl' => $request->fullUrl(),
            ], 403);
        }

        return $next($request);
    }
}

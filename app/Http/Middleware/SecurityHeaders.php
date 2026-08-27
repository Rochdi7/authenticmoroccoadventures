<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sets the security-related response headers and strips the headers that
 * advertise the server stack.
 *
 * This duplicates what public/.htaccess does, on purpose: .htaccess is ignored
 * when the site is served by Nginx or LiteSpeed without an Apache layer, and
 * PHP's own X-Powered-By is added after Apache has already run mod_headers on
 * some setups. Doing it here as well means the headers are correct regardless
 * of which web server the host uses.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        // Remove before the response is built where PHP allows it.
        if (! headers_sent()) {
            header_remove('X-Powered-By');
        }

        $response = $next($request);

        $response->headers->remove('X-Powered-By');

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set(
            'Permissions-Policy',
            'geolocation=(), microphone=(), camera=(), interest-cohort=()'
        );

        // HSTS is only meaningful — and only safe — over HTTPS.
        if ($request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=63072000; includeSubDomains; preload'
            );
        }

        return $response;
    }
}

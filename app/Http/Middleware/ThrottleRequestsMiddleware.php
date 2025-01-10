<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleRequestsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $maxAttempts
     * @param  int  $decayMinutes
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, $maxAttempts = 5, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);

        $rateLimiter = app(RateLimiter::class);

        if ($rateLimiter->tooManyAttempts($key, $maxAttempts)) {
            $seconds = $rateLimiter->availableIn($key);
            return response()->json([
                'message' => 'Too many attempts. Please try again later.',
                'status' => 429,
                'retry_after' => "$seconds seconds",
            ], 429);
        }

        $rateLimiter->hit($key, $decayMinutes * 60);

        return $next($request);
    }

    /**
     * Resolve the request signature.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function resolveRequestSignature(Request $request)
    {
        return sha1($request->ip());
    }
}

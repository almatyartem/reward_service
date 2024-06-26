<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if (!$token){
            return response('Unauthorized.', Response::HTTP_UNAUTHORIZED);
        }
        if ($request->bearerToken() !== env('API_TOKEN')) {
            return response('Forbidden.', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}

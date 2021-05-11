<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Auth\AuthenticationException;
use JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                throw new AuthenticationException('Token is Invalid.');
            }
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                throw new AuthenticationException('Token is Expired.');
            }

            throw new AuthenticationException('Authorization Token not found.');
        }

        return $next($request);
    }
}

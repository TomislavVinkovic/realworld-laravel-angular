<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Attempt to authenticate the user from the token in the request.
            JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            // Check if the token has expired.
            if ($e instanceof TokenExpiredException) {
                try {
                    $refreshedToken = JWTAuth::refresh(JWTAuth::getToken());
                    JWTAuth::setToken($refreshedToken)->authenticate();
                    $response = $next($request);
                    $response->headers->set('Authorization', 'Token ' . $refreshedToken);
                    
                    return $response;
                } catch (JWTException $refreshException) {
                    return response()->json(['error' => 'Token has expired. Please sign in again'], 401);
                }
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }
        return $next($request);
    }
}

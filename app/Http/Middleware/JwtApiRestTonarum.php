<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtApiRestTonarum
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $tokenUserConsumer = JWTAuth::parseToken()->authenticate();
            //             return abort(403);
                } catch (Exception $e) {
                    if ($e instanceof TokenInvalidException) {
                        return response()->json(['status' => Response::HTTP_UNAUTHORIZED, 'message' => 'token invalido'], Response::HTTP_UNAUTHORIZED);
                    }
                    if ($e instanceof TokenExpiredException) {
                        return response()->json(['status' => Response::HTTP_UNAUTHORIZED, 'message' => 'token expirado'], Response::HTTP_UNAUTHORIZED);
                    }
                }
                return $next($request->merge(['user'=>$tokenUserConsumer]));
    }
}

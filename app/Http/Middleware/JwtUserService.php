<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtUserService
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user=$request->get('user');
        if ($user['rol'] != 1) {
            return response()->json(['status' => Response::HTTP_UNAUTHORIZED, 'message' => 'Usuraio sin permiso'], Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}

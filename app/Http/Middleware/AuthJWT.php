<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthJWT {
    public function handle(Request $request, Closure $next): JsonResponse {
        try {
            if (!JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token invalid'], 401);
        }

        return $next($request);
    }
}


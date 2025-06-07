<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // ✅ ป้องกัน CORS ถูกเขียนซ้ำ
        $allowedHeaders = 'Authorization, Content-Type, X-Requested-With, Origin, Accept';
        $allowedMethods = 'GET, POST, PUT, DELETE, OPTIONS';

        $response->headers->set("Access-Control-Allow-Origin", "*");
        $response->headers->set("Access-Control-Allow-Methods", $allowedMethods);
        $response->headers->set("Access-Control-Allow-Headers", $allowedHeaders);
        $response->headers->set("Access-Control-Allow-Credentials", "true");

        // ✅ รองรับ OPTIONS Method (Safari, iOS)
        if ($request->isMethod("OPTIONS")) {
            return response()->json('CORS Preflight OK', 200)
                ->header("Access-Control-Allow-Origin", "*")
                ->header("Access-Control-Allow-Methods", $allowedMethods)
                ->header("Access-Control-Allow-Headers", $allowedHeaders);
        }

        return $response;
    }
}

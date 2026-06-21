<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');
        $validKey = config('api.api_key');

        if (!$apiKey || $apiKey !== $validKey) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized — invalid or missing API key',
                'data' => null,
            ], 401);
        }

        return $next($request);
    }
}

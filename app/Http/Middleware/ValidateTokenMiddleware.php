<?php

namespace App\Http\Middleware;

use App\User;
use Illuminate\Http\Request;
use Closure;
use Firebase\JWT\JWT;

class ValidateTokenMiddleware {
    public function handle(Request $request, Closure $next) {
        $token = $request->header('X-Access-Token');
        if (! $token) {
            return response()->json([
                'status' => 401,
                'error' => 'Unauthorized',
                'reason' => 'Unauthorized user',
            ], 401);
        }

        $secret_key = env('TOKEN_SECRET');
        try {
            $decoded = (array) JWT::decode($token, $secret_key, array('HS256'));
            $userId = $decoded['sub'];
            $user = User::find($userId);
            if (! $user) {
                return response()->json([
                    'status' => 401,
                    'error' => 'Unauthorized',
                    'reason' => 'Account is not exists'
                ], 401);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'error' => 'Bad request',
                'reason' => $e->getMessage(),
            ]);
        }
        return $next($request);
    }
}


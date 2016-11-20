<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{
    public function __construct()
    {
        //
    }

    /**
     * Sign in user with email and password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function SignIn(Request $request){
        $data = $request->all();
        $user = User::where('email', $data['email'])->first();
        if (! $user) {
            return response()->json([
                'status' => 401,
                'error' => 'Unauthorize',
                'reason' => 'User is not registered',
            ], 401);
        } else {
            if ($user->verifyPassword($data['password'])) {
                return response()->json($this->generateToken($user));
            } else {
                return response()->json([
                    'status' => 401,
                    'error' => 'Unauthorized',
                    'reason' => 'Wrong password'
                ], 401);
            }
        }
    }

    /**
     * Generate user access token
     *
     * @param $user
     * @return array
     */
    public function generateToken($user) {
        $ttl = 2 * 30 * 24 * 60 * 60; // 2 months
        $secret_key = env('TOKEN_SECRET');
        $payload = array(
            'id' => $user->id,
        );
        $jwt = JWT::encode($payload, $secret_key);

        return [
            'token' => $jwt,
            'ttl' => $ttl,
        ];
    }
}

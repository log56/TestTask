<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Authenticate the user and issue a JWT token.
     *
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
        // Extract login credentials from the request
        $credentials = request(['username', 'password']);

        // Attempt to authenticate the user using provided credentials
        if (! $token = Auth::attempt($credentials)) {
            // Return 401 Unauthorized if authentication fails
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Return token response on success
        return $this->respondWithToken($token);
    }

    /**
     * Logout the authenticated user (invalidate token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        // Invalidate current user's token
        Auth::logout();

        // Return success response
        return response()->json(['logged out']);
    }

    /**
     * Format the token response payload.
     *
     * @param string $token
     * @return JsonResponse
     */
    public function respondWithToken($token)
    {
        // Return access token and metadata
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 3600, // Token expiration time in seconds
        ]);
    }
}

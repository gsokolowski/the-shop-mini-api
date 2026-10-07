<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // RegisterRequest has got validation rules
    public function register(RegisterRequest $request): JsonResponse
    {
        // Eloquent mass-assigns those attributes and saves them
        // Laravel hashes password before storing.
        $user = User::query()->create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'is_admin' => false, // not admin by default
        ]);

        // issue Laravel Sanctum personal access token for this user
        // token is stoored in db table personal_access_tokens
        // and in Chrome -> Aplication->Storage->Local Storage
        // later verification is goes through Sanctum and the personal_access_tokens table.
        $token = $user->createToken('api')->plainTextToken;

        // return user with token and 201 status
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }
    // LoginRequest has got validation rules
    public function login(LoginRequest $request): JsonResponse
    {
        // find user based on email
        $user = User::query()
            ->where('email', $request->validated('email'))
            ->first();

        // ogin credential check: 
        // reject the request if the user doesn’t exist 
        // or the password is wrong
        if (! $user || ! Hash::check($request->validated('password'), $user->password)) {
            // 422 validation-style
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        // issue Laravel Sanctum personal access token for this user
        $token = $user->createToken('api')->plainTextToken;

        // Frontend it needs a session credential == token
        // and usually the current user profile
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        // remove access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out'
        ], 204);
    }
}
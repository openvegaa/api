<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    public function login(Request $request)
    {
        $data = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($data, (bool) $request->get('remember'))) {
            return $this->sendResponse('Invalid credentials', false, 401, 1308);
        }

        $user = Auth::user();
        $token = $user->createToken('auth:'.$user->email);
        return $this->sendResponse(
            'User logged in successfully',
            true,
            [
                'uid' => $user->id,
                'token' => $token->plainTextToken
            ]);
    }

    /**
     * Register a User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->sendResponse('User created successfully', true, ['uid' => $user->id, 'token' => $token]);
    }

    private function username(): string
    {
        return 'email';
    }
}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class UserController extends ApiController
{
    public function profile(): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $user = $user->only(['id', 'name', 'email']);
        return $this->sendResponse('Found', true, $user);
    }

    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $user->update($data);
        return $this->sendResponse('Your profile has been updated.', true);
    }
}

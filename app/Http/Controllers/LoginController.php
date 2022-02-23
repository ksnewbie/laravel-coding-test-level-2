<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login()
    {
        $credentials = request()->validate(
            [
                'username' => 'required',
                'password' => 'required'
            ]
        );

        // $data = $this->user_service->login($credentials);

        if (!auth()->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials.']);
        }

        $user = auth()->user();

        if ($user->role == 'admin') {
            $data = [
                'manage-users',
            ];
        } elseif ($user->role == 'product_owner') {
            $data = [
                'manage-projects',
                'manage-tasks'
            ];
        } else {
            $data = [
                'manage-tasks',
            ];
        }
        $accessToken = $user->createToken('authToken', $data);

        $data = [
            'user' => $user,
            'accessToken' => $accessToken->plainTextToken
        ];

        return response()->json($data);
    }
}

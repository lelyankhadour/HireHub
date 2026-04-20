<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\ProfileResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }
// DIP 
    public function register(RegisterRequest $request)
    {
        $result = $this->service->register($request->validated());

        return response()->json([
            'user'  => new ProfileResource($result['user']),
            'token' => $result['token'],
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->service->login($request->validated());

        if (! $result) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'user'  => new ProfileResource($result['user']),
            'token' => $result['token'],
        ]);
    }

    public function logout(Request $request)
    {
        $this->service->logout($request->user());

        return response()->json(['message' => 'Logged out successfully']);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\ProfileResource;
use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
class AuthController extends Controller
{use ResponseTrait;
    protected AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }
// DIP 
    public function register(RegisterRequest $request)
    {try{
        $result = $this->service->register($request->validated());

        // return response()->json([
        //     'user'  => new ProfileResource($result['user']),
        //     'token' => $result['token'],
        // ], 201);
        
        $data= [
            'user'  => new ProfileResource($result['user']),
            'token' => $result['token'],
        ];
                return $this->successResponse($data, "Register completed successfully", 201);
            
        }catch (\Throwable $e) {
            return $this->errorResponse("An unexpected error occurred " , 500);
        } 
    }

    public function login(LoginRequest $request)
    {try{
        $result = $this->service->login($request->validated());

        if (! $result) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // return response()->json([
        //     'user'  => new ProfileResource($result['user']),
        //     'token' => $result['token'],
        // ]);
                $data=[
            'user'  => new ProfileResource($result['user']),
            'token' => $result['token'],
        ];
        
                return $this->successResponse( $data, "Login completed successfully", 200);
            
        }catch (\Throwable $e) {
            return $this->errorResponse("Something went wrong " , 500);
        } 
    }

    public function logout(Request $request)
    {try{
        $this->service->logout($request->user());

        // return response()->json(['message' => 'Logged out successfully']);
        
     return $this->successResponse( null, "Logged out successfully", 200);
            
        }catch (\Throwable $e) {
            return $this->errorResponse("An unexpected error occurred" , 500);
        } 
    }
}

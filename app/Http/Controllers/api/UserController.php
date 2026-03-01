<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ResponseTrait;
use App\Http\Requests\LoginRequest;
use App\Services\RegisterService;
use App\Http\Requests\RegisterRequest;



class UserController extends Controller
{
    use ResponseTrait;
    protected RegisterService $registerService;

    public function __construct(RegisterService $registerService){
        $this->registerService = $registerService;
    }

    public function login(LoginRequest $request){
        $validated = $request->validated();
        if(Auth::attempt($validated)){
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return $this->sendResponse([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }else{
            return $this->sendError('Hibás email vagy jelszó', [], 401);
        }
    }

    public function register(RegisterRequest $request){
        $validated = $request->validated();
        return $this->registerService->create($validated);
    }
    
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse([], 'Sikeres kijelentkezés');
    }


}

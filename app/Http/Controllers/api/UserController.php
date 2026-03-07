<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Traits\ResponseTrait;
use App\Http\Requests\LoginRequest;
use App\Services\RegisterService;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateMyProfileRequest;
use App\Services\UserService;
use App\Http\Requests\SetPasswordRequest; 



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

    public function getProfile(Request $request)
    {
        $user = $request->user();
        Gate::authorize('viewProfile', $user);

        return $this->sendResponse($user->load('profile'), 'Profil lekérve.');
    }

    public function updateProfile(UpdateMyProfileRequest $request, UserService $userService)
    {
        $user = $request->user();
        Gate::authorize('updateProfile', $user);

        $validated = $request->validated();
        $updatedUser = $userService->updateProfile($user, $validated);

        return $this->sendResponse($updatedUser, 'Profil frissítve.');
    }

    public function updateProfileByAdmin(UpdateMyProfileRequest $request, User $user, UserService $userService)
    {
        Gate::authorize('updateProfileByAdmin', $user);

        $validated = $request->validated();
        $updatedUser = $userService->updateProfileByAdmin($user, $validated);

        return $this->sendResponse($updatedUser, 'Profil frissítve.');
    }

    public function makeAdmin(User $user, UserService $userService)
    {
        Gate::authorize('updateRole', $user);

        $updated = $userService->updateRole($user, 'admin');

        return $this->sendResponse($updated, 'Felhasználó admin jogosultságot kapott.');
    }

    public function removeAdmin(User $user, UserService $userService)
    {
        Gate::authorize('updateRole', $user);

        $updated = $userService->updateRole($user, 'user');

        return $this->sendResponse($updated, 'Admin jogosultság elvéve.');
    }

    public function deleteUser(User $user, UserService $userService)
    {
        Gate::authorize('delete', $user);
        $deleted = $userService->deleteUser($user);

        return $this->sendResponse($deleted, 'Felhasználó törölve.');
    }

    public function getProfiles()
    {
        Gate::authorize('viewProfiles', User::class);

        $users = User::with('profile')->get();
        return $this->sendResponse($users, 'Felhasználók lekérve.');
    }

    public function setPasswordByAdmin(SetPasswordRequest $request, User $user, UserService $userService)
    {
        Gate::authorize('setPasswordByAdmin', $user);

        $validated = $request->validated();
        $updated = $userService->setPasswordByAdmin($user, $validated['password']);

        return $this->sendResponse($updated, 'Jelszó frissítve.');
    }

    public function setPassword(SetPasswordRequest $request, UserService $userService)
    {
        $user = $request->user();
        Gate::authorize('setPassword', $user);

        $validated = $request->validated();
        $updated = $userService->setPassword($user, $validated['password']);

        return $this->sendResponse($updated, 'Jelszó frissítve.');
    }

}

<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        
    }
    

    public function viewAny(User $user): Response
    {
        if($user->isAdmin() || $user->isSuperAdmin()) {
            return Response::allow();
        }
        return Response::deny("Nincs jogosultságod felhasználók megtekintéséhez.");
    }

    public function viewProfile(User $user, User $targetUser): Response
    {
        if ($user->id === $targetUser->id) {
            return Response::allow();
        }

        return Response::deny('Nincs jogosultságod más profiljának megtekintéséhez.');
    }

    public function updateProfile(User $user, User $targetUser): Response
    {
        if ($user->id === $targetUser->id) {
            return Response::allow();
        }

        return Response::deny('Nincs jogosultságod más profiljának szerkesztéséhez.');
    }

    public function updateRole(User $user, User $targetUser): Response
    {
        if ($user->isSuperAdmin()) {
            return Response::allow();
        }

        return Response::deny('Csak a szuper adminisztrátor adhat vagy vehet el adminisztrátor jogosultságot.');
    }
}

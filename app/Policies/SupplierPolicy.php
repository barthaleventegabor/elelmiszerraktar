<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Supplier;
use Illuminate\Auth\Access\Response;

class SupplierPolicy
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
        return Response::deny("Nincs jogosultságod beszállítók megtekintéséhez.");
    }


    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Supplier $supplier): Response
    {
        if($user->isAdmin() || $user->isSuperAdmin()) {
            return Response::allow();
        }
        return Response::deny("Nincs jogosultságod beszállító megtekintéséhez.");
    }


    public function create(User $user): Response
    {
        if($user->isAdmin() || $user->isSuperAdmin()) {
            return Response::allow();
        }
        return Response::deny("Nincs jogosultságod beszállító létrehozásához.");
    }

    public function update(User $user, Supplier $supplier): Response
    {
        if($user->isAdmin() || $user->isSuperAdmin()) {
            return Response::allow();
        }
        return Response::deny("Nincs jogosultságod beszállító szerkesztéséhez.");
    }


    public function delete(User $user, Supplier $supplier): Response
    {
        if($user->isSuperAdmin()) {
            return Response::allow();
        }
        return Response::deny("Nincs jogosultságod beszállító törléséhez.");
    }
}

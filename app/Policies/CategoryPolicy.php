<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
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
        return Response::deny("Nincs jogosultságod kategóriák megtekintéséhez.");
    }


    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Category $category): Response
    {
        if($user->isAdmin() || $user->isSuperAdmin()) {
            return Response::allow();
        }
        return Response::deny("Nincs jogosultságod kategória megtekintéséhez.");
    }


    public function create(User $user): Response
    {
        if($user->isAdmin() || $user->isSuperAdmin()) {
            return Response::allow();
        }
        return Response::deny("Nincs jogosultságod kategória létrehozásához.");
    }

    public function update(User $user, Category $category): Response
    {
        if($user->isAdmin() || $user->isSuperAdmin()) {
            return Response::allow();
        }
        return Response::deny("Nincs jogosultságod kategória szerkesztéséhez.");
    }


    public function delete(User $user, Category $category): Response
    {
        if($user->isSuperAdmin()) {
            return Response::allow();
        }
        return Response::deny("Nincs jogosultságod kategória törléséhez.");
    }
}

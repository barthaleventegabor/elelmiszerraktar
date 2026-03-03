<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function updateProfile(User $user, array $data): User
    {
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return $user->fresh('profile');
    }

    public function updateRole(User $targetUser, string $role): User
    {
        $targetUser->role = $role;
        $targetUser->save();

        return $targetUser;
    }
}

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

    public function deleteUser(User $targetUser): bool
    {
        return $targetUser->delete();
    }

    public function setPasswordByAdmin(User $targetUser, string $password): User
    {
        $targetUser->password = bcrypt($password);
        $targetUser->save();

        return $targetUser;
    }

    public function setPassword(User $user, string $password): User
    {
        $user->password = bcrypt($password);
        $user->save();

        return $user;
    }

    public function updateProfileByAdmin(User $targetUser, array $data): User
    {
        $targetUser->profile()->updateOrCreate(
            ['user_id' => $targetUser->id],
            $data
        );

        return $targetUser->fresh('profile');
    }

    
}

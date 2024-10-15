<?php

namespace App\Actions\Jetstream;

use App\Models\User;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        if ($user->isRole('admin') &&
            count(User::all()->where('role', '=', 'admin')) <= 1
        ) return;

        if ($user->isRole('user'))
            $user->update(['firstname' => $user->id, 'middlename' => $user->id, 'lastname' => $user->id, 'email' => $user->id, 'password' => $user->id,]);
        $user->deleteProfilePhoto();
        $user->profile()->delete();
    }
}

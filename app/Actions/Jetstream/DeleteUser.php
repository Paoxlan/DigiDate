<?php

namespace App\Actions\Jetstream;

use App\Models\AuditTrail;
use App\Models\User;
use App\Traits\AuditTrailable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    use AuditTrailable;

    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        if ($user->isRole('admin') &&
            count(User::all()->where('role', '=', 'admin')) <= 1
        ) return;

        AuditTrail::create([
            'user_id' => $user->id,
            'class' => User::class,
            'method' => 'Delete',
            'model' => $this->auditTrailJson($user)
        ]);

        if ($user->isRole('user'))
            $user->update(['firstname' => $user->id, 'middlename' => $user->id, 'lastname' => $user->id, 'email' => $user->id, 'password' => $user->id,]);

        $user->deleteProfilePhoto();

        $user->profile()->delete();
    }

    protected function getHiddenAuditTrailAttributes(): array
    {
        return [
            'email_verified_at',
            'two_factor_confirmed_at',
            'profile_photo_path',
            'profile_photo_url',
            'updated_at'
        ];
    }

    public function auditTrailJson(Model $models): string
    {
        $modelArray = $models->withoutRelations()->toArray();
        $modelArray = array_filter($modelArray, function ($key) {
            return !in_array($key, $this->getHiddenAuditTrailAttributes());
        }, ARRAY_FILTER_USE_KEY);

        $modelArray['firstname'] = "REDACTED FOR PRIVACY";
        $modelArray['middlename'] = "REDACTED FOR PRIVACY";
        $modelArray['lastname'] = "REDACTED FOR PRIVACY";
        $modelArray['email'] = "REDACTED FOR PRIVACY";

        return json_encode($modelArray);
    }
}

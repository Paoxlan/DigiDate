<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\AuditTrail;
use App\Models\User;
use App\Traits\AuditTrailable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    use PasswordValidationRules, AuditTrailable;

    public function index()
    {
        return view('manage.admin-overview', [
            'users' => User::all(),
        ]);
    }

    public function create()
    {
        return view('admin.register');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        Validator::make($input, [
            'firstname' => ['required', 'string', 'max:40'],
            'middlename' => ['nullable', 'string', 'max:40'],
            'lastname' => ['required', 'string', 'max:40'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'firstname' => $input['firstname'],
            'middlename' => $input['middlename'],
            'lastname' => $input['lastname'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => 'admin'
        ]);

        AuditTrail::create([
            'user_id' => auth()->id(),
            'class' => User::class,
            'method' => 'Create',
            'model' => $this->auditTrailJson($user)
        ]);

        return redirect()
            ->route('manage.admins', ['users' => User::all()])
            ->with('success', 'Admin aangemaakt.');
    }

    public function destroy(User $user)
    {
        if (auth()->user()->id === $user->id)
            return back()->withErrors([
                'logged_in' => 'You cannot delete your own account.',
            ]);

        AuditTrail::create([
            'user_id' => auth()->id(),
            'class' => User::class,
            'method' => 'Delete',
            'model' => $this->auditTrailJson($user, true)
        ]);

        $user->delete();

        return back()->with('success', 'User has been successfully deleted.');
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

    public function auditTrailJson(Model $models, bool $deleted = false): string
    {
        $modelArray = $models->withoutRelations()->toArray();
        $modelArray = array_filter($modelArray, function ($key) {
            return !in_array($key, $this->getHiddenAuditTrailAttributes());
        }, ARRAY_FILTER_USE_KEY);

        if ($deleted) {
            $modelArray['firstname'] = "REDACTED FOR PRIVACY";
            $modelArray['middlename'] = "REDACTED FOR PRIVACY";
            $modelArray['lastname'] = "REDACTED FOR PRIVACY";
            $modelArray['email'] = "REDACTED FOR PRIVACY";
        }

        return json_encode($modelArray);
    }
}

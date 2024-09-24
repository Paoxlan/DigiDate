<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    use PasswordValidationRules;

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

        User::create([
            'firstname' => $input['firstname'],
            'middlename' => $input['middlename'],
            'lastname' => $input['lastname'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => 'admin'
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

        $user->delete();

        return back()->with('success', 'User has been successfully deleted.');
    }
}

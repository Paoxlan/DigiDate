<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        return view('manage.admin-overview', [
            'users' => User::all(),
        ]);
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

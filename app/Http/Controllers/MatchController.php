<?php

namespace App\Http\Controllers;

use App\Models\User;

class MatchController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('user.matches-list', ['matched_users' => $user->getMatchedUsers()]);
    }

    public function find(User $user)
    {
        $currentUser = auth()->user();

        $found = false;
        $matchedUsers = $currentUser->getMatchedUsers();
        foreach ($matchedUsers as $matchedUser) {
            if ($matchedUser->id !== $user->id) continue;

            $found = true;
            break;
        }

        if (!$found) abort(404);

        return view('user.matches-list', [
            'matched_users' => $matchedUsers,
            'chatting_with' => $user
        ]);
    }
}

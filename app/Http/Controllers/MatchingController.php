<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use App\Models\LikedUsers;
use App\Models\User;
use App\Traits\AuditTrailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchingController extends Controller
{
    use AuditTrailable;

    public function index()
    {
        $authUserId = Auth::id();

        // Get all users that the authenticated user has already liked or disliked
        $likedOrDislikedUserIds = LikedUsers::where('from_id', $authUserId)->pluck('to_id');

        // Fetch all users except the authenticated user and those they liked/disliked
        $users = User::where('id', '!=', $authUserId)
            ->whereNotIn('id', $likedOrDislikedUserIds)
            ->get();

        return view('user.matching-list', [
            'users' => $users,
        ]);
    }

    public function like(Request $request)
    {
        $likedUser = User::find($request->user);
        $likedByUser = Auth::user()->id;

        $likedUsers = new LikedUsers();

        $likedUsers->from_id = $likedByUser;
        $likedUsers->to_id = $likedUser->id;
        $likedUsers->is_liked = true;

        $likedUsers->save();
        AuditTrail::create([
            'user_id' => Auth::id(),
            'class' => LikedUsers::class,
            'method' => 'Create',
            'model' => $this->auditTrailJson($likedUsers)
        ]);

        return redirect()->route('matching');
    }

    public function dislike(Request $request)
    {
        $dislikedUser = User::find($request->user);
        $dislikedByUser = Auth::user()->id;

        $dislikedUsers = new LikedUsers();

        $dislikedUsers->from_id = $dislikedByUser;
        $dislikedUsers->to_id = $dislikedUser->id;
        $dislikedUsers->is_liked = false;

        $dislikedUsers->save();
        AuditTrail::create([
            'user_id' => Auth::id(),
            'class' => LikedUsers::class,
            'method' => 'Create',
            'model' => $this->auditTrailJson($dislikedUsers)
        ]);

        return redirect()->route('matching');
    }

    protected function getHiddenAuditTrailAttributes(): array
    {
        return [
            'id',
            'updated_at'
        ];
    }
}


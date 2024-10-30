<?php

namespace App\Livewire;

use App\Models\LikedUsers;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MatchingProfiles extends Component
{
    use WithPagination;

    public bool $usePreference = true;
    private int $perPage = 8;

    public ?int $minimumAge;
    public ?int $maximumAge;

    /*** @var Tag[] */
    public array $tags = [];
    public ?int $minimumTags = 0;

    public function mount(): void
    {
        $preferences = Auth::user()->preference;
        $this->minimumAge = $preferences->minimum_age;
        $this->maximumAge = $preferences->maximum_age;
    }

    /**
     * Returns a bool based on the preferences and profile between the two users.
     * @param User $user
     * @param User $otherUser
     * @return bool
     */
    public function filterOnPreferences(User $user, User $otherUser): bool
    {
        $profile = $user->profile;
        $preferences = $user->preference;
        $otherProfile = $otherUser->profile;
        $otherTags = $otherUser->getTags();
        $otherPreferences = $otherUser->preference;

        $rightGender = !$preferences->gender || $preferences->gender === $otherProfile->gender;
        $ageIsInBetween = ($this->minimumAge ?? $preferences->minimum_age ?? 18) < $otherProfile->age &&
            ($this->maximumAge ?? $preferences->maximum_age ?? PHP_INT_MAX) > $otherProfile->age;

        $equalTagAmount = count(array_filter($this->tags, function ($tag) use ($otherTags) {
            foreach ($otherTags as $otherTag) {
                if ($otherTag->id === $tag->id) return $tag;
            }

            return null;
        }));

        return $ageIsInBetween && $rightGender && $equalTagAmount >= min($this->minimumTags ?? 0, count($this->tags));
    }

    /**
     * @return User[]|LengthAwarePaginator
     */
    public function getUsers(): array|LengthAwarePaginator
    {
        $user = Auth::user();
        $userId = $user->id;

        // Get all users that the authenticated user has already liked or disliked
        $likedOrDislikedUserIds = LikedUsers::where('from_id', $userId)->pluck('to_id');

        // Fetch all users except the authenticated user and those they liked/disliked
        $users = User::where('id', '!=', $userId)
            ->whereNotIn('id', $likedOrDislikedUserIds)
            ->get()
            ->filter(function (User $otherUser) use ($user) {
                if ($otherUser->isRole('admin')) return false;
                if (!$this->usePreference) return true;

                return $this->filterOnPreferences($user, $otherUser);
            });

        $page = Paginator::resolveCurrentPage() ?? 1;
        return new LengthAwarePaginator(
            $users->forPage($page, $this->perPage), $users->count(), $this->perPage, $page, ['path' => Paginator::resolveCurrentPath()]
        );
    }

    public function addTag(Tag $tag): void
    {
        $this->tags[] = $tag;
    }

    public function removeTag(Tag $tag): void
    {
        $index = -1;
        foreach ($this->tags as $key => $value) {
            if ($value->id !== $tag->id) continue;
            $index = $key;
            break;
        }

        if ($index === -1) return;

        array_splice($this->tags, $index, 1);
    }

    public function hasTag(Tag $requestedTag): bool
    {
        foreach ($this->tags as $tag) {
            if ($requestedTag->id === $tag->id) return true;
        }

        return false;
    }

    public function getUserProperty(): User
    {
        return Auth::user();
    }

    public function getPreferencesProperty(): UserPreference
    {
        return Auth::user()->preference;
    }

    public function render(): View
    {
        return view('livewire.matching-profiles');
    }
}

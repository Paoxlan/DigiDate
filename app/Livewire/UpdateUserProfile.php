<?php

namespace App\Livewire;

use App\Enums\Gender;
use App\Models\Tag;
use App\Models\TaggedUser;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateUserProfile extends Component
{
    use WithFileUploads;

    public $state = [];
    public $photo;
    public $verificationLinkSent = false;

    public function mount(): void
    {
        $user = Auth::user();
        $profile = $user->profile;

        $this->state = array_merge(
            ['email' => $user->email],
            $user->withoutRelations()->toArray(),
            $profile
                ? array_merge(
                $profile->withoutRelations()->toArray(),
                $profile->residence->toArray()
            ) : []
        );
    }

    public function addTag(Tag $tag): void
    {
        $user = Auth::user();
        if (!$user) return;

        TaggedUser::where('user_id', $user->id)
            ->where('tag_id', $tag->id)
            ->firstOrCreate([
                'user_id' => $user->id,
                'tag_id' => $tag->id
            ]);
    }

    public function removeTag(Tag $tag): void
    {
        $user = Auth::user();
        if (!$user) return;

        TaggedUser::where('user_id', $user->id)
            ->where('tag_id', $tag->id)
            ->delete();
    }

    public function updateProfileInformation(UpdatesUserProfileInformation $updater): ?RedirectResponse
    {
        $this->resetErrorBag();
        $user = Auth::user();

        Validator::make($this->state, ['email' => [
            'required',
            'string',
            'email',
            'max:255',
            Rule::unique('users')->ignore($user->id)
        ]])->validate();

        $updater->update(
            $user,
            $this->photo
                ? ['email' => $this->state['email'], 'photo' => $this->photo]
                : ['email' => $this->state['email']]
        );

        if ($user->isRole('user')) {
            Validator::make($this->state, [
                'bio' => ['nullable', 'string', 'max:100'],
                'residence' => ['required', 'string', 'max:255'],
                'study' => ['nullable', 'string', 'max:40'],
                'gender' => ['required', 'string']
            ])->validate();

            if (!Gender::tryFrom(strtolower($this->state['gender']))) abort(400);

            $profile = $user->profile;

            $residence = $profile->residence
                ->where('residence', $this->state['residence'])
                ->firstOrCreate(['residence' => $this->state['residence']]);

            $user->profile->update([
                'bio' => $this->state['bio'],
                'residence_id' => $residence->id,
                'study' => $this->state['study'],
                'gender' => $this->state['gender']
            ]);
        }

        if (isset($this->photo)) {
            return redirect()->route('profile.show');
        }

        $this->dispatch('saved');
        $this->dispatch('refresh-navigation-menu');

        return null;
    }

    public function deleteProfilePhoto(): void
    {
        Auth::user()->deleteProfilePhoto();

        $this->dispatch('refresh-navigation-menu');
    }

    public function sendEmailVerification(): void
    {
        Auth::user()->sendEmailVerificationNotification();

        $this->verificationLinkSent = true;
    }

    public function hasTag(Tag $tag): bool
    {
        foreach (Auth::user()->getTags() as $userTag) {
            if ($userTag->id === $tag->id) return true;
        }

        return false;
    }

    public function getUserProperty(): User
    {
        return Auth::user();
    }

    public function getTagsProperty()
    {
        return Tag::all();
    }

    public function render(): View
    {
        return view('profile.update-profile-information-form');
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Role;
use App\Models\UserProfile as Profile;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    public string $name;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Returns the profile this user has.
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Returns the preferences this user has.
     * @return HasOne
     */
    public function preference(): HasOne
    {
        return $this->hasOne(UserPreference::class);
    }

    /**
     * Returns all UserTags this user has.
     * @return HasMany
     */
    public function userTags(): HasMany
    {
        return $this->hasMany(TaggedUser::class);
    }

    /**
     * Checks if this user has the specified tag.
     * @param Tag|int $tag
     * @return bool
     */
    public function hasTag(Tag|int $tag): bool
    {
        return TaggedUser::userHasTag($this, $tag);
    }

    /**
     * returns all tags from the user.
     * @return Tag[]
     */
    public function getTags(): array
    {
        $tags = [];

        foreach ($this->userTags as $userTag)
            $tags[] = $userTag->tag;

        return $tags;
    }

    /**
     * @return User[]|Collection
     */
    public function getMatchedUsers(): array|Collection
    {
        $likedUsersMatched = LikedUsers::getLikedUsersFrom($this)
            ->filter(function ($likedUsers) {
                return LikedUsers::userIsMatchedWith($this, $likedUsers->likedUser);
            });


        return $likedUsersMatched->map(fn ($likedUsers) => $likedUsers->likedUser);
    }

    public function isRole(Role|string $role): bool
    {
        if ($role instanceof Role) return $role === $this->role;

        return Role::tryFrom($role) === $this->role;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role' => Role::class,
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function defaultProfilePhotoUrl(): string
    {

        $name = trim(collect(explode(' ', $this->full_name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(get: fn($_, array $attributes) => implode(" ",
            array_filter(
                \Arr::only($attributes, ['firstname', 'middlename', 'lastname'])
            )
        ));
    }
}

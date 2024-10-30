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

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function preference(): HasOne
    {
        return $this->hasOne(UserPreference::class);
    }

    public function userTags(): HasMany
    {
        return $this->hasMany(TaggedUser::class);
    }

    public function hasTag(Tag|int $tag): bool
    {
        return TaggedUser::userHasTag($this, $tag);
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        $tags = [];

        foreach ($this->userTags as $userTag)
            $tags[] = $userTag->tag;

        return $tags;
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

    protected function fullName(): Attribute
    {
        return Attribute::make(get: fn($_, array $attributes) => implode(" ",
            array_filter(
                \Arr::only($attributes, ['firstname', 'middlename', 'lastname'])
            )
        ));
    }

    protected static function boot(): void
    {
        parent::boot();

        static::retrieved(function ($user) {
            $user->name = $user->fullname;
        });
    }
}

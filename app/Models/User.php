<?php

namespace App\Models;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;

class User extends Authenticatable implements JWTSubject, LaratrustUser, MustVerifyEmail
{
    use HasFactory, Notifiable, HasRolesAndPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array<string>
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function role(): Role
    {
        // @phpstan-ignore-next-line
        return $this->roles->first();
    }

    /**
     * @return HasMany<Post>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * @param array<string, bool> $options
     */
    public function save(array $options = []): bool
    {
        if ($this->isDirty('email'))
            $this->email_verified_at = null;

        $result = parent::save($options);

        if ($this->wasChanged('email') || !$this->hasVerifiedEmail())
            event(new Registered($this));

        return $result;
    }

    public function delete(): bool
    {
        if (!parent::delete())
            return false;
        $this->syncRoles([]);
        return true;
    }

    /**
     * @return array<string>
     */
    public function getAllPermissionsNames(): array
    {
        $userPermissions = $this->allPermissions();
        $permissionNames = [];
        foreach ($userPermissions as $permission) {
            // @phpstan-ignore-next-line
            array_push($permissionNames, $permission->name);
        }
        return $permissionNames;
    }
}

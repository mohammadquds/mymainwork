<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'start_date',
        'end_date',
        'status',
        'admin_id',
        'invite_code',
        'company_name',
    ];
    function activity_logs()
    {
        return $this->hasMany(Activity_logs::class, 'user_id');
    }
    function subscriptions()
    {
        return $this->hasMany(Subscriptions::class, 'user_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //////
 protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    'start_date' => 'date', // Make sure this is here
    'end_date' => 'date',   // Make sure this is here
];


// Gets the Admin who created this user
public function manager()
{
    return $this->belongsTo(User::class, 'admin_id');
}

// Gets all the regular users this Admin created
public function teamMembers()
{
    return $this->hasMany(User::class, 'admin_id');
}


// Helper to check if they are still active
public function isSubscribed()
{
    if ($this->hasRole('Super Admin')) return true; // Admin never gets blocked

    return $this->end_date && $this->end_date->isFuture();
}

// Add this to User.php
    public function children()
    {
        // This tells Laravel: "Find all users where their admin_id matches my id"
        return $this->hasMany(User::class, 'admin_id');
    }

///////


    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}

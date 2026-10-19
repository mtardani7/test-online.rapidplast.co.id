<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\CandidatePasswordReset;
use App\Notifications\CandidateVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->detail()->create([]);
        });
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new CandidateVerifyEmail());
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CandidatePasswordReset($token));
    }

    public function attempts()
    {
        return $this->hasMany(\App\Models\Attempt::class);
    }

    public function detail()
    {
        return $this->hasOne(DetailUser::class);
    }
}

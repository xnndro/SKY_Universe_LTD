<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Roles;
use App\Models\MatchPartner;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'dating_code',
        'birthday',
        'gender',
        'email',
        'password',
        'phone',
        'profile_picture',
        'banned',
        'role_id',
        'user_dating_id'
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
        'password' => 'hashed',
    ];

    public function getAuthIdentifierName()
    {
        return 'user_dating_id'; // or 'email', depending on the login input type
    }
    
    public function role()
    {
        return $this->belongsTo(Roles::class, 'role_id', 'id');
    }

    public function match()
    {
        return $this->hasMany(MatchPartner::class, 'user_id', 'id');
    }

    public function matchPartner()
    {
        return $this->hasMany(MatchPartner::class, 'partner_id', 'id');
    }
}

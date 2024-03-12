<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;


    protected $fillable = [
        'name', 'username', 'email', 'password', 'role_id', 'image'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // function getNameAttribute($value){
    //     $this->attributes['name'] = ucfirst($value);
    // }

    function setUsernameAttribute($username){
        $this->attributes['username'] = strtolower($username);
    }

    function setEmailAttribute($email){
        $this->attributes['email'] = strtolower($email);
    }

    function roles()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}

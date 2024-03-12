<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $guard = 'customer';


    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'role_id', 'country', 'address', 'address2', 'city', 'zip', 'phone', 'image'];

    
    protected $hidden = [
        'password',
    ];
    
    protected $casts = [
        'password' => 'hashed'
    ];
}

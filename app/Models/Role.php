<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'perms'];

    protected $casts = [
        'perms' => 'arrray'
    ];

    function setNameAttribute($value){
        $this->attributes['name'] = Str::ucfirst($value);
        $this->attributes['slug'] = Str::slug(strtolower($value));
    }

    // public function getNameAttribute($value)
    // {
    //     return ucfirst($value); // Capitalize the first letter of the title
    // }


}

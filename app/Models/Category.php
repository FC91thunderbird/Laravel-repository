<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image'];

    function setNameAttribute($name){
        $this->attributes['name'] = ucfirst($name);
        $this->attributes['slug'] = Str::slug($name);
    }
}

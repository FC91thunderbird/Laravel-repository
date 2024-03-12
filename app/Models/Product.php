<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'category', 'subcategory', 'buy_price', 'price', 'size', 'color', 'description','image','quantity'];


    protected $casts =[
        'size'=> 'array',
        'color'=> 'array'
    ];

    function setTitleAttributes($title){
        $this->attributes['title'] = $title;
        $this->attributes['slug'] = Str::slug($title);
    }

    function Category(){
        return $this->hasOne(Category::class, 'id', 'category');
    }

    function Subcategory(){
        return $this->hasOne(Subcategory::class,'id', 'subcategory');
    }

    
}

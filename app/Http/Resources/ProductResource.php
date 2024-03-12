<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'category' => $this->Category->name,
            'subcategory' => $this->Subcategory->name,
            'buy_price' => $this->buy_price,
            'price' => $this->price,
            'description' => $this->description,
            'image' => $this->image,
            'size'=> $this->size,
            'color'=> $this->color
        ];
    }
}

<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\PaginationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryList extends JsonResource
{
    public function toArray(Request $request): array
    {
        // $this->collection  $this->resource->items()
        return [
            'data' => CategoryResource::collection($this->resource->items()),
            'pagination' => new PaginationResource($this->resource),
        ];
    }
}

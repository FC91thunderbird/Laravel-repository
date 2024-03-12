<?php

namespace App\Http\Resources\User;

use App\Http\Resources\PaginationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
         // $this->collection  $this->resource->items()
        return [
            'data' => UserResource::collection($this->resource->items()),
            'pagination' => new PaginationResource($this->resource),
        ];
    }
}

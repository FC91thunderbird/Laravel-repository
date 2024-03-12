<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'name' => ucfirst($this->name),
            'username' => $this->username,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'role' => $this->role_id ? $this->roles->name : null,
            'image' => $this->image,
            'status' => $this->status
        ];
    }
}

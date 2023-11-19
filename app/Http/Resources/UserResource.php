<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "role"=> $this->role,
            "nomor_induk" => $this->nomor_induk,
            "name" => $this->name,
            "email" => $this->email,
            
            // "" => $this->avatar,
            // "" => $this->avatar_url,

        ];
    }
}

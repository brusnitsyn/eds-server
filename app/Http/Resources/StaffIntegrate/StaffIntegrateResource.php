<?php

namespace App\Http\Resources\StaffIntegrate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffIntegrateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'login' => $this->login,
            'password' => $this->password,
            'link' => $this->link,
        ];
    }
}

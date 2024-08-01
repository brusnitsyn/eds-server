<?php

namespace App\Http\Resources\Certification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortCertificationResource extends JsonResource
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
            'serial_number' => $this->serial_number,
            'valid_from' => (int)$this->valid_from,
            'valid_to' => (int)$this->valid_to,
            'has_valid' => $this->actual()['has_valid'],
            'has_request_new' => $this->actual()['has_request_new']
        ];
    }
}

<?php

namespace App\Http\Resources\PacientFallEvent;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PacientFallEventResource extends JsonResource
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
            'full_name' => $this->full_name,
            'reason' => $this->reason,
            'place' => $this->place,
            'held_event' => $this->held_event,
            'consequence' => $this->consequence,
            'date' => $this->date,
        ];
    }
}

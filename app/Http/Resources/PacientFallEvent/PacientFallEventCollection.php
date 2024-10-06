<?php

namespace App\Http\Resources\PacientFallEvent;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PacientFallEventCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'events' => $this->collection
        ];
    }
}

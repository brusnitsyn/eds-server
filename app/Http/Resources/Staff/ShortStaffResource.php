<?php

namespace App\Http\Resources\Staff;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ShortStaffResource extends JsonResource
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
            'snils' => $this->snils,
            'inn' => $this->inn,
            'full_name' => $this->full_name,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'job_title' => $this->job_title,
            'tel' => $this->tel,
            'division_id' => $this->division_id,
            'cert_valid_to' => Carbon::createFromTimestampMs($this->certification->valid_to)->format("d.m.Y"),
            'has_valid' => $this->certification->is_valid,
            'has_request_new' => $this->certification->is_request_new
        ];
    }
}

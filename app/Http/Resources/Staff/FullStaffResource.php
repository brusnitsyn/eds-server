<?php

namespace App\Http\Resources\Staff;

use App\Http\Resources\Certification\ShortCertificationResource;
use App\Http\Resources\StaffIntegrate\StaffIntegrateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FullStaffResource extends JsonResource
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
            'mis_user_id' => $this->mis_user_id,
            'mis_guid' => $this->mis_guid,
            'mis_sync_at' => $this->mis_sync_at,
            'cert' => ShortCertificationResource::make($this->certification),
            'integrations' => StaffIntegrateResource::collection($this->integrations),
        ];
    }
}

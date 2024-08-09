<?php

namespace App\Http\Requests\Staff;

use App\Facades\StaffFacade;
use App\Models\Staff;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cert' => ['array'],
            'cert.serial_number' => ['nullable', 'string'],
            'cert.valid_from' => ['nullable', 'integer'],
            'cert.valid_to' => ['nullable', 'integer'],
            'division_id' => ['nullable', 'integer'],
            'dob' => ['nullable', 'integer'],
            'first_name' => ['nullable', 'string'],
            'full_name' => ['nullable', 'string'],
            'gender' => ['nullable', 'string'],
            'id' => ['nullable', 'integer'],
            'inn' => ['nullable', 'string'],
            'job_title' => ['nullable', 'string'],
            'last_name' => ['nullable', 'string'],
            'middle_name' => ['nullable', 'string'],
            'snils' => ['nullable', 'string'],
            'tel' => ['nullable', 'integer'],
        ];
    }

    public function update(Staff $staff)
    {
        return StaffFacade::update($staff, $this->validated());
    }
}

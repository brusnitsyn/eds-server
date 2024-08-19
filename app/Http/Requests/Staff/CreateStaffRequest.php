<?php

namespace App\Http\Requests\Staff;

use App\Facades\StaffFacade;
use Illuminate\Foundation\Http\FormRequest;

class CreateStaffRequest extends FormRequest
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
            'cert' => ['required', 'array'],
            'cert.serial_number' => ['required', 'string'],
            'cert.valid_from' => ['required', 'integer'],
            'cert.valid_to' => ['required', 'integer'],
            'snils' => ['required', 'string'],
            'inn' => ['required', 'string'],
            'first_name' => ['required', 'string'],
            'middle_name' => ['nullable', 'string'],
            'last_name' => ['required', 'string'],
            'dob' => ['nullable', 'integer'],
            'gender' => ['required'],
            'job_title' => ['required', 'string'],
            'tel' => ['nullable', 'string'],
            'division_id' => ['nullable']
        ];
    }

    public function store()
    {
        return StaffFacade::create($this->validated());
    }
}

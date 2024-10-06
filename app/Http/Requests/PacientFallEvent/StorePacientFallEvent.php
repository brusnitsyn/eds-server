<?php

namespace App\Http\Requests\PacientFallEvent;

use App\Models\PacientFallEvent;
use Illuminate\Foundation\Http\FormRequest;

class StorePacientFallEvent extends FormRequest
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
            'full_name' => ['required', 'string'],
            'reason' => ['required', 'string'],
            'place' => ['required', 'string'],
            'held_event' => ['nullable', 'string'],
            'consequence' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'division_id' => ['required', 'numeric'],
        ];
    }

    public function store()
    {
        return PacientFallEvent::create($this->validated());
    }
}

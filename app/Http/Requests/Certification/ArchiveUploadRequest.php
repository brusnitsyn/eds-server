<?php

namespace App\Http\Requests\Certification;

use App\Facades\CertificateFacade;
use App\Facades\StaffFacade;
use Illuminate\Foundation\Http\FormRequest;

class ArchiveUploadRequest extends FormRequest
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
            'archive' => ['required', 'mimes:zip'],
            'is_package' => ['required', 'boolean']
        ];
    }

    public function upload()
    {
        return StaffFacade::create($this->validated());
    }
}

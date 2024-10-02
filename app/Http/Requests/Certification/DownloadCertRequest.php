<?php

namespace App\Http\Requests\Certification;

use App\Facades\CertificateFacade;
use Illuminate\Foundation\Http\FormRequest;

class DownloadCertRequest extends FormRequest
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
            'staff_ids' => ['required', 'array'],
        ];
    }

    public function download()
    {
        return CertificateFacade::download($this->validated('staff_ids'));
    }
}

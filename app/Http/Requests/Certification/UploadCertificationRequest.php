<?php

namespace App\Http\Requests\Certification;

use App\Facades\CertificateFacade;
use Illuminate\Foundation\Http\FormRequest;

class UploadCertificationRequest extends FormRequest
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
            'certificate' => ['required', 'file']
        ];
    }

    public function readData()
    {
        $data = $this->validated('certificate');
        return CertificateFacade::readPropsCert($data);
    }
}

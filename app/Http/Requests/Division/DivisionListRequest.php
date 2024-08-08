<?php

namespace App\Http\Requests\Division;

use App\Facades\DivisionFacade;
use Illuminate\Foundation\Http\FormRequest;

class DivisionListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function resolve()
    {
        return DivisionFacade::list($this->query());
    }
}

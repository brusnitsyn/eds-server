<?php

namespace App\Http\Requests\Auth;

use App\Facades\AuthFacade;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function login(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'token' => AuthFacade::login(
                $this->validated(),
            ),
            'user' => Auth::user(),
        ]);
    }
}

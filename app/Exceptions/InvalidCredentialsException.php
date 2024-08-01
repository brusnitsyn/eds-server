<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvalidCredentialsException extends Exception
{
    protected $message = "invalid_credentials";

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => __("auth.{$this->getMessage()}")
        ], 401);
    }
}

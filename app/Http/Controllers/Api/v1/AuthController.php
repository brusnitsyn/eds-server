<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        return $request->authenticate();
//        return response()->json([
//            'user' => $user,
//            'token' => $user->createToken('default')->plainTextToken,
//        ]);
    }

    public function currentUser(): array
    {
        return UserResource::make(Auth::user())->resolve();
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create($data);

        Auth::login($user);

        return '';
    }
}

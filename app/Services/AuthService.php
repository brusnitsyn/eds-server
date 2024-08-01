<?php

namespace App\Services;

use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $data = array_merge($data, array('rule_id' => $data['rule']['id']));

        $user = User::query()
            ->create($data);

        return response()->json([
            'message' => 'Учетная запись успешно создана'
        ])->setStatusCode(201);
    }

    public function login(array $data) {

        if (!Auth::attempt($data)) {
            throw new InvalidCredentialsException();
        }

        return Auth::user()->createToken('default')->plainTextToken;
    }
}

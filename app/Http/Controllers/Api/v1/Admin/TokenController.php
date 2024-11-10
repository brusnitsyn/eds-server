<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PersonalAccessToken\StorePersonalAccessTokenRequest;
use App\Http\Resources\PersonalAccessToken\PersonalAccessTokenResource;
use App\Models\User;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function get()
    {
        return PersonalAccessTokenResource::collection(auth()->user()->tokens);
    }

    public function create(User $user, StorePersonalAccessTokenRequest $request)
    {
        $token = $user->createToken(
            $request->validated('name', 'default'),
            $request->validated('abilities'),
            $request->validated('expiresAt')
        );

        return $token;
    }

    public function update()
    {

    }

    public function delete()
    {

    }
}

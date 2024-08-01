<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static create(array $data)
 * @method static login(array $data)
 *
 * @see \App\Services\AuthService
 */
class AuthFacade extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return 'auth.facade';
    }
}

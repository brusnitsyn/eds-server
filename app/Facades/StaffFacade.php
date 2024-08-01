<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static create(array $data)
 * @method static get(array $data)
 *
 * @see \App\Services\AuthService
 */
class StaffFacade extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return 'staff.facade';
    }
}

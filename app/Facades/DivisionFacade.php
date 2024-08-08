<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static create(array $data)
 * @method static get(array $data)
 * @method static list(array $data)
 *
 * @see \App\Services\DivisionService
 */
class DivisionFacade extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return 'division.facade';
    }
}

<?php

namespace App\Facades;

use App\Models\Staff;
use Illuminate\Support\Facades\Facade;

/**
 * @method static create(array $data)
 * @method static get(array $data)
 * @method static update(Staff $staff, array $data)
 *
 * @see \App\Services\StaffService
 */
class StaffFacade extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return 'staff.facade';
    }
}

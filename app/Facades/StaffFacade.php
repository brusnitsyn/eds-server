<?php

namespace App\Facades;

use App\Models\Staff;
use App\Models\StaffIntegrate;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Facade;

/**
 * @method static create(array $data)
 * @method static get(array $data)
 * @method static update(Staff $staff, array $data)
 * @method static delete(Staff $staff)
 * @method static createIntegrate(Staff $staff, array $data)
 * @method static updateIntegrate(StaffIntegrate $integrate, array $data)
 * @method static insertCertToMis(Staff $staff)
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

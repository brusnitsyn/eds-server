<?php

namespace App\Http\Controllers\Api\v1;

use App\Facades\StaffFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\StaffIntegrate\CreateStaffIntegrateRequest;
use App\Http\Requests\StaffIntegrate\UpdateStaffIntegrateRequest;
use App\Models\Staff;
use App\Models\StaffIntegrate;

class StaffIntegratedController extends Controller
{
    public function index() {}
    public function create(Staff $staff, CreateStaffIntegrateRequest $request)
    {
        return StaffFacade::createIntegrate($staff, $request->validated());
    }

    public function update(StaffIntegrate $staffIntegrate, UpdateStaffIntegrateRequest $request)
    {
        return StaffFacade::updateIntegrate($staffIntegrate, $request->validated());
    }

    public function delete(Staff $staff, StaffIntegrate $staffIntegrate)
    {
        $hasDeleted = $staffIntegrate->delete();
        return $hasDeleted;
    }
}

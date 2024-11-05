<?php

namespace App\Http\Controllers\Api\v1;

use App\Facades\StaffFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\StaffIntegrated\CreateStaffIntegratedRequest;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffIntegratedController extends Controller
{
    public function index() {}
    public function create(Staff $staff, CreateStaffIntegratedRequest $request)
    {
        return StaffFacade::createIntegrated($staff, $request->validated());
    }
}

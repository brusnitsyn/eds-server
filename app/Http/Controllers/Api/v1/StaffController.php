<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\CreateStaffRequest;
use App\Http\Resources\Staff\FullStaffResource;
use App\Http\Resources\Staff\ShortStaffCollection;
use App\Http\Resources\Staff\ShortStaffResource;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function all()
    {
        return new ShortStaffCollection(Staff::all());
    }

    public function create(CreateStaffRequest $request)
    {
        return $request->store();
    }

    public function get(Staff $staff)
    {
        return FullStaffResource::make($staff);
    }
}

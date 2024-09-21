<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\CreateStaffRequest;
use App\Http\Requests\Staff\UpdateStaffRequest;
use App\Http\Resources\Staff\FullStaffResource;
use App\Http\Resources\Staff\ShortStaffCollection;
use App\Http\Resources\Staff\ShortStaffResource;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function all()
    {
        $staff = Staff::paginate(30);
        return new ShortStaffCollection($staff);
    }

    public function create(CreateStaffRequest $request)
    {
        return $request->store();
    }

    public function get(Staff $staff)
    {
        return FullStaffResource::make($staff);
    }

    public function update(Staff $staff, UpdateStaffRequest $request)
    {
        return $request->update($staff);
    }
}

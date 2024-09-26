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
use Illuminate\Support\Collection;

class StaffController extends Controller
{
    public function all(Request $request)
    {
        $query = Staff::query();
        $searchColumn = $request->query('search_from');
        $searchValue = $request->query('search_value');
        $validType = $request->query('valid_type');

        if (!is_null($validType)) {
            switch ($validType) {
                case 'no-valid':
                    $query->whereHas('certification', function ($query) {
                        $query->where('is_valid', false);
                    });
                    break;
                case 'new-request':
                    $query->whereHas('certification', function ($query) {
                        $query->where('is_request_new', true);
                    });
                    break;
            }
        }

        if (!is_null($searchColumn) && !is_null($searchValue)) {
            $query->where($searchColumn, 'ilike', $searchValue . '%');
        }

        $query->orderBy('full_name', 'ASC');

        $staff = $query->paginate(30);

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

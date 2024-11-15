<?php

namespace App\Http\Controllers\Api\v1;

use App\Exports\StaffExport;
use App\Facades\StaffFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\CreateStaffRequest;
use App\Http\Requests\Staff\UpdateStaffRequest;
use App\Http\Resources\Staff\FullStaffResource;
use App\Http\Resources\Staff\ShortStaffCollection;
use App\Http\Resources\Staff\ShortStaffResource;
use App\Models\Staff;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StaffController extends Controller
{
    public function all(Request $request)
    {
        $query = Staff::query();

        $searchColumn = $request->query('search_from');
        $searchValue = $request->query('search_value');
        $validType = $request->query('valid_type');

        $sortColumn = $request->query('sort_column', 'full_name');
        $sortDirection = $request->query('sort_direction', 'asc');

        if ($sortDirection === 'descend') $sortDirection = 'desc';
        else if ($sortDirection === 'ascend') $sortDirection = 'asc';
        else {
            $sortColumn = 'full_name';
            $sortDirection = 'asc';
        }

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

        switch ($sortColumn) {
            case 'certificate.valid_to':
                $query->leftJoin('certifications', function (JoinClause $join) {
                    $join->on('staff.id', '=', 'certifications.staff_id');
                })->orderBy('certifications.valid_to', $sortDirection);
                break;
                default: $query->orderBy($sortColumn, $sortDirection);
        }


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

    public function delete(Staff $staff)
    {
        $hasStaffDeleted = $staff->delete();
        return $hasStaffDeleted;
    }

    public function syncMis(Staff $staff)
    {
        $misUser = DB::connection('mis')->table('dbo.x_User')->where('FIO', $staff->full_name)->first();
        if ($misUser)
        {
            $staff->integrations()->updateOrCreate(['name' => 'ТМ:МИС'], ['name' => 'ТМ:МИС', 'login' => $misUser->GeneralLogin]);
        }
    }

    public function insertCertMis(Staff $staff)
    {
        return StaffFacade::insertCertToMis($staff);
    }

    public function updateCertSetting(Staff $staff)
    {

    }

    public function importExcel(Request $request)
    {
        $validType = $request->query('valid_type');
        return Excel::download(new StaffExport($validType ?? 'valid'), 'staffs.xlsx');
    }
}

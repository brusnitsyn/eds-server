<?php

namespace App\Services;

use App\Exceptions\InvalidCredentialsException;
use App\Models\Certification;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffService
{
    public function create(array $data): \Illuminate\Http\JsonResponse
    {
        $cert = $data['cert'];
        $data['full_name'] = "{$data['last_name']} {$data['first_name']} {$data['middle_name']}";
        $data['job_title'] = ucfirst(strtolower($data['job_title']));
        $createdStaff = Staff::create($data);

        $cert['staff_id'] = $createdStaff->id;

        $createdCert = Certification::create($cert);

        return response()->json([
           'message' => 'Персона создана!'
        ])->setStatusCode(201);
    }

    public function get(array $data)
    {

    }
}

<?php

namespace App\Http\Controllers\Api\ACI;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ACIController extends Controller
{
    public function staff()
    {
        $staff = Staff::all();
        $encode = Crypt::encryptString($staff);

        return $encode;
    }
}

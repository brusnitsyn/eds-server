<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test()
    {
        $staff = Staff::first();
        return DB::connection('mis')->table('dbo.x_User')->where('FIO', $staff->full_name)->get();
    }
}

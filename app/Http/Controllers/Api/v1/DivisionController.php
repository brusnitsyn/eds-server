<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Division\DivisionCollection;
use App\Http\Resources\Division\DivisionResource;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function all()
    {
        return DivisionResource::collection(Division::all());
    }
}

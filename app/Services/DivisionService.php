<?php

namespace App\Services;

use App\Http\Resources\Division\DivisionCollection;
use App\Models\Division;

class DivisionService
{
    public function create(array $data)
    {

    }

    public function get(array $data)
    {

    }

    public function list(array $queries)
    {
        return new DivisionCollection(Division::all());
    }
}

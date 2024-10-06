<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PacientFallEvent\StorePacientFallEvent;
use App\Http\Resources\PacientFallEvent\PacientFallEventCollection;
use App\Models\PacientFallEvent;
use Illuminate\Http\Request;

class PacientFallEventController extends Controller
{
    public function all(Request $request)
    {
        $query = PacientFallEvent::query();

        $searchColumn = $request->query('search_from');
        $searchValue = $request->query('search_value');

        if (!is_null($searchColumn) && !is_null($searchValue)) {
            $query->where($searchColumn, 'ilike', $searchValue . '%');
        }

        $query->orderBy('full_name', 'ASC');

        $pacientFallEvents = $query->paginate(30);

        return new PacientFallEventCollection($pacientFallEvents);
    }

    public function store(StorePacientFallEvent $request)
    {
        return $request->store();
    }
}

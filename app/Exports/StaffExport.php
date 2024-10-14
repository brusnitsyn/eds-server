<?php

namespace App\Exports;

use App\Models\Staff;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class StaffExport implements FromView, ShouldAutoSize
{
    private string $validType;

    public function __construct(string $validType)
    {
        $this->validType = $validType;
    }

    public function view(): View
    {
        $query = Staff::query();

        switch ($this->validType) {
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
            case 'valid':
                break;
        }

        $staffs = $query->get();

        return view('exports.staff', [
            'staffs' => $staffs
        ]);
    }
}

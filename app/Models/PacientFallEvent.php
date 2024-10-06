<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PacientFallEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'reason',
        'place',
        'held_event',
        'consequence',
        'date',
        'division_id'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}

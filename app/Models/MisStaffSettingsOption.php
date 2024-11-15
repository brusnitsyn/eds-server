<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MisStaffSettingsOption extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'key',
        'property',
        'setting_type',
        'label',
        'deleted_at'
    ];


}

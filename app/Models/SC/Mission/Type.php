<?php

namespace App\Models\SC\Mission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $table = 'sc_mission_types';

    protected $fillable = [
        'uuid',
        'name',
    ];
}

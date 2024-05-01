<?php

namespace App\Models\SC\Mission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deadline extends Model
{
    use HasFactory;

    protected $table = 'sc_mission_deadlines';

    protected $fillable = [
        'mission_id',
        'mission_completion_time',
        'mission_auto_end',
        'mission_result_after_timer_end',
        'mission_end_reason',
    ];

    protected $casts = [
        'mission_completion_time' => 'double',
        'mission_auto_end' => 'boolean',
    ];
}

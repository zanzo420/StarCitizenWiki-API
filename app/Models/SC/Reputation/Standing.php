<?php

namespace App\Models\SC\Reputation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standing extends Model
{
    use HasFactory;

    protected $table = 'sc_reputation_standings';

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'display_name',
        'perk_description',
        'min_reputation',
        'drift_reputation',
        'drift_time_hours',
        'gated',
    ];
}

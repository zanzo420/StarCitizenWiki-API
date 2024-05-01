<?php

namespace App\Models\SC\Mission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $table = 'sc_mission_rewards';

    protected $fillable = [
        'mission_id',
        'amount',
        'max',
        'plus_bonuses',
        'currency',
        'reputation_bonus',
    ];

    protected $casts = [
        'amount' => 'double',
        'max' => 'double',
        'plus_bonuses' => 'boolean',
    ];
}

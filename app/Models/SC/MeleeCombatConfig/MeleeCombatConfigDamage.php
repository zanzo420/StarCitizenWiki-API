<?php

namespace App\Models\SC\MeleeCombatConfig;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeleeCombatConfigDamage extends Model
{
    use HasFactory;

    protected $table = 'sc_melee_combat_damages';

    protected $fillable = [
        'combat_config_id',
        'type',
        'name',
        'damage',
    ];

    protected $casts = [
        'damage' => 'double',
    ];
}

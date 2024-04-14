<?php

namespace App\Models\SC\MeleeCombatConfig;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MeleeCombatConfig extends Model
{
    use HasFactory;

    protected $table = 'sc_melee_combat_configs';

    protected $fillable = [
        'uuid',
        'category',
        'stun_recovery_modifier',
        'block_stun_reduction_modifier',
        'block_stun_stamina_modifier',
        'attack_impulse',
        'ignore_body_part_impulse_scale',
        'fullbody_animation',
    ];

    protected $casts = [
        'stun_recovery_modifier' => 'double',
        'block_stun_reduction_modifier' => 'double',
        'block_stun_stamina_modifier' => 'double',
        'attack_impulse' => 'double',
        'ignore_body_part_impulse_scale' => 'boolean',
        'fullbody_animation' => 'boolean',
    ];

    public function damages(): HasMany
    {
        return $this->hasMany(MeleeCombatConfigDamage::class, 'combat_config_id');
    }

    public function getDamageAttribute(): float
    {
        return $this->damages->reduce(function ($carry, $item) {
            return $carry + $item->damage;
        }, 0);
    }
}

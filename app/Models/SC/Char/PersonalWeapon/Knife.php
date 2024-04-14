<?php

declare(strict_types=1);

namespace App\Models\SC\Char\PersonalWeapon;

use App\Models\SC\CommodityItem;
use App\Models\SC\MeleeCombatConfig\MeleeCombatConfig;
use App\Traits\HasDescriptionDataTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Knife extends CommodityItem
{
    use HasDescriptionDataTrait;

    protected $table = 'sc_item_knifes';

    protected $with = [
        'combatConfig',
    ];

    protected $fillable = [
        'item_uuid',
        'can_be_used_for_take_down',
        'can_block',
        'can_be_used_in_prone',
        'can_dodge',
        'melee_combat_config_uuid',
    ];

    protected $casts = [
        'can_be_used_for_take_down' => 'boolean',
        'can_block' => 'boolean',
        'can_be_used_in_prone' => 'boolean',
        'can_dodge' => 'boolean',
    ];

    public function combatConfig(): HasMany
    {
        return $this->hasMany(MeleeCombatConfig::class, 'uuid', 'melee_combat_config_uuid');
    }

    public function getDamageAttribute(): float
    {
        return $this->combatConfig->reduce(function ($carry, MeleeCombatConfig $combatConfig) {
            return $carry + $combatConfig->damage;
        }, 0);
    }
}

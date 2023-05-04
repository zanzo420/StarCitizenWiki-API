<?php

declare(strict_types=1);

namespace App\Models\SC\Char\PersonalWeapon;

use App\Models\SC\CommodityItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonalWeaponMagazine extends CommodityItem
{
    use HasFactory;

    protected $table = 'sc_personal_weapon_magazines';

    protected $fillable = [
        'weapon_id',
        'initial_ammo_count',
        'max_ammo_count',
    ];

    protected $casts = [
        'initial_ammo_count' => 'double',
        'max_ammo_count' => 'double',
    ];
}

<?php

declare(strict_types=1);

namespace App\Models\SC\Vehicle;

use App\Models\SC\Item\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class VehicleItem extends Item
{
    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(
            'type',
            static function (Builder $builder) {
                $builder->where('name', '<>', '<= PLACEHOLDER =>')
                    ->where('class_name', 'NOT LIKE', '%test%')
                    ->where('class_name', 'NOT LIKE', '%lowpoly%')
                    ->where('class_name', 'NOT LIKE', '%dummy%')
                    ->where('class_name', 'NOT LIKE', '%_mm')
                    ->where('class_name', 'NOT LIKE', '%s%_idris_m')
                    ->where('class_name', 'NOT LIKE', '%s%_turret')
                    ->where('class_name', 'NOT LIKE', 'mrck_s05_orig_%') //MSD-543 Missile Rack
                    ->where('class_name', 'NOT LIKE', 'mrck_s05_behr_quad_s03_a') //MSD-543 Missile Rack
                    ->whereIn('type', [
                        'Arm',
                        'Battery',
                        'BombLauncher',
                        'Cooler',
                        'EMP',
                        'ExternalFuelTank',
                        'FlightController',
                        'FuelIntake',
                        'FuelTank',
                        'MainThruster',
                        'ManneuverThruster',
                        'MiningArm',
                        'Missile',
                        'MissileLauncher',
                        'Mount',
                        'Paints',
                        'PowerPlant',
                        'QuantumDrive',
                        'QuantumFuelTank',
                        'QuantumInterdictionGenerator',
                        'Radar',
                        'SalvageModifier',
                        'SelfDestruct',
                        'Shield',
                        'ToolArm',
                        'TowingBeam',
                        'TractorBeam',
                        'Turret',
                        'Turret',
                        'TurretBase',
                        'UtilityTurret',
                        'WeaponDefensive',
                        'WeaponGun',
                        'WeaponMount',
                        'WeaponMining',
                        'WheeledController',
                    ]);
            }
        );
    }

    public function ports(): HasMany
    {
        return parent::ports()
            ->where('name', 'NOT LIKE', '%access%')
            ->where('name', 'NOT LIKE', '%hud%');
    }

    public function getGradeAttribute()
    {
        return $this->getDescriptionDatum('Grade');
    }

    public function getClassAttribute()
    {
        return $this->getDescriptionDatum('Class');
    }

    public function getItemTypeAttribute()
    {
        return implode(' ', Str::ucsplit($this->type));
    }
}

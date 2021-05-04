<?php

declare(strict_types=1);

namespace App\Jobs\StarCitizenUnpacked\Import;

use App\Models\StarCitizenUnpacked\Item;
use App\Models\StarCitizenUnpacked\ShipItem\QuantumDrive\QuantumDrive;
use App\Models\StarCitizenUnpacked\ShipItem\Shield\Shield;
use App\Models\StarCitizenUnpacked\ShipItem\ShipItem as ShipItemModel;
use App\Models\StarCitizenUnpacked\ShipItem\Weapon\Weapon;
use App\Models\StarCitizenUnpacked\ShipItem\Weapon\WeaponMode;
use App\Services\Parser\StarCitizenUnpacked\ShipItems\ShipItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ShipItems implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private array $supportedClasses = [
        'Ship.Cooler',
        'Ship.PowerPlant.Power',
        'Ship.Shield',
        'Ship.QuantumDrive',

        'Ship.Weapon.Gun',
        'Ship.Weapon.Rocket',
        'Ship.Weapon.NoseMounted',
    ];

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $items = new ShipItem();
        } catch (\JsonException | FileNotFoundException $e) {
            $this->fail($e->getMessage());

            return;
        }

        $items->getData()
            ->filter(function (array $item) {
                return in_array($item['item_class'], $this->supportedClasses, true);
            })
            ->each(function ($item) {
                if (!Item::query()->where('uuid', $item['uuid'])->exists()) {
                    return;
                }

                $shipItem = ShipItemModel::updateOrCreate([
                    'uuid' => $item['uuid'],
                ], [
                    'grade' => $item['grade'],
                    'class' => $item['class'],
                    'type' => $item['type'],
                    'version' => config('api.sc_data_version'),
                ]);

                $this->createPowerDataModel($item, $shipItem);
                $this->createHeatDataModel($item, $shipItem);
                $this->createDistortionDataModel($item, $shipItem);
                $this->createDurabilityDataModel($item, $shipItem);

                $this->createModel($item, $shipItem);

                $shipItem->translations()->updateOrCreate([
                    'locale_code' => 'en_EN',
                ], [
                    'translation' => $item['description'] ?? '',
                ]);
            });
    }

    private function createPowerDataModel(array $item, ShipItemModel $model): void
    {
        if (!isset($item['power']) || $item['power'] === null) {
            return;
        }

        $model->powerData()->updateOrCreate([
            'ship_item_id' => $model->id,
        ], [
            'power_base' => $item['power']['power_base'] ?? null,
            'power_draw' => $item['power']['power_draw'] ?? null,
            'throttleable' => $item['power']['throttleable'] ?? null,
            'overclockable' => $item['power']['overclockable'] ?? null,
            'overclock_threshold_min' => $item['power']['overclock_threshold_min'] ?? null,
            'overclock_threshold_max' => $item['power']['overclock_threshold_max'] ?? null,
            'overclock_performance' => $item['power']['overclock_performance'] ?? null,
            'overpower_performance' => $item['power']['overpower_performance'] ?? null,
            'power_to_em' => $item['power']['power_to_em'] ?? null,
            'decay_rate_em' => $item['power']['decay_rate_em'] ?? null,
        ]);
    }

    private function createHeatDataModel(array $item, ShipItemModel $model): void
    {
        if (!isset($item['heat']) || $item['heat'] === null) {
            return;
        }

        $model->heatData()->updateOrCreate([
            'ship_item_id' => $model->id,
        ], [
            'temperature_to_ir' => $item['heat']['temperature_to_ir'] ?? null,
            'overpower_heat' => $item['heat']['overpower_heat'] ?? null,
            'overclock_threshold_min' => $item['heat']['overclock_threshold_min'] ?? null,
            'overclock_threshold_max' => $item['heat']['overclock_threshold_max'] ?? null,
            'thermal_energy_base' => $item['heat']['thermal_energy_base'] ?? null,
            'thermal_energy_draw' => $item['heat']['thermal_energy_draw'] ?? null,
            'thermal_conductivity' => $item['heat']['thermal_conductivity'] ?? null,
            'specific_heat_capacity' => $item['heat']['specific_heat_capacity'] ?? null,
            'mass' => $item['heat']['mass'] ?? null,
            'surface_area' => $item['heat']['surface_area'] ?? null,
            'start_cooling_temperature' => $item['heat']['start_cooling_temperature'] ?? null,
            'max_cooling_rate' => $item['heat']['max_cooling_rate'] ?? null,
            'max_temperature' => $item['heat']['max_temperature'] ?? null,
            'min_temperature' => $item['heat']['min_temperature'] ?? null,
            'overheat_temperature' => $item['heat']['overheat_temperature'] ?? null,
            'recovery_temperature' => $item['heat']['recovery_temperature'] ?? null,
            'misfire_min_temperature' => $item['heat']['misfire_min_temperature'] ?? null,
            'misfire_max_temperature' => $item['heat']['misfire_max_temperature'] ?? null,
        ]);
    }

    private function createDistortionDataModel(array $item, ShipItemModel $model): void
    {
        if (!isset($item['distortion']) || $item['distortion'] === null) {
            return;
        }

        $model->distortionData()->updateOrCreate([
            'ship_item_id' => $model->id,
        ], [
            'decay_rate' => $item['distortion']['decay_rate'] ?? null,
            'maximum' => $item['distortion']['maximum'] ?? null,
            'overload_ratio' => $item['distortion']['overload_ratio'] ?? null,
            'recovery_ratio' => $item['distortion']['recovery_ratio'] ?? null,
            'recovery_time' => $item['distortion']['recovery_time'] ?? null,
        ]);
    }

    private function createDurabilityDataModel(array $item, ShipItemModel $model): void
    {
        if (!isset($item['durability']) || $item['durability'] === null) {
            return;
        }

        $model->durabilityData()->updateOrCreate([
            'ship_item_id' => $model->id,
        ], [
            'health' => $item['durability']['health'] ?? null,
            'max_lifetime' => $item['durability']['max_lifetime'] ?? null,
        ]);
    }

    private function createModel(array $item, ShipItemModel $shipItem): ?Model
    {
        switch ($item['item_class']) {
            case 'Ship.Cooler':
                return $this->createCooler($item, $shipItem);

            case 'Ship.PowerPlant.Power':
                return $this->createPowerPlant($item, $shipItem);

            case 'Ship.Shield':
                return $this->createShield($item, $shipItem);

            case 'Ship.QuantumDrive':
                return $this->createQuantumDrive($item, $shipItem);

            case 'Ship.Weapon.Rocket':
            case 'Ship.Weapon.Gun':
            case 'Ship.Weapon.NoseMounted':
                return $this->createWeapon($item, $shipItem);

            default:
                return null;
        }
    }

    private function createCooler(array $item, ShipItemModel $shipItem): Model
    {
        return $shipItem->itemSpecification()->updateOrCreate([
            'uuid' => $item['uuid'],
        ], [
            'cooling_rate' => $item['cooler']['cooling_rate'],
            'suppression_ir_factor' => $item['cooler']['suppression_ir_factor'],
            'suppression_heat_factor' => $item['cooler']['suppression_heat_factor'],
            'ship_item_id' => $shipItem->id,
        ]);
    }

    private function createPowerPlant(array $item, ShipItemModel $shipItem): Model
    {
        return $shipItem->itemSpecification()->updateOrCreate([
            'uuid' => $item['uuid'],
        ], [
            'power_output' => $item['power_plant']['power_output'],
            'ship_item_id' => $shipItem->id,
        ]);
    }

    private function createShield(array $item, ShipItemModel $shipItem): Model
    {
        /** @var Shield $shield */
        $shield = $shipItem->itemSpecification()->updateOrCreate([
            'uuid' => $item['uuid'],
        ], [
            'max_shield_health' => $item['shield']['max_shield_health'],
            'max_shield_regen' => $item['shield']['max_shield_regen'],
            'decay_ratio' => $item['shield']['decay_ratio'],
            'downed_regen_delay' => $item['shield']['downed_regen_delay'],
            'damage_regen_delay' => $item['shield']['damage_regen_delay'],
            'max_reallocation' => $item['shield']['max_reallocation'],
            'reallocation_rate' => $item['shield']['reallocation_rate'],
            'shield_hardening_factor' => $item['shield']['shield_hardening_factor'],
            'shield_hardening_duration' => $item['shield']['shield_hardening_duration'],
            'shield_hardening_cooldown' => $item['shield']['shield_hardening_cooldown'],
            'ship_item_id' => $shipItem->id,
        ]);

        foreach ($item['shield']['absorptions'] as $type => $absorption) {
            $shield->absorptions()->updateOrCreate([
                'ship_shield_id' => $shield->id,
                'type' => $type
            ], [
                'min' => $absorption['min'],
                'max' => $absorption['max'],
            ]);
        }

        return $shield;
    }

    private function createQuantumDrive(array $item, ShipItemModel $shipItem): Model
    {
        /** @var QuantumDrive $drive */
        $drive = $shipItem->itemSpecification()->updateOrCreate([
            'uuid' => $item['uuid'],
        ], [
            'quantum_fuel_requirement' => $item['quantum_drive']['quantum_fuel_requirement'],
            'jump_range' => $item['quantum_drive']['jump_range'],
            'disconnect_range' => $item['quantum_drive']['disconnect_range'],
            'pre_ramp_up_thermal_energy_draw' => $item['quantum_drive']['pre_ramp_up_thermal_energy_draw'],
            'ramp_up_thermal_energy_draw' => $item['quantum_drive']['ramp_up_thermal_energy_draw'],
            'in_flight_thermal_energy_draw' => $item['quantum_drive']['in_flight_thermal_energy_draw'],
            'ramp_down_thermal_energy_draw' => $item['quantum_drive']['ramp_down_thermal_energy_draw'],
            'post_ramp_down_thermal_energy_draw' => $item['quantum_drive']['post_ramp_down_thermal_energy_draw'],
            'ship_item_id' => $shipItem->id,
        ]);

        foreach ($item['quantum_drive']['modes'] as $type => $mode) {
            $drive->modes()->updateOrCreate([
                'type' => $type,
            ], [
                'drive_speed' => $mode['drive_speed'],
                'cooldown_time' => $mode['cooldown_time'] ?? 0,
                'stage_one_accel_rate' => $mode['stage_one_accel_rate'],
                'stage_two_accel_rate' => $mode['stage_two_accel_rate'],
                'engage_speed' => $mode['engage_speed'],
                'interdiction_effect_time' => $mode['interdiction_effect_time'],
                'calibration_rate' => $mode['calibration_rate'],
                'min_calibration_requirement' => $mode['min_calibration_requirement'],
                'max_calibration_requirement' => $mode['max_calibration_requirement'],
                'calibration_process_angle_limit' => $mode['calibration_process_angle_limit'],
                'calibration_warning_angle_limit' => $mode['calibration_warning_angle_limit'],
                'spool_up_time' => $mode['spool_up_time'],
            ]);
        }

        return $drive;
    }

    private function createWeapon(array $item, ShipItemModel $shipItem): ?Model
    {
        if (!isset($item['weapon'])) {
            return null;
        }

        /** @var Weapon $weapon */
        $weapon = $shipItem->itemSpecification()->updateOrCreate([
            'uuid' => $item['uuid'],
        ], [
            'speed' => $item['weapon']['speed'],
            'range' => $item['weapon']['range'],
            'size' => $item['weapon']['size'],
            'capacity' => $item['weapon']['capacity'],

            'ship_item_id' => $shipItem->id,
        ]);

        foreach ($item['weapon']['damages'] as $type => $damage) {
            if (empty($damage)) {
                continue;
            }

            foreach ($damage as $name => $value) {
                $weapon->damages()->updateOrCreate([
                    'ship_weapon_id' => $weapon->id,
                    'type' => $type,
                    'name' => $name,
                ], [
                    'damage' => $value,
                ]);
            }
        }

        foreach ($item['weapon']['modes'] as $mode) {
            /** @var WeaponMode $mode */
            $weapon->modes()->updateOrCreate([
                'mode' => $mode['mode'],
            ], [
                'localised' => $mode['localised'],
                'type' => $mode['type'],
                'rounds_per_minute' => $mode['rounds_per_minute'],
                'ammo_per_shot' => $mode['ammo_per_shot'],
                'pellets_per_shot' => $mode['pellets_per_shot'],
            ]);
        }

        return $weapon;
    }
}
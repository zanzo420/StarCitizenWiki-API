<?php

declare(strict_types=1);

namespace App\Jobs\SC\Import;

use App\Models\SC\Char\PersonalWeapon\IronSight;
use App\Models\SC\Char\PersonalWeapon\PersonalWeaponMagazine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JsonException;

class WeaponAttachment extends AbstractItemCreationJob
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->loadLabels();
        try {
            $parser = new \App\Services\Parser\SC\WeaponAttachment($this->filePath, $this->labels);
        } catch (FileNotFoundException|JsonException $e) {
            $this->fail($e);

            return;
        }

        $item = $parser->getData();

        if ($item === null) {
            return;
        }

        if (! empty($item['ammo'])) {
            PersonalWeaponMagazine::query()->withoutGlobalScopes()->updateOrCreate([
                'item_uuid' => $item['uuid'],
            ], [
                'initial_ammo_count' => $item['ammo']['initial_ammo_count'] ?? null,
                'max_ammo_count' => $item['ammo']['max_ammo_count'] ?? null,
                'type' => $item['item_type'] ?? null,
                'ammunition_uuid' => $item['ammo']['ammunition_uuid'],
            ]);
        }

        if (! empty($item['iron_sight'])) {
            IronSight::query()->withoutGlobalScopes()->updateOrCreate([
                'item_uuid' => $item['uuid'],
            ], [
                'default_range' => $item['iron_sight']['default_range'],
                'max_range' => $item['iron_sight']['max_range'],
                'range_increment' => $item['iron_sight']['range_increment'],
                'auto_zeroing_time' => $item['iron_sight']['auto_zeroing_time'],
                'zoom_scale' => $item['iron_sight']['zoom_scale'],
                'zoom_time_scale' => $item['iron_sight']['zoom_time_scale'],
            ]);
        }
    }
}

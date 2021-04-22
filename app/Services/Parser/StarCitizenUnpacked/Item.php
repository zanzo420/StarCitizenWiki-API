<?php

declare(strict_types=1);

namespace App\Services\Parser\StarCitizenUnpacked;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use JsonException;

final class Item
{
    private Collection $item;
    private Collection $labels;
    private Collection $manufacturers;

    /**
     * AssaultRifle constructor.
     * @param string $fileName
     * @throws FileNotFoundException
     * @throws JsonException
     */
    public function __construct(string $fileName, Collection $labels, Collection $manufacturers)
    {
        $items = File::get(storage_path(sprintf('app/%s', $fileName)));
        $this->item = collect(json_decode($items, true, 512, JSON_THROW_ON_ERROR));
        $this->labels = $labels;
        $this->manufacturers = $manufacturers;
    }

    public function getData(): ?array
    {
        // phpcs:disable
        if (
        !isset(
            $this->item['Raw']['Entity']['__ref'],
            $this->item['Raw']['Entity']['Components']['SAttachableComponentParams']['AttachDef']['Localization']['Name'],
        )
        ) {
            return null;
        }

        $nameKey = substr(
            $this->item['Raw']['Entity']['Components']['SAttachableComponentParams']['AttachDef']['Localization']['Name'],
            1
        );
        // phpcs:enable

        if (!$this->labels->has($nameKey)) {
            return null;
        }

        $attach = $this->item['Raw']['Entity']['Components']['SAttachableComponentParams']['AttachDef'];
        $manufacturer = $this->manufacturers->get($attach['Manufacturer'], []);
        $manufacturer = $manufacturer['name'] ?? $manufacturer['code'] ?? 'Unknown Manufacturer';

        return [
            'uuid' => $this->item['Raw']['Entity']['__ref'],
            'name' => $this->cleanName($nameKey),
            'type' => $attach['Type'],
            'sub_type' => $attach['SubType'],
            'manufacturer' => $manufacturer,
            'size' => $attach['Size'],
        ];
    }

    private function cleanName(string $key): string
    {
        $name = trim(str_replace(' ', ' ', $this->labels->get($key)));
        return str_replace(['“', '”'], '"', $name);
    }
}
<?php

declare(strict_types=1);

namespace App\Jobs\SC\Import;

use App\Services\Parser\SC\Labels;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JsonException;

class HackingChip implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $labels = (new Labels())->getData();

        try {
            $parser = new \App\Services\Parser\SC\HackingChip($this->filePath, $labels);
        } catch (FileNotFoundException|JsonException $e) {
            $this->fail($e);

            return;
        }
        $item = $parser->getData();

        /** @var \App\Models\SC\ItemSpecification\HackingChip $model */
        \App\Models\SC\ItemSpecification\HackingChip::updateOrCreate([
            'item_uuid' => $item['uuid'],
        ], [
            'max_charges' => $item['max_charges'] ?? null,
            'duration_multiplier' => $item['duration_multiplier'] ?? null,
            'error_chance' => $item['error_chance'] ?? null,
        ]);
    }
}
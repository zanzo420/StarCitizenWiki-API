<?php

declare(strict_types=1);

namespace App\Console\Commands\StarCitizen\Starmap;

use App\Console\Commands\AbstractQueueCommand as QueueCommand;

class TranslateSystems extends QueueCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'starmap:translate-systems';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate all Starsystems using DeepL';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('Dispatching Systems Translation');

        dispatch(new \App\Jobs\StarCitizen\Starmap\Translate\TranslateSystems());

        return 0;
    }
}

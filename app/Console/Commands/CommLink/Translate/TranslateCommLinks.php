<?php declare(strict_types = 1);

namespace App\Console\Commands\CommLink\Translate;

use Illuminate\Console\Command;

class TranslateCommLinks extends Command
{
    const FIRST_COMM_LINK_ID = 12663;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:comm-links {offset=0 : Comm-Link start ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate all Comm-Links using DeepL';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Dispatching Comm-Link Translation');
        $offset = intval($this->argument('offset'));
        if ($offset > 0) {
            if ($offset < self::FIRST_COMM_LINK_ID) {
                $offset = self::FIRST_COMM_LINK_ID + $offset;
            }

            $this->info("Starting at Comm-Link ID {$offset}");
        }

        dispatch(new \App\Jobs\Rsi\CommLink\Translate\TranslateCommLinks($offset));
    }
}
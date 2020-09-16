<?php declare(strict_types=1);

namespace App\Console\Commands\Stat\Download;

use App\Jobs\Api\StarCitizen\Stat\DownloadStats as DownloadStatsJob;
use App\Jobs\Api\StarCitizen\Stat\Parser\ParseStat;
use Illuminate\Bus\Dispatcher;
use Illuminate\Console\Command;

/**
 * Class DownloadStats
 */
class DownloadStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:download {--i|import : Import stats after download}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download funding statistics and optionally import them';

    /**
     * @var Dispatcher
     */
    private Dispatcher $dispatcher;

    /**
     * Create a new command instance.
     *
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        parent::__construct();

        $this->dispatcher = $dispatcher;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        if ($this->option('import') === true) {
            $this->info('Downloading funding statistics and starting import');
            DownloadStatsJob::withChain(
                [
                    new ParseStat(),
                ]
            )->dispatch();
        } else {
            $this->info('Starting funding statistics download');
            $this->dispatcher->dispatchNow(new DownloadStatsJob());
        }

        return 0;
    }
}

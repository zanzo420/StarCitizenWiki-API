<?php

declare(strict_types=1);

namespace App\Console\Commands\SC\Wiki;

use App\Models\SC\Char\Clothing\Clothes;
use App\Traits\GetWikiCsrfTokenTrait;
use App\Traits\Jobs\CreateEnglishSubpageTrait;

class CreateClothingWikiPages extends CreateCharArmorWikiPages
{
    use CreateEnglishSubpageTrait;
    use GetWikiCsrfTokenTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sc:create-clothing-pages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create clothing as wikipages';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->withProgressBar(
            Clothes::all(),
            function (Clothes $armor) {
                $this->uploadWiki($armor, 'Automatische Erstellung von Kleidungs- und Rüstungsseiten');
            }
        );

        return 0;
    }
}

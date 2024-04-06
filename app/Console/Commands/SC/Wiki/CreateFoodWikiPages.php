<?php

declare(strict_types=1);

namespace App\Console\Commands\SC\Wiki;

use App\Models\SC\Food\Food;

class CreateFoodWikiPages extends AbstractCreateWikiPage
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sc:create-food-pages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create food wikipages';

    protected string $template = <<<'TEMPLATE'
Das Item '''<ITEM NAME>''' ist ein Lebensmittel<FOOD EFFECT>. Es wird hergestellt von [[{{subst:MFURN|<MANUFACTURER CODE>}}]].<ref name="ig3221">{{Cite game|build=[[Star Citizen Alpha 3.22.1|Alpha 3.22.1]]|accessdate=<CURDATE>}}</ref>
== Beschreibung ==
{{Item description}}
== Erwerb ==
{{Item availability}}
== Quellen ==
<references />
{{Navplate food and drinks}}
TEMPLATE;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->withProgressBar(
            Food::query()
                ->whereRelation('item', 'name', 'NOT LIKE', '%palceholder%')
                ->whereRelation('item', 'class_name', 'NOT LIKE', '%test%')
                ->get(),
            function (Food $food) {
                $this->uploadWiki($food, 'Automatische Erstellung von Lebensmitteln');
            }
        );

        return 0;
    }

    /**
     * @param  Food  $model
     */
    protected function prepareTemplate($model): string
    {
        $pageContent = $this->template;

        $effects = '';
        if ($model->nutritional_density_rating && $model->hydration_efficacy_index) {
            $effects = ', welches [[NDR|hunger]] und [[HEI|durst]] stillt';
        } elseif ($model->hydration_efficacy_index) {
            $effects = ', welches den [[HEI|durst]] stillt';
        } elseif ($model->nutritional_density_rating) {
            $effects = ', welches den [[NDR|hunger]] sättigt';
        }

        $pageContent = str_replace(
            '<FOOD EFFECT>',
            $effects,
            $pageContent
        );

        return $pageContent;
    }

    protected function getPageName($model): string
    {
        return $model->item->name;
    }

    protected function getManufacturerCode($model): string
    {
        return $model->item->manufacturer->code;
    }

    protected function getUUID($model): string
    {
        return $model->item->uuid;
    }
}

<?php

declare(strict_types=1);

namespace App\Console\Commands\SC\Wiki;

use App\Models\SC\Char\PersonalWeapon\Attachment;

class CreateWeaponAttachmentWikiPages extends AbstractCreateWikiPage
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sc:create-weapon-attachment-pages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create weapon attachments as wikipages';

    protected string $template = <<<'TEMPLATE'
Das Item '''<ITEM NAME>''' ist ein Größe <ITEM SIZE> <ITEM TYPE> hergestellt von [[{{subst:MFURN|<MANUFACTURER CODE>}}]].<ref name="<REFNAME>">{{Cite game|build=[[Star Citizen Alpha <REFVERSION>|Alpha <REFVERSION>]]|accessdate=<CURDATE>}}</ref>
== Beschreibung ==
{{Item description}}
== Erwerb ==
{{Item availability}}
{{Quellen}}
{{Navplate manufacturers|<MANUFACTURER CODE>}}
TEMPLATE;

    protected array $typeMapping = [
        'Ballistic Compensator' => 'ballistischer Kompensator',
        'Flash Hider' => 'Mündungsfeuerdämpfer',
        'Energy Stabilizer' => 'Energie-Stabilisator',
        'Suppressor' => 'Schalldämpfer',
        'Projection' => 'Projektionsvisier',
        'Reflex' => 'Reflexvisier',
        'Telescopic' => 'Zielfernrohr',
        'Monitor' => 'Monitorvisier',
        'Flashlight' => 'Taschenlampe',
        'Laser Pointer' => 'Laserpointer',

        // Raw Subtype
        'Magazine' => 'Magazin',
        'Utility' => 'Waffenaufsatz',
        'IronSight' => 'Zielfernrohr',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->withProgressBar(
            Attachment::query()
                ->where('sc_items.name', '<>', '<= PLACEHOLDER =>')
                ->whereIn('sub_type', [
                    'Magazine',
                    'Barrel',
                    'IronSight',
                    'Utility',
                    'BottomAttachment',
                ])
                ->get(),
            function (Attachment $item) {
                $this->uploadWiki($item, 'Automatische Erstellung von Waffenbefestigungen');
            }
        );

        return 0;
    }

    /**
     * @param  Attachment  $model
     */
    protected function prepareTemplate($model): string
    {
        $pageContent = $this->template;
        $type = ($this->typeMapping[$model->attachment_type] ?? $this->typeMapping[$model->sub_type] ?? $model->sub_type);

        if ($model->size === null) {
            $pageContent = str_replace(
                'Größe <ITEM SIZE> ',
                '',
                $pageContent
            );
        } else {
            $pageContent = str_replace(
                '<ITEM SIZE>',
                (string) ($model->getAttributes()['size'] ?? $model->size ?? 0),
                $pageContent
            );
        }

        $pageContent = str_replace(
            '<ITEM TYPE> ',
            $type.' ',
            $pageContent
        );

        $this->fixText($type, $pageContent);

        return $pageContent;
    }

    /**
     * @param  Attachment  $model
     */
    protected function getPageName($model): string
    {
        return $model->name;
    }

    /**
     * @param  Attachment  $model
     */
    protected function getManufacturerCode($model): string
    {
        return $model->manufacturer->code;
    }
}

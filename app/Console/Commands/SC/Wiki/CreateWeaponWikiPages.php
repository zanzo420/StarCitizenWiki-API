<?php

declare(strict_types=1);

namespace App\Console\Commands\SC\Wiki;

use App\Models\SC\Char\PersonalWeapon\PersonalWeapon;

class CreateWeaponWikiPages extends AbstractCreateWikiPage
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sc:create-weapon-pages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create personal weapon as wikipages';

    protected string $template = <<<'TEMPLATE'
Das Item '''<ITEM NAME>''' ist ein Größe <ITEM SIZE> <ITEM CLASS><ITEM TYPE> hergestellt von [[{{subst:MFURN|<MANUFACTURER CODE>}}]].<DESCRIPTION DATA><ref name="ig3221">{{Cite game|build=[[Star Citizen Alpha 3.22.1|Alpha 3.22.1]]|accessdate=<CURDATE>}}</ref>
== Beschreibung ==
{{Item description}}
== Itemports ==
{{Item ports}}
== Statistik ==
{{Weapon damage stats}}
== Erwerb ==
{{Item availability}}
== Model ==
=== Varianten ===
{{Item variants}}
{{Quellen}}
{{Navplate manufacturers|<MANUFACTURER CODE>}}
{{Navplate personal weapons}}
TEMPLATE;

    protected array $typeMapping = [
        'Knife' => 'Messer',
        'Railgun' => 'Railgun',
        'Missile Launcher' => 'Raketenwerfer',
        'Grenade Launcher' => 'Granatwerfer',
        'Grenade' => 'Granate',
        'LMG' => 'leichtes Maschinengewehr',
        'Pistol' => 'Pistole',
        'Assault Rifle' => 'Sturmgewehr',
        'Shotgun' => 'Schrotflinte',
        'SMG' => 'Maschinenpistole',
        'Sniper Rifle' => 'Scharfschützengewehr',
        'Medical Device' => 'Medizinalgerät',
        'Utility' => 'Hilfsmittel',
        'Tractor Beam' => 'Traktorstrahl',
        'Frag Pistol' => 'Splitterpistole',
        'Toy Pistol' => 'Spielzeugpistole',
        'Large' => 'große Waffe',
        'Medium' => 'mittlere Waffe',
        'Small' => 'kleine Waffe',
        'Gadget' => 'Hilfsmittel',
    ];

    protected array $classMapping = [
        'Ballistic' => 'Ballistik',
        'Energy (Laser)' => 'Laserenergie',
        'Laser' => 'Laser',
        'Energy (Plasma)' => 'Plasmaenergie',
        'Electron' => 'Elektronen',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->withProgressBar(
            PersonalWeapon::all(),
            function (PersonalWeapon $item) {
                $this->uploadWiki($item, 'Automatische Erstellung von Waffenseiten');
            }
        );

        return 0;
    }

    /**
     * @param  PersonalWeapon  $model
     */
    protected function prepareTemplate($model): string
    {
        $pageContent = $this->template;
        $type = ($this->typeMapping[$model->weapon_type] ?? $this->typeMapping[$model->sub_type] ?? $model->sub_type);

        $pageContent = str_replace(
            '<ITEM SIZE>',
            (string) $model->size,
            $pageContent
        );

        $pageContent = str_replace(
            '<ITEM CLASS>',
            isset($this->classMapping[$model->weapon_class]) ? $this->classMapping[$model->weapon_class].' ' : '',
            $pageContent
        );

        $pageContent = str_replace(
            '<ITEM TYPE>',
            $type,
            $pageContent
        );

        $this->fixText($type, $pageContent);

        if ($type === 'Messer') {
            $pageContent = str_replace("== Statistik ==\n{{Weapon damage stats}}\n", '', $pageContent);
        }

        $descriptionDataTargets = [
            'Magazine Size' => 'Magazingröße',
            'Rate Of Fire' => 'Feuerrate',
            'Effective Range' => 'effektive Reichweite',
        ];
        $dataFragments = [];

        foreach ($descriptionDataTargets as $target => $desc) {
            $data = $model->getDescriptionDatum($target);
            if ($data !== null) {
                $line = sprintf('%s von %s', $desc, $data);
                if ($target === 'Magazine Size') {
                    if ($data === 'Integrated Battery') {
                        $line = 'integrierte Batterie';
                    } else {
                        $line .= ' Schuss';
                    }
                }
                $dataFragments[] = $line;
            }
        }

        if (! empty($dataFragments)) {
            $last = array_pop($dataFragments);
            $dataFragments = sprintf(
                ' Die Waffe besitzt eine %s, und eine %s.',
                implode(', eine ', $dataFragments),
                $last
            );
        } else {
            $dataFragments = '';
        }

        $pageContent = str_replace(
            '<DESCRIPTION DATA>',
            $dataFragments,
            $pageContent
        );

        $this->fixText($type, $pageContent);

        return $pageContent;
    }

    /**
     * @param  PersonalWeapon  $model
     */
    protected function getPageName($model): string
    {
        return $model->name;
    }

    /**
     * @param  PersonalWeapon  $model
     */
    protected function getManufacturerCode($model): string
    {
        return $model->manufacturer->code;
    }
}

<?php

declare(strict_types=1);

namespace App\Console\Commands\SC\Wiki;

use App\Models\SC\Vehicle\VehicleItem;

class CreateShipItemWikiPages extends AbstractCreateWikiPage
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sc:create-ship-item-pages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create ship items as wikipages';

    protected string $template = <<<'TEMPLATE'
Das Item '''<ITEM NAME>''' ist ein Größe <ITEM SIZE><ITEM GRADE><ITEM CLASS><ITEM TYPE> hergestellt von [[{{subst:MFURN|<MANUFACTURER CODE>}}]].<ref name="<REFNAME>">{{Cite game|build=[[Star Citizen Alpha <REFVERSION>|Alpha <REFVERSION>]]|accessdate=<CURDATE>}}</ref>
== Beschreibung ==
{{Item description}}
== Erwerb ==
{{Item availability}}
== Standardausrüstung von ==
{{Standardausrüstung}}
{{Quellen}}
{{Navplate manufacturers|<MANUFACTURER CODE>}}
{{<ITEM TYPE> Navplate}}
TEMPLATE;

    protected array $typeMapping = [
        'BombLauncher' => 'Bombenwerfer',
        'Cooler' => 'Kühler',
        'EMP' => 'EMP-Generator',
        'Missile' => 'Rakete',
        'MissileLauncher' => 'Raketenwerfer',
        'PowerPlant' => 'Generator',
        'QuantumDrive' => 'Quantenantrieb',
        'QuantumInterdictionGenerator' => 'Quantum Enforcement Device',
        'SalvageModifier' => 'Bergungsmodifikator',
        'Shield' => 'Schildgenerator',
        'TowingBeam' => 'Abschleppstrahl',
        'TractorBeam' => 'Traktorstrahl',
        'WeaponGun' => 'Fahrzeugwaffe',
        'WeaponMining' => 'Bergbaulaser',
        'WeaponDefensive' => 'Defensivmittel',
    ];

    protected array $classMappings = [
        'Civilian' => 'Zivil',
        'Competition' => 'Wettkampf',
        'Military' => 'Militär',
        'Industrial' => 'Industrie',
        'Stealth' => 'Stealth',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->withProgressBar(
            VehicleItem::query()
                ->where('sc_items.name', '<>', '<= PLACEHOLDER =>')
                ->whereIn('type', [
                    'BombLauncher',
                    'Cooler',
                    'EMP',
                    'Missile',
                    'MissileLauncher',
                    'PowerPlant',
                    'QuantumDrive',
                    'QuantumInterdictionGenerator',
                    'SalvageModifier',
                    'Shield',
                    'TowingBeam',
                    'TractorBeam',
                    'WeaponGun',
                    'WeaponMining',
                    'Radar',
                ])
                ->get(),
            function (VehicleItem $item) {
                $this->uploadWiki($item, 'Automatische Erstellung von Fahrzeugitems');
            }
        );

        return 0;
    }

    protected function prepareTemplate($model): string
    {
        $pageContent = $this->template;
        $type = ($this->typeMapping[$model->type] ?? $model->type);

        $pageContent = str_replace(
            '<ITEM SIZE>',
            $model->size.((! $model->grade && ! $model->class) ? ' ' : ''),
            $pageContent
        );

        $pageContent = str_replace(
            '<ITEM GRADE>',
            $model->grade ? ', Grad '.$model->grade.', ' : '',
            $pageContent
        );

        $pageContent = str_replace(
            '<ITEM CLASS>',
            $model->class ? ($this->classMappings[$model->class] ?? $model->class).'-' : '',
            $pageContent
        );

        $pageContent = str_replace(
            '<ITEM TYPE> ',
            $type.' ',
            $pageContent
        );

        $this->fixText($type, $pageContent);

        return $pageContent;
    }

    /**
     * @param  VehicleItem  $model
     */
    protected function getPageName($model): string
    {
        $name = $model->name;

        if (in_array($name, ['Liberator', 'Odyssey', 'Nova', 'Vulcan', 'Eclipse', 'Centurion', 'Citadel', 'Castra', 'Mercury'])) {
            $name = sprintf('%s (%s)', $name, ($this->typeMapping[$model->type] ?? $model->type));
        }

        return $name;
    }

    /**
     * @param  VehicleItem  $model
     */
    protected function getManufacturerCode($model): string
    {
        return $model->manufacturer->code;
    }
}

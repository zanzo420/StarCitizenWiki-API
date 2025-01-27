<?php

declare(strict_types=1);

namespace App\Http\Resources\SC\Item;

use App\Http\Resources\AbstractTranslationResource;
use App\Http\Resources\SC\Char\ClothingResource;
use App\Http\Resources\SC\Char\PersonalWeapon\BarrelAttachResource;
use App\Http\Resources\SC\Char\PersonalWeapon\GrenadeResource;
use App\Http\Resources\SC\Char\PersonalWeapon\IronSightResource;
use App\Http\Resources\SC\Char\PersonalWeapon\KnifeResource;
use App\Http\Resources\SC\Char\PersonalWeapon\PersonalWeaponMagazineResource;
use App\Http\Resources\SC\Char\PersonalWeapon\PersonalWeaponResource;
use App\Http\Resources\SC\FoodResource;
use App\Http\Resources\SC\ItemSpecification\ArmorResource;
use App\Http\Resources\SC\ItemSpecification\BombResource;
use App\Http\Resources\SC\ItemSpecification\CoolerResource;
use App\Http\Resources\SC\ItemSpecification\EmpResource;
use App\Http\Resources\SC\ItemSpecification\FlightControllerResource;
use App\Http\Resources\SC\ItemSpecification\FuelIntakeResource;
use App\Http\Resources\SC\ItemSpecification\FuelTankResource;
use App\Http\Resources\SC\ItemSpecification\HackingChipResource;
use App\Http\Resources\SC\ItemSpecification\MiningLaser\MiningLaserResource;
use App\Http\Resources\SC\ItemSpecification\MiningModuleResource;
use App\Http\Resources\SC\ItemSpecification\MissileResource;
use App\Http\Resources\SC\ItemSpecification\PowerPlantResource;
use App\Http\Resources\SC\ItemSpecification\QuantumDrive\QuantumDriveResource;
use App\Http\Resources\SC\ItemSpecification\QuantumInterdictionGeneratorResource;
use App\Http\Resources\SC\ItemSpecification\SalvageModifierResource;
use App\Http\Resources\SC\ItemSpecification\SelfDestructResource;
use App\Http\Resources\SC\ItemSpecification\ShieldResource;
use App\Http\Resources\SC\ItemSpecification\ThrusterResource;
use App\Http\Resources\SC\ItemSpecification\TractorBeamResource;
use App\Http\Resources\SC\Manufacturer\ManufacturerLinkResource;
use App\Http\Resources\SC\Shop\ShopResource;
use App\Http\Resources\SC\Vehicle\Weapon\VehicleWeaponResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'metadata_v2',
    title: 'Metadata',
    description: 'Information about version and update date',
    properties: [
        new OA\Property(
            property: 'updated_at',
            description: 'Timestamp this data was last updated.',
            type: 'datetime',
        ),
        new OA\Property(
            property: 'version',
            description: 'The Game Version this item exists in.',
            type: 'string',
        ),
    ],
    type: 'object'
)]

#[OA\Schema(
    schema: 'item_base_v2',
    title: 'Base Item',
    allOf: [
        new OA\Schema(
            properties: [
                new OA\Property(property: 'uuid', type: 'string'),
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'class_name', type: 'string'),
                new OA\Property(
                    property: 'description',
                    nullable: true,
                    oneOf: [
                        new OA\Schema(type: 'string'),
                        new OA\Schema(
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/translation_v2'),
                        ),
                    ],
                ),
                new OA\Property(property: 'size', type: 'integer', nullable: true),
                new OA\Property(property: 'mass', type: 'double', nullable: true),
                new OA\Property(property: 'is_base_variant', type: 'boolean'),
                new OA\Property(property: 'grade', type: 'string', nullable: true),
                new OA\Property(property: 'class', type: 'string', nullable: true),
                new OA\Property(
                    property: 'description_data',
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/item_description_data_v2'),
                    nullable: true
                ),
                new OA\Property(property: 'manufacturer_description', type: 'string', nullable: true),
                new OA\Property(property: 'manufacturer', ref: '#/components/schemas/manufacturer_link_v2'),
                new OA\Property(property: 'type', type: 'string', nullable: true),
                new OA\Property(property: 'sub_type', type: 'string', nullable: true),
                new OA\Property(property: 'dimension', ref: '#/components/schemas/item_dimension_v2'),
                new OA\Property(property: 'inventory', ref: '#/components/schemas/item_container_v2', nullable: true),
                new OA\Property(
                    property: 'tags',
                    type: 'array',
                    items: new OA\Items(type: 'string'),
                    nullable: true,
                ),
                new OA\Property(
                    property: 'required_tags',
                    type: 'array',
                    items: new OA\Items(type: 'string'),
                    nullable: true,
                ),
                new OA\Property(
                    property: 'entity_tags',
                    type: 'array',
                    items: new OA\Items(type: 'string'),
                    nullable: true,
                ),
                new OA\Property(
                    property: 'interactions',
                    type: 'array',
                    items: new OA\Items(type: 'string'),
                    nullable: true,
                ),
                new OA\Property(property: 'ports', ref: '#/components/schemas/item_port_data_v2', nullable: true),
                new OA\Property(property: 'heat', ref: '#/components/schemas/item_heat_data_v2', nullable: true),
                new OA\Property(property: 'power', ref: '#/components/schemas/item_power_data_v2', nullable: true),
                new OA\Property(property: 'distortion', ref: '#/components/schemas/item_distortion_data_v2', nullable: true),
                new OA\Property(property: 'durability', ref: '#/components/schemas/item_durability_data_v2', nullable: true),
                new OA\Property(property: 'shops', ref: '#/components/schemas/shop_v2', nullable: true),
                new OA\Property(property: 'base_variant', ref: '#/components/schemas/item_link_v2', nullable: true),
                new OA\Property(
                    property: 'variants',
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/item_link_v2'),
                    nullable: true
                ),
            ],
            type: 'object'
        ),
        new OA\Schema(ref: '#/components/schemas/metadata_v2'),
    ],
)]

#[OA\Schema(
    schema: 'item_v2',
    title: 'Item',
    description: 'An Item in Star Citizen',
    type: 'object',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/item_base_v2'),
        new OA\Schema(
            type: 'object',
            oneOf: [
                new OA\Schema(ref: '#/components/schemas/vehicle_item_specification_v2'),
                new OA\Schema(ref: '#/components/schemas/personal_weapon_attachment_specification_v2'),
                new OA\Schema(
                    properties: [
                        new OA\Property(property: 'clothing', ref: '#/components/schemas/clothing_v2'),
                    ],
                    type: 'object',
                ),
                new OA\Schema(
                    properties: [
                        new OA\Property(property: 'food', ref: '#/components/schemas/food_v2'),
                    ],
                    type: 'object',
                ),
                new OA\Schema(
                    properties: [
                        new OA\Property(property: 'grenade', ref: '#/components/schemas/grenade_v2'),
                    ],
                    type: 'object',
                ),
                new OA\Schema(
                    properties: [
                        new OA\Property(property: 'hacking_chip', ref: '#/components/schemas/hacking_chip_v2'),
                    ],
                    type: 'object',
                ),
                new OA\Schema(
                    properties: [
                        new OA\Property(property: 'knife', ref: '#/components/schemas/knife_v2'),
                    ],
                    type: 'object',
                ),
                new OA\Schema(
                    properties: [
                        new OA\Property(property: 'personal_weapon', ref: '#/components/schemas/personal_weapon_v2'),
                    ],
                    type: 'object',
                ),
            ]
        ),
    ]
)]
class ItemResource extends AbstractTranslationResource
{

    public static function validIncludes(): array
    {
        return parent::validIncludes() + [
            'shops',
            'shops.items',
        ];
    }

    public function toArray(Request $request): array
    {
        if ($this->uuid === null) {
            return [];
        }

        $vehicleItem = $this->vehicleItem;

        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'class_name' => $this->class_name,
            'description' => $this->getTranslation($this, $request),
            'size' => $this->size,
            'mass' => $this->mass,
            'is_base_variant' => $this->base_id === null,
            $this->mergeWhen($vehicleItem !== null || $vehicleItem->exists, [
                'grade' => $vehicleItem->grade,
                'class' => $vehicleItem->class,
            ]),
            'description_data' => ItemDescriptionDataResource::collection($this->whenLoaded('descriptionData')),
            'manufacturer_description' => $this->getDescriptionDatum('Manufacturer'),
            'manufacturer' => new ManufacturerLinkResource($this->manufacturer),
            'type' => $this->cleanType(),
            'sub_type' => $this->sub_type,
            $this->mergeWhen(...$this->addAttachmentPosition()),
            $this->mergeWhen($this->isTurret(), $this->addTurretData()),
            $this->mergeWhen(...$this->addSpecification()),
            'dimension' => new ItemDimensionResource($this),
            $this->mergeWhen($this->container->exists, [
                'inventory' => new ItemContainerResource($this->container),
            ]),
            'tags' => $this->defaultTags->pluck('name')->toArray(),
            'required_tags' => $this->requiredTags->pluck('name')->toArray(),
            'entity_tags' => $this->entityTags->pluck('tag')->toArray(),
            'interactions' => $this->interactions->pluck('name')->toArray(),
            'ports' => ItemPortResource::collection($this->whenLoaded('ports')),
            $this->mergeWhen($this->relationLoaded('heatData'), [
                'heat' => new ItemHeatDataResource($this->heatData),
            ]),
            $this->mergeWhen($this->relationLoaded('powerData'), [
                'power' => new ItemPowerDataResource($this->powerData),
            ]),
            $this->mergeWhen($this->relationLoaded('distortionData'), [
                'distortion' => new ItemDistortionDataResource($this->distortionData),
            ]),
            $this->mergeWhen($this->relationLoaded('durabilityData'), [
                'durability' => new ItemDurabilityDataResource($this->durabilityData),
            ]),
            $this->mergeWhen($this->type === 'WeaponAttachment', [
                'weapon_modifier' => new ItemWeaponModifierDataResource($this->weaponModifierData),
            ]),
            'shops' => ShopResource::collection($this->whenLoaded('shops')),
            $this->mergeWhen($this->base_id !== null, [
                'base_variant' => new ItemLinkResource($this->baseVariant),
            ]),
            'variants' => ItemLinkResource::collection($this->whenLoaded('variants')),
            'updated_at' => $this->updated_at,
            'version' => $this->version,
        ];
    }

    protected function addSpecification(): array
    {
        $specification = $this?->specification;
        if (! $specification?->exists || $specification === null) {
            return [false, []];
        }

        return match (true) {
            $this->type === 'Armor' => [
                $specification->exists,
                ['emp' => new ArmorResource($specification)],
            ],
            $this->type === 'Bomb' => [
                $specification->exists,
                ['bomb' => new BombResource($specification)],
            ],
            $this->type === 'Cooler' => [
                $specification->exists,
                ['cooler' => new CoolerResource($specification)],
            ],
            str_contains($this->type, 'Char_Clothing'), str_contains($this->type, 'Char_Armor') => [
                $specification->exists,
                ['clothing' => new ClothingResource($specification)],
            ],
            $this->type === 'EMP' => [
                $specification->exists,
                ['emp' => new EmpResource($specification)],
            ],
            $this->type === 'Food', $this->type === 'Bottle', $this->type === 'Drink' => [
                $specification->exists,
                ['food' => new FoodResource($specification)],
            ],
            $this->type === 'FlightController' => [
                $specification->exists,
                ['flight_controller' => new FlightControllerResource($specification)],
            ],
            $this->type === 'FuelTank', $this->type === 'QuantumFuelTank', $this->type === 'ExternalFuelTank' => [
                $specification->exists,
                ['fuel_tank' => new FuelTankResource($specification)],
            ],
            $this->type === 'FuelIntake' => [
                $specification->exists,
                ['fuel_intake' => new FuelIntakeResource($specification)],
            ],
            $this->sub_type === 'Hacking' => [
                $specification->exists,
                ['hacking_chip' => new HackingChipResource($specification)],
            ],
            $this->type === 'MainThruster', $this->type === 'ManneuverThruster' => [
                $specification->exists,
                ['thruster' => new ThrusterResource($specification)],
            ],
            $this->sub_type === 'Magazine' => [
                $specification->exists,
                ['personal_weapon_magazine' => new PersonalWeaponMagazineResource($specification)],
            ],
            $this->type === 'Missile', $this->type === 'Torpedo' => [
                $specification->exists,
                ['missile' => new MissileResource($specification)],
            ],
            $this->type === 'MiningModifier' => [
                $specification->exists,
                ['mining_module' => new MiningModuleResource($specification)],
            ],
            $this->type === 'PowerPlant' => [
                $specification->exists,
                ['power_plant' => new PowerPlantResource($specification)],
            ],
            $this->type === 'QuantumInterdictionGenerator' => [
                $specification->exists,
                ['quantum_interdiction_generator' => new QuantumInterdictionGeneratorResource($specification)],
            ],
            $this->type === 'QuantumDrive' => [
                $specification->exists,
                ['quantum_drive' => new QuantumDriveResource($specification)],
            ],
            $this->type === 'SalvageModifier' => [
                $specification->exists,
                ['salvage_modifier' => new SalvageModifierResource($specification)],
            ],
            $this->type === 'SelfDestruct' => [
                $specification->exists,
                ['self_destruct' => new SelfDestructResource($specification)],
            ],
            $this->type === 'Shield' => [
                $specification->exists,
                ['shield' => new ShieldResource($specification)],
            ],
            $this->type === 'TractorBeam' || $this->type === 'TowingBeam' => [
                $specification->exists,
                ['tractor_beam' => new TractorBeamResource($specification)],
            ],
            $this->type === 'WeaponPersonal' && $this->sub_type === 'Grenade' => [
                $specification->exists,
                ['grenade' => new GrenadeResource($specification)],
            ],
            $this->type === 'WeaponPersonal' && $this->sub_type === 'Knife' => [
                $specification->exists,
                ['knife' => new KnifeResource($specification)],
            ],
            $this->type === 'WeaponPersonal' => [
                $specification->exists,
                ['personal_weapon' => new PersonalWeaponResource($specification)],
            ],
            $this->sub_type === 'IronSight' => [
                $specification->exists,
                ['iron_sight' => new IronSightResource($specification)],
            ],
            $this->type === 'WeaponAttachment' && in_array($this->sub_type, ['Barrel', 'BottomAttachment', 'Utility'], true) => [
                $specification->exists,
                ['barrel_attach' => new BarrelAttachResource($specification)],
            ],
            $this->type === 'WeaponGun', $this->type === 'WeaponDefensive' => [
                $specification->exists,
                [($this->type === 'WeaponGun' ?
                    'vehicle_weapon' :
                    'counter_measure') => new VehicleWeaponResource($specification), ],
            ],
            $this->type === 'WeaponMining' => [
                $specification->exists,
                ['mining_laser' => new MiningLaserResource($specification)],
            ],
            default => [false, []],
        };
    }

    protected function addTurretData(): array
    {
        $mountName = 'max_mounts';
        if ($this->type === 'MissileLauncher') {
            $mountName = 'max_missiles';
        } elseif ($this->type === 'BombLauncher') {
            $mountName = 'max_bombs';
        }

        $ports = $this->ports;

        return [
            $mountName => $ports->count(),
            'min_size' => $ports->min('min_size'),
            'max_size' => $ports->max('max_size'),
        ];
    }

    private function addAttachmentPosition(): array
    {
        if ($this->type !== 'WeaponAttachment' || $this->name === '<= PLACEHOLDER =>') {
            return [false, []];
        }

        return [
            true,
            [
                'position' => match ($this->sub_type) {
                    'Magazine' => 'Magazine Well',
                    'Barrel' => 'Barrel',
                    'IronSight' => 'Optic',
                    'Utility' => 'Utility',
                    'BottomAttachment' => 'Underbarrel',
                    default => $this->sub_type,
                },
            ],
        ];
    }
}

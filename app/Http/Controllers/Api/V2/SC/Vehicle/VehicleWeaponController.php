<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V2\SC\Vehicle;

use App\Http\Controllers\Api\V2\AbstractApiV2Controller;
use App\Http\Resources\AbstractBaseResource;
use App\Http\Resources\SC\Item\ItemResource;
use App\Http\Resources\SC\Vehicle\VehicleItemLinkResource;
use App\Http\Resources\SC\Vehicle\Weapon\VehicleWeaponResource;
use App\Models\SC\Item\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VehicleWeaponController extends AbstractApiV2Controller
{
    #[OA\Get(
        path: '/api/v2/vehicle-weapons',
        tags: ['Vehicles', 'In-Game', 'Weapons'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/page'),
            new OA\Parameter(ref: '#/components/parameters/limit'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of Vehicle Weapons',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/item_link_v2')
                )
            ),
        ]
    )]
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = QueryBuilder::for(Item::class, $request)
            ->where('type', 'WeaponGun')
            ->allowedIncludes(VehicleWeaponResource::validIncludes())
            ->allowedFilters([
                AllowedFilter::callback('type', static function (Builder $query, $value) {
                    $query->whereRelation('descriptionData', 'name', 'Item Type')
                        ->whereRelation('descriptionData', 'value', $value);
                }),
            ])
            ->paginate($this->limit)
            ->appends(request()->query());

        return VehicleItemLinkResource::collection($query);
    }

    #[OA\Get(
        path: '/api/v2/vehicle-weapons/{weapon}',
        tags: ['Vehicles', 'In-Game', 'Weapons'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/locale'),
            new OA\Parameter(ref: '#/components/parameters/commodity_includes_v2'),
            new OA\Parameter(
                name: 'weapon',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    description: 'Item name or UUID',
                    type: 'string',
                ),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'A Vehicle Weapon',
                content: new OA\JsonContent(ref: '#/components/schemas/vehicle_weapon_v2')
            ),
        ]
    )]
    public function show(Request $request): AbstractBaseResource
    {
        ['weapon' => $identifier] = Validator::validate(
            [
                'weapon' => $request->weapon,
            ],
            [
                'weapon' => 'required|string|min:1|max:255',
            ]
        );

        $identifier = $this->cleanQueryName($identifier);

        try {
            $identifier = QueryBuilder::for(Item::class, $request)
                ->where('type', 'WeaponGun')
                ->where(function (Builder $query) use ($identifier) {
                    $query->where('uuid', $identifier)
                        ->orWhere('name', 'LIKE', sprintf('%%%s%%', $identifier));
                })
                ->orderByDesc('version')
                ->allowedIncludes(VehicleWeaponResource::validIncludes())
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('No Weapon with specified UUID or Name found.');
        }

        return new ItemResource($identifier);
    }
}

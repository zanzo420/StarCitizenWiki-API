<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V2\StarCitizen;

use App\Http\Controllers\Api\V2\AbstractApiV2Controller;
use App\Http\Requests\StarCitizen\Vehicle\VehicleSearchRequest;
use App\Http\Resources\AbstractBaseResource;
use App\Http\Resources\SC\Vehicle\VehicleLinkResource;
use App\Http\Resources\StarCitizen\Vehicle\VehicleResource;
use App\Models\SC\Vehicle\Vehicle as UnpackedVehicle;
use App\Models\StarCitizen\Vehicle\Vehicle\Vehicle;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VehicleController extends AbstractApiV2Controller
{
    #[OA\Get(
        path: '/api/v2/vehicles',
        tags: ['Vehicles', 'RSI-Website', 'In-Game'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/page'),
            new OA\Parameter(ref: '#/components/parameters/limit'),
            new OA\Parameter(ref: '#/components/parameters/locale'),
            new OA\Parameter(name: 'filter[manufacturer]', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of Vehicles',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/vehicle_link_v2')
                )
            )
        ]
    )]
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = QueryBuilder::for(Vehicle::class, $request)
            ->orderByDesc('name')
            ->allowedFilters([
                AllowedFilter::partial('manufacturer', 'manufacturer.name'),
            ])
            ->paginate($this->limit)
            ->appends(request()->query());

        return VehicleLinkResource::collection($query);
    }

    #[OA\Get(
        path: '/api/v2/vehicles/{name}',
        tags: ['Vehicles', 'RSI-Website', 'In-Game'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/locale'),
            new OA\Parameter(
                name: 'include',
                in: 'query',
                schema: new OA\Schema(
                    schema: 'vehicle_includes_v2',
                    description: 'Available Vehicle includes',
                    collectionFormat: 'csv',
                    enum: [
                        'components',
                        'hardpoints',
                        'shops',
                        'shops.items',
                    ]
                ),
                allowReserved: true
            ),
            new OA\Parameter(
                name: 'name',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    schema: 'vehicle_name',
                    description: '(Partial) Vehicle name',
                    type: 'string',
                ),
            ),
        ],
        responses: [
            new OA\Response(
                ref: '#/components/schemas/vehicle_v2',
                response: 200,
                description: 'A singular vehicle'
            )
        ]
    )]
    public function show(Request $request): AbstractBaseResource
    {
        ['vehicle' => $identifier] = Validator::validate(
            [
                'vehicle' => $request->vehicle,
            ],
            [
                'vehicle' => 'required|string|min:1|max:255',
            ]
        );

        $identifier = $this->cleanQueryName($identifier);

        try {
            $vehicleModel = QueryBuilder::for(Vehicle::class, $request)
                ->where('name', 'LIKE', "%{$identifier}%")
                ->orWhere('slug', 'LIKE', "%{$identifier}%")
                ->first();

            if ($vehicleModel === null) {
                $vehicleModel = QueryBuilder::for(UnpackedVehicle::class)
                    // ->allowedIncludes(VehicleResource::validIncludes())
                    ->where('name', 'like', '%' . $identifier . '%')
                    ->orWhere('class_name', 'like', '%' . $identifier . '%')
                    ->firstOrFail();

                return new \App\Http\Resources\SC\Vehicle\VehicleResource($vehicleModel);
            }
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('No Vehicle with specified name found.');
        }

        return new VehicleResource($vehicleModel);
    }

    #[OA\Post(
        path: '/api/v2/vehicles/search',
        requestBody: new OA\RequestBody(
            description: 'Vehicle (partial) name or slug',
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        schema: 'query',
                        type: 'json',
                    ),
                    example: '{"query": "Merchant"}',
                )
            ]
        ),
        tags: ['Vehicles', 'RSI-Website', 'In-Game'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/page'),
            new OA\Parameter(ref: '#/components/parameters/limit'),
            new OA\Parameter(ref: '#/components/parameters/locale'),
            new OA\Parameter(name: 'filter[manufacturer]', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of vehicles matching the query',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/vehicle_link_v2')
                )
            ),
            new OA\Response(
                response: 404,
                description: 'No vehicle(s) found.',
            )
        ],
    )]
    public function search(Request $request): AnonymousResourceCollection
    {
        $rules = (new VehicleSearchRequest())->rules();

        $request->validate($rules);

        $query = $this->cleanQueryName($request->get('query'));

        $queryBuilder = QueryBuilder::for(Vehicle::class, $request)
            ->allowedFilters([
                AllowedFilter::partial('manufacturer', 'manufacturer.name'),
            ])
            ->where('name', 'like', "%{$query}%")
            ->orWhere('slug', 'like', "%{$query}%")
            ->paginate($this->limit)
            ->appends(request()->query());

        if ($queryBuilder->count() === 0) {
            throw new NotFoundHttpException(sprintf(static::NOT_FOUND_STRING, $query));
        }

        return VehicleResource::collection($queryBuilder);
    }
}
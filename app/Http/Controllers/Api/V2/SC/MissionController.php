<?php

namespace App\Http\Controllers\Api\V2\SC;

use App\Http\Controllers\Api\V2\AbstractApiV2Controller;
use App\Http\Resources\SC\Mission\MissionLinkResource;
use App\Http\Resources\SC\Mission\MissionResource;
use App\Models\SC\Mission\Mission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MissionController extends AbstractApiV2Controller
{
    #[OA\Get(
        path: '/api/v2/missions',
        tags: ['In-Game', 'Mission'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/page'),
            new OA\Parameter(ref: '#/components/parameters/limit'),
            new OA\Parameter(name: 'filter[type]', in: 'query', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'filter[name]', in: 'query', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'filter[giver]', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of Missions',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/mission_link_v2')
                )
            ),
        ]
    )]
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = QueryBuilder::for(Mission::class, $request)
            ->allowedFilters([
                AllowedFilter::partial('type', 'type.name'),
                AllowedFilter::partial('name', 'title'),
                AllowedFilter::partial('giver', 'mission_giver'),
            ])
            ->paginate($this->limit)
            ->appends(request()->query());

        return MissionLinkResource::collection($query);
    }

    #[OA\Get(
        path: '/api/v2/missions/{mission}',
        tags: ['In-Game', 'Mission'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/locale'),
            new OA\Parameter(
                name: 'mission',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    description: 'Mission UUID',
                    type: 'string',
                ),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'A Mission',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/mission_v2')
                )
            ),
        ]
    )]
    public function show(Request $request): JsonResource
    {
        ['mission' => $identifier] = Validator::validate(
            [
                'mission' => $request->mission,
            ],
            [
                'mission' => 'required|uuid',
            ]
        );

        $model = QueryBuilder::for(Mission::class, $request)
            ->where('uuid', $identifier)
            ->with([
                'giver',
                'type',
                'deadline',
                'reward',
                'requiredMissions',
                'associatedMissions',
            ])
            ->firstOrFail();

        return new MissionResource($model);
    }
}

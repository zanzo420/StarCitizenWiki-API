<?php

namespace App\Http\Controllers\Api\V2\SC;

use App\Http\Controllers\Api\V2\AbstractApiV2Controller;
use App\Http\Resources\SC\Mission\GiverLinkResource;
use App\Http\Resources\SC\Mission\GiverResource;
use App\Models\SC\Mission\Giver;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;
use Spatie\QueryBuilder\QueryBuilder;

class MissionGiverController extends AbstractApiV2Controller
{
    #[OA\Get(
        path: '/api/v2/missions-givers',
        tags: ['In-Game', 'Missions'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/page'),
            new OA\Parameter(ref: '#/components/parameters/limit'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of Mission Givers',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/mission_giver_link_v2')
                )
            ),
        ]
    )]
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = QueryBuilder::for(Giver::class, $request)
            ->paginate($this->limit)
            ->appends(request()->query());

        return GiverLinkResource::collection($query);
    }

    #[OA\Get(
        path: '/api/v2/mission-givers/{giver}',
        tags: ['In-Game', 'Missions'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/locale'),
            new OA\Parameter(
                name: 'giver',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    description: 'Mission Giver UUID',
                    type: 'string',
                ),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'A Mission Giver',
                content: new OA\JsonContent(ref: '#/components/schemas/mission_giver_v2')
            ),
        ]
    )]
    public function show(Request $request): JsonResource
    {
        ['giver' => $identifier] = Validator::validate(
            [
                'giver' => $request->giver,
            ],
            [
                'giver' => 'required|uuid',
            ]
        );

        $model = QueryBuilder::for(Giver::class, $request)
            ->where('uuid', $identifier)
            ->with([
                'missions',
            ])
            ->firstOrFail();

        return new GiverResource($model);
    }
}

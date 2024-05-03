<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V2\SC;

use App\Http\Controllers\Api\V2\AbstractApiV2Controller;
use App\Http\Resources\AbstractBaseResource;
use App\Http\Resources\SC\Faction\FactionLinkResource;
use App\Http\Resources\SC\Faction\FactionResource;
use App\Models\SC\Faction\Faction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FactionController extends AbstractApiV2Controller
{
    #[OA\Get(
        path: '/api/v2/factions',
        tags: ['In-Game', 'Factions'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/page'),
            new OA\Parameter(ref: '#/components/parameters/limit'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of Factions',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/faction_link_v2')
                )
            ),
        ]
    )]
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = QueryBuilder::for(Faction::class, $request)
            ->paginate($this->limit)
            ->appends(request()->query());

        return FactionLinkResource::collection($query);
    }

    #[OA\Get(
        path: '/api/v2/factions/{faction}',
        tags: ['In-Game', 'Factions'],
        parameters: [
            new OA\Parameter(
                name: 'faction',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    description: 'Faction UUID or name',
                    type: 'string',
                ),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'A Faction and its relations',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/faction_v2')
                )
            ),
        ]
    )]
    public function show(Request $request): AbstractBaseResource
    {
        ['faction' => $identifier] = Validator::validate(
            [
                'faction' => $request->faction,
            ],
            [
                'faction' => 'required|string|min:1|max:255',
            ]
        );

        $identifier = $this->cleanQueryName($identifier);

        try {
            $faction = QueryBuilder::for(Faction::class, $request)
                ->where('uuid', $identifier)
                ->orWhere('name', 'LIKE', sprintf('%%%s%%', $identifier))
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('No Faction with specified UUID or Name found.');
        }

        return new FactionResource($faction);
    }
}

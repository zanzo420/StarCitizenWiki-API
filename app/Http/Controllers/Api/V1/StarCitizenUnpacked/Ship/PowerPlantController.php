<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\StarCitizenUnpacked\Ship;

use App\Http\Controllers\Api\AbstractApiController as ApiController;
use App\Models\StarCitizenUnpacked\ShipItem\PowerPlant;
use App\Transformers\Api\V1\StarCitizenUnpacked\ShipItem\ShipItemTransformer;
use Dingo\Api\Http\Request;
use Dingo\Api\Http\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class PowerPlantController extends ApiController
{
    /**
     * ShipController constructor.
     *
     * @param ShipItemTransformer $transformer
     * @param Request $request
     */
    public function __construct(ShipItemTransformer $transformer, Request $request)
    {
        $this->transformer = $transformer;

        parent::__construct($request);
    }

    public function index(): Response
    {
        return $this->getResponse(PowerPlant::query());
    }

    public function show(Request $request): Response
    {
        ['item' => $item] = Validator::validate(
            [
                'item' => $request->item,
            ],
            [
                'item' => 'required|string|min:1|max:255',
            ]
        );

        $item = $this->cleanQueryName($item);

        try {
            $item = PowerPlant::query()
                ->whereHas('shipItem.item', function (Builder $query) use ($item) {
                    return $query->where('name', $item)
                        ->orWhere('uuid', $item);
                })
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $this->response->errorNotFound(sprintf(static::NOT_FOUND_STRING, $item));
        }

        return $this->getResponse($item);
    }
}

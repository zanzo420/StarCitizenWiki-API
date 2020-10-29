<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\StarCitizen\Vehicle\GroundVehicle;

use App\Http\Controllers\Api\AbstractApiController as ApiController;
use App\Http\Requests\StarCitizen\Vehicle\GroundVehicleSearchRequest;
use App\Models\Api\StarCitizen\Vehicle\GroundVehicle\GroundVehicle;
use App\Transformers\Api\V1\StarCitizen\Vehicle\GroundVehicle\GroundVehicleLinkTransformer;
use App\Transformers\Api\V1\StarCitizen\Vehicle\GroundVehicle\GroundVehicleTransformer;
use App\Transformers\Api\V1\StarCitizen\Vehicle\Ship\ShipLinkTransformer;
use Dingo\Api\Http\Request;
use Dingo\Api\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Bodenfahrzeug API
 * Ausgabe der Bodenfahrzeuge der Ship Matrix
 */
class GroundVehicleController extends ApiController
{
    /**
     * ShipController constructor.
     *
     * @param GroundVehicleTransformer $transformer
     * @param Request                  $request
     */
    public function __construct(GroundVehicleTransformer $transformer, Request $request)
    {
        $this->transformer = $transformer;

        parent::__construct($request);
    }

    /**
     * Einzelnes Bodenfahrzeug
     * Ausgabe eines einzelnen Bodenfahrzeuges nach Fahrzeugnamen (z.B. Cyclone)
     * Name des Bodenfahrzeuges sollte URL enkodiert sein
     *
     * @param string $groundVehicle
     *
     * @return Response
     */
    public function show(string $groundVehicle): Response
    {
        $groundVehicle = urldecode($groundVehicle);

        try {
            $groundVehicle = GroundVehicle::query()
                ->where('name', $groundVehicle)
                ->orWhere('slug', $groundVehicle)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $this->response->errorNotFound(sprintf(static::NOT_FOUND_STRING, $groundVehicle));
        }

        return $this->getResponse($groundVehicle);
    }

    /**
     * Alle Bodenfahrzeuge
     * Ausgabe aller Bodenfahrzeuge der Ship Matrix paginiert
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        if ($request->has('transformer') && $request->get('transformer', null) === 'link') {
            $this->transformer = new GroundVehicleLinkTransformer();
        }

        return $this->getResponse(GroundVehicle::query()->orderBy('name'));
    }

    /**
     * Search Endpoint
     *
     * @param Request $request
     *
     * @return Response
     */
    public function search(Request $request): Response
    {
        $rules = (new GroundVehicleSearchRequest())->rules();

        $request->validate($rules);

        $query = urldecode($request->get('query'));
        $queryBuilder = GroundVehicle::query()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('slug', 'like', "%{$query}%");

        if ($queryBuilder->count() === 0) {
            $this->response->errorNotFound(sprintf(static::NOT_FOUND_STRING, $query));
        }

        return $this->getResponse($queryBuilder);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Resources\StarCitizen\Vehicle;

use App\Http\Resources\AbstractBaseResource;
use App\Models\StarCitizen\Vehicle\Component\Component;
use App\Transformers\Api\V1\AbstractV1Transformer as V1Transformer;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'vehicle_component_v2',
    title: 'Vehicle Component',
    description: 'Components from in-game files',
    properties: [
        new OA\Property(property: 'type', type: 'string'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'mounts', type: 'integer'),
        new OA\Property(property: 'component_size', type: 'integer'),
        new OA\Property(property: 'category', type: 'string'),
        new OA\Property(property: 'size', type: 'integer'),
        new OA\Property(property: 'details', type: 'string'),
        new OA\Property(property: 'quantity', type: 'integer'),
        new OA\Property(property: 'manufacturer', type: 'string'),
        new OA\Property(property: 'component_class', type: 'string'),
    ],
    type: 'json'
)]
class ComponentResource extends AbstractBaseResource
{

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
            'mounts' => $this->pivot->mounts,
            'component_size' => $this->component_size,
            'category' => $this->category,
            'size' => $this->pivot->size,
            'details' => $this->pivot->details,
            'quantity' => $this->pivot->quantity,
            'manufacturer' => $this->manufacturer,
            'component_class' => $this->component_class,
        ];
    }

    public static function validIncludes(): array
    {
        return [];
    }
}

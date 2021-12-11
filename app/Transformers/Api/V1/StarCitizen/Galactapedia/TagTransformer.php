<?php

declare(strict_types=1);

namespace App\Transformers\Api\V1\StarCitizen\Galactapedia;

use App\Models\StarCitizen\Galactapedia\Tag;
use App\Transformers\Api\V1\AbstractV1Transformer as V1Transformer;
use App\Transformers\Api\V1\StarCitizen\Vehicle\GroundVehicle\GroundVehicleLinkTransformer;
use App\Transformers\Api\V1\StarCitizen\Vehicle\Ship\ShipLinkTransformer;

/**
 * Manufacturer Transformer
 */
class TagTransformer extends V1Transformer
{
    /**
     * @param Tag $tag
     *
     * @return array
     */
    public function transform(Tag $tag): array
    {
        return [
            'id' => $tag->cig_id,
            'name' => $tag->name,
        ];
    }
}

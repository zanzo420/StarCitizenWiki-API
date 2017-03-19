<?php
/**
 * User: Hannes
 * Date: 04.03.2017
 * Time: 12:28
 */

namespace App\Transformers\StarCitizenWiki\Ships;

use App\Transformers\BaseAPITransformerInterface;
use League\Fractal\TransformerAbstract;

class ShipsTransformer extends TransformerAbstract implements BaseAPITransformerInterface
{
    public function transform($ship)
    {
        return $ship;
    }
}
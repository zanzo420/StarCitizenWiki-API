<?php
/**
 * User: Hannes
 * Date: 11.03.2017
 * Time: 20:04
 */

namespace App\Transformers\StarCitizen\Starmap;

use App\Transformers\BaseAPITransformerInterface;
use League\Fractal\TransformerAbstract;

class SystemTransformer extends TransformerAbstract implements BaseAPITransformerInterface
{
	public function transform($system)
    {
		return $system;
	}
}
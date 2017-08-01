<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 19.01.2017
 * Time: 12:47
 */

namespace App\Repositories\StarCitizen\Interfaces;

/**
 * Interface StatsInterface
 *
 * @package App\Repositories\StarCitizen\APIv1\Stats
 */
interface StatsInterface
{
    /**
     * Returns the Crowdfund Stats
     * https://robertsspaceindustries.com/api/stats/getCrowdfundStats
     */
    public function getCrowdfundStats();
}

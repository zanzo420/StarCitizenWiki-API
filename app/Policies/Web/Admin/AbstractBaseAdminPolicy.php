<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 06.08.2018
 * Time: 19:45
 */

namespace App\Policies\Web\Admin;

use App\Models\Account\Admin\Admin;

/**
 * Class AbstractBaseAdminPolicy
 */
class AbstractBaseAdminPolicy
{
    /**
     * Dont allow Access if Account is blocked
     *
     * @param \App\Models\Account\Admin\Admin $admin
     *
     * @return bool
     */
    public function before(Admin $admin)
    {
        if ($admin->isBlocked()) {
            return false;
        }
    }
}
<?php declare(strict_types = 1);

namespace App\Policies\Web\Admin\Rsi\CommLink;

use App\Models\Account\Admin\Admin;
use App\Models\Account\Admin\AdminGroup;
use App\Policies\Web\Admin\AbstractBaseAdminPolicy as BaseAdminPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class ManufacturerPolicy
 */
class CommLinkPolicy extends BaseAdminPolicy
{
    use HandlesAuthorization;

    /**
     * View all / single resource
     *
     * @param \App\Models\Account\Admin\Admin $admin
     *
     * @return bool
     */
    public function view(Admin $admin)
    {
        return $admin->getHighestPermissionLevel() >= AdminGroup::USER;
    }

    /**
     * Update a Resource
     *
     * @param \App\Models\Account\Admin\Admin $admin
     *
     * @return bool
     */
    public function update(Admin $admin)
    {
        return $admin->isEditor() || $admin->getHighestPermissionLevel() >= AdminGroup::SYSOP;
    }

    /**
     * Update Comm Link Settings
     *
     * @param \App\Models\Account\Admin\Admin $admin
     *
     * @return bool
     */
    public function updateSettings(Admin $admin)
    {
        return $admin->getHighestPermissionLevel() >= AdminGroup::SYSOP;
    }

    /**
     * Preview Comm Link Version
     *
     * @param \App\Models\Account\Admin\Admin $admin
     *
     * @return bool
     */
    public function preview(Admin $admin)
    {
        return $admin->getHighestPermissionLevel() >= AdminGroup::SYSOP;
    }
}

<?php
namespace ValuePad\Api\Company\V2_0\Controllers\Permissions;

use ValuePad\Api\Company\V2_0\Protectors\CompanyOwnerProtector;
use Ascope\Libraries\Permissions\AbstractActionsPermissions;

/**
 *
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class BranchesPermissions extends AbstractActionsPermissions
{

    /**
     *
     * @return array
     */
    protected function permissions()
    {
        return [
            'store' => CompanyOwnerProtector::class,
            'update' => CompanyOwnerProtector::class,
            'show' => CompanyOwnerProtector::class,
            'index' => CompanyOwnerProtector::class,
            'destroy' => CompanyOwnerProtector::class
        ];
    }
}
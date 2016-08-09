<?php
namespace ValuePad\Api\Company\V2_0\Controllers\Permissions;

use Ascope\Libraries\Permissions\AbstractActionsPermissions;
use ValuePad\Api\Company\V2_0\Protectors\CompanyOwnerProtector;
use ValuePad\Api\Company\V2_0\Protectors\SingleOwnerProtector;

/**
 *
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class CompaniesPermissions extends AbstractActionsPermissions
{
    /**
     * @return array
     */
    protected function permissions()
    {
        return [
            'store' => 'all',
            'update' => CompanyOwnerProtector::class,
            'show' => CompanyOwnerProtector::class,
            'destroy' => [
                CompanyOwnerProtector::class,
                SingleOwnerProtector::class
            ]
        ];
    }
}
<?php
namespace ValuePad\Api\Company\V2_0\Controllers\Permissions;

use Ascope\Libraries\Permissions\AbstractActionsPermissions;
use ValuePad\Api\Company\V2_0\Protectors\CompanyOwnerProtector;

/**
 *
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class MembersPermissions extends AbstractActionsPermissions
{

    /**
     *
     * @return array
     */
    protected function permissions()
    {
        return [
            'index' => CompanyOwnerProtector::class
        ];
    }
}
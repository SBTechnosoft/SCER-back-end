<?php
namespace ValuePad\Api\Company\V2_0\Controllers\Permissions;

use Ascope\Libraries\Permissions\AbstractActionsPermissions;
use ValuePad\Api\Company\V2_0\Protectors\SingleOwnerProtector;

/**
 *
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class OwnersPermissions extends AbstractActionsPermissions
{

    /**
     *
     * @return array
     */
    protected function permissions()
    {
        $protectors = [
            [
                'owner',
                [
                    'index' => 1
                ]
            ]
        ];

        return [
            /*
             * We don't want other owners to be freely added to the company created by the first owner.
             * This could be dangerous in sense that the other owners could be malicious users.
             */
            'store' => SingleOwnerProtector::class,
            'show' => $protectors,
            'update' => $protectors,
            'destroy' => $protectors
        ];
    }
}
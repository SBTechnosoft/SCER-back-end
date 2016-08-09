<?php
namespace ValuePad\Api\Company\V2_0\Routes;

use Ascope\Libraries\Routing\RouteRegistrarInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarInterface;
use ValuePad\Api\Company\V2_0\Controllers\MembersController;

/**
 *
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class Members implements RouteRegistrarInterface
{

    /**
     *
     * @param RegistrarInterface $registrar
     */
    public function register(RegistrarInterface $registrar)
    {
        $registrar->resource('companies.branches.members', MembersController::class, [
            'only' => 'index'
        ]);
    }
}
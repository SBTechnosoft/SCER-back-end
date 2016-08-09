<?php
namespace ValuePad\Api\Company\V2_0\Routes;

use Ascope\Libraries\Routing\RouteRegistrarInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarInterface;
use ValuePad\Api\Company\V2_0\Controllers\ManagersController;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class Managers implements RouteRegistrarInterface
{
    /**
     * @param RegistrarInterface $registrar
     */
    public function register(RegistrarInterface $registrar)
    {
        $registrar->resource('companies.branches.managers', ManagersController::class, [
            'except' => [
                'index'
            ]
        ]);
    }
}
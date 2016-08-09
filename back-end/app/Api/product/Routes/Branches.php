<?php
namespace ValuePad\Api\Company\V2_0\Routes;

use ValuePad\Api\Company\V2_0\Controllers\BranchesController;
use Ascope\Libraries\Routing\RouteRegistrarInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class Branches implements RouteRegistrarInterface
{
    /**
     * @param RegistrarInterface $registrar
     */
    public function register(RegistrarInterface $registrar)
    {
        $registrar->resource('companies.branches', BranchesController::class);
    }
}
<?php
namespace ValuePad\Api\Company\V2_0\Routes;

use Ascope\Libraries\Routing\RouteRegistrarInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarInterface;
use ValuePad\Api\Company\V2_0\Controllers\CompaniesController;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class Companies implements RouteRegistrarInterface
{
    /**
     * @param RegistrarInterface $registrar
     */
    public function register(RegistrarInterface $registrar)
    {
        $registrar->resource('companies', CompaniesController::class, [
            'except' => [
                'index'
            ]
        ]);
    }
}
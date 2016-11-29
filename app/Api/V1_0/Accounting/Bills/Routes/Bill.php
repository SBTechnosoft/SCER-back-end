<?php
namespace ERP\Api\V1_0\Accounting\Bills\Routes;

use ERP\Api\V1_0\Accounting\Bills\Controllers\BillController;
use ERP\Support\Interfaces\RouteRegistrarInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarInterface;
use Illuminate\Support\Facades\Route;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class Bill implements RouteRegistrarInterface
{
    /**
     * @param RegistrarInterface $registrar
	 * description : this function is going to the controller page
     */
    public function register(RegistrarInterface $Registrar)
    {
		// insert data post request
		Route::post('Accounting/Bills/Bill', 'Accounting\Bills\Controllers\BillController@store');
	}
}


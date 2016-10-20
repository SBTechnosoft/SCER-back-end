<?php
namespace ERP\Api\V1_0\Accounting\LedgerGrps\Routes;

use ERP\Api\V1_0\Accounting\LedgerGrps\Controllers\LedgerGrpController;
use ERP\Support\Interfaces\RouteRegistrarInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarInterface;
use Illuminate\Support\Facades\Route;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class LedgerGrp implements RouteRegistrarInterface
{
    /**
     * @param RegistrarInterface $registrar
	 * description : this function is going to the controller page
     */
    public function register(RegistrarInterface $Registrar)
    {
		// all the possible get request 
		Route::group(['as' => 'get'], function ()
		{
			Route::get('Accounting/LedgerGrps/LedgerGrp/{ledgerGrpId?}', 'Accounting\LedgerGrps\Controllers\LedgerGrpController@getData');
		});
	}
}



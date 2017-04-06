<?php
namespace ERP\Api\V1_0\Accounting\Taxation\Routes;

use ERP\Api\V1_0\Accounting\Taxation\Controllers\TaxationController;
use ERP\Support\Interfaces\RouteRegistrarInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarInterface;
use Illuminate\Support\Facades\Route;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class Taxation implements RouteRegistrarInterface
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
			Route::get('Accounting/Taxation/Taxation/sale-tax', 'Accounting\Taxation\Controllers\TaxationController@getSaleTaxData');
			Route::get('Accounting/Taxation/Taxation/purchase-tax', 'Accounting\Taxation\Controllers\TaxationController@getPurchaseTaxData');
		});
		
	}
}



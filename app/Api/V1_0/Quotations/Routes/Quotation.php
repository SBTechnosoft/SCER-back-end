<?php
namespace ERP\Api\V1_0\Quotations\Routes;

use ERP\Api\V1_0\Quotations\Controllers\QuotationController;
use ERP\Support\Interfaces\RouteRegistrarInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarInterface;
use Illuminate\Support\Facades\Route;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class Quotation implements RouteRegistrarInterface
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
			Route::get('Quotations/Quotation/{quotationId?}', 'QuotationController@getData');
			Route::get('Quotations/Quotation/company/{companyId?}', 'QuotationController@getAllData');
		});
		// insert data post request
		Route::post('Quotations/Quotation', 'QuotationController@store');
	}
}



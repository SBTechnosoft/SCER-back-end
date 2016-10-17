<?php
namespace ERP\Api\V1_0\Invoices\Routes;

use ERP\Api\V1_0\Invoices\Controllers\InvoiceController;
use ERP\Support\Interfaces\RouteRegistrarInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarInterface;
use Illuminate\Support\Facades\Route;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class Invoice implements RouteRegistrarInterface
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
			Route::get('Invoices/Invoice/{invoiceId?}', 'InvoiceController@getData');
			Route::get('Invoices/Invoice/company/{companyId?}', 'InvoiceController@getAllData');
		});
		// insert data post request
		Route::post('Invoices/Invoice', 'InvoiceController@store');
	}
}



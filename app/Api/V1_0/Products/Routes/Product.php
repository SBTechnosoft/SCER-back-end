<?php
namespace ERP\Api\V1_0\Products\Routes;

use ERP\Api\V1_0\Products\Controllers\ProductController;
use ERP\Support\Interfaces\RouteRegistrarInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarInterface;
use Illuminate\Support\Facades\Route;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class Product implements RouteRegistrarInterface
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
			Route::get('Products/Product/{productId?}', 'Products\Controllers\ProductController@getData');
			Route::get('Products/Product/company/{companyId?}/branch/{branchId?}', 'Products\Controllers\ProductController@getAllProductData');
		});
		
		// insert data post request
		Route::post('Products/Product', 'Products\Controllers\ProductController@store');
		
		// update data post request
		Route::post('Products/Product/{productId}', 'Products\Controllers\ProductController@update');
		
		//delete data delete request
		Route::delete('Products/Product/{productId}', 'Products\Controllers\ProductController@Destroy');
			
    }
}



<?php
namespace ERP\Api\V1_0\ProductGroups\Routes;

use ERP\Api\V1_0\ProductGroups\Controllers\ProductGroupController;
use ERP\Support\Interfaces\RouteRegistrarInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarInterface;
use Illuminate\Support\Facades\Route;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductGroup implements RouteRegistrarInterface
{
    /**
     * @param RegistrarInterface $registrar
	 * description : this function is going to the controller page
     */
    public function register(RegistrarInterface $Registrar)
    {
		// echo "function ";
		// print_r($Registrar);
		// exit;
        // all the possible get request 
		Route::group(['as' => 'get'], function ()
		{
			Route::get('ProductGroups/ProductGroup/{productGroupId?}', 'ProductGroupController@getData');
		});
		
		// insert data post request
		Route::post('ProductGroups/ProductGroup', 'ProductGroupController@store');
		
		// update data post request
		Route::post('ProductGroups/ProductGroup/{productGroupId}', 'ProductGroupController@update');
		
		//delete data delete request
		Route::delete('ProductGroups/ProductGroup/{productGroupId}', 'ProductGroupController@Destroy');
			
    }
}



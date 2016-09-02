<?php
namespace ERP\Api\V1_0\Sample\Routes;

use ERP\Api\V1_0\Sample\Controllers\BranchController;
use ERP\Support\Interfaces\RouteRegistrarInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarInterface;
use Illuminate\Support\Facades\Route;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class Branch implements RouteRegistrarInterface
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
			Route::get('Sample/Branch/{id?}', 'BranchController@getData');
		});
		
		// insert data post request
		Route::post('Sample/Branch', 'BranchController@store');
		
		// update data patch request
		Route::post('Sample/Branch/{id}', 'BranchController@update');
		
		//delete data delete request
		Route::delete('Sample/Branch/{id}', 'BranchController@Destroy');
			
    }
}



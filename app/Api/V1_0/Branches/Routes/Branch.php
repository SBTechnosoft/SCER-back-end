<?php
namespace ERP\Api\V1_0\Branches\Routes;

use ERP\Api\V1_0\Branches\Controllers\BranchController;
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
			Route::get('Branches/Branch/{branchId?}', 'BranchController@getData');
			Route::get('Branches/Branch/company/{companyId?}', 'BranchController@getAllData');
		});
		// insert data post request
		Route::post('Branches/Branch', 'BranchController@store');
		
		// update data post request
		Route::post('Branches/Branch/{branchId}', 'BranchController@update');
		
		//delete data delete request
		Route::delete('Branches/Branch/{branchId}', 'BranchController@Destroy');
	}
}



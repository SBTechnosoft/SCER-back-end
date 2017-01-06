<?php
namespace ERP\Api\V1_0\Authenticate\Routes;

// use ERP\Api\V1_0\Authenticate\Controllers\StateController;
use ERP\Support\Interfaces\RouteRegistrarInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarInterface;
use Illuminate\Support\Facades\Route;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class Authenticate implements RouteRegistrarInterface
{
    /**
     * @param RegistrarInterface $registrar
	 * description : this function is going to the controller page
     */
    public function register(RegistrarInterface $Registrar)
    {
		echo "route";
		exit;
		// all the possible get request 
		// Route::group(['as' => 'get'], function ()
		// {
			// Route::get('States/State/{stateId?}', 'States\Controllers\StateController@getData');
		// });
		// insert data post request
		// Route::post('States/State', 'States\Controllers\StateController@store');
	}
}



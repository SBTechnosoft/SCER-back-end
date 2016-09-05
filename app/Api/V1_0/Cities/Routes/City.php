<?php
namespace ERP\Api\V1_0\Cities\Routes;

use ERP\Api\V1_0\Cities\Controllers\CityController;
use ERP\Support\Interfaces\RouteRegistrarInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarInterface;
use Illuminate\Support\Facades\Route;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class City implements RouteRegistrarInterface
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
			Route::get('Cities/City/{cityId?}', 'CityController@getData');
			Route::get('Cities/City/state/{stateAbb?}', 'CityController@getAllData');
		});
		
		// insert data post request
		Route::post('Cities/City', 'CityController@store');
		
		// update data post request
		Route::post('Cities/City/{cityId}', 'CityController@update');
		
		//delete data delete request
		Route::delete('Cities/City/{cityId}', 'CityController@Destroy');
			
    }
}



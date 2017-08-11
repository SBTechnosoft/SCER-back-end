<?php
namespace ERP\Api\V1_0\Settings\Professions\Routes;

use ERP\Api\V1_0\Settings\Professions\Controllers\ProfessionController;
use ERP\Support\Interfaces\RouteRegistrarInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarInterface;
use Illuminate\Support\Facades\Route;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class Profession implements RouteRegistrarInterface
{
    /**
     * @param RegistrarInterface $registrar
	 * description : this function is going to the controller page
     */
    public function register(RegistrarInterface $Registrar)
    {
		// all the possible get request 
		// Route::group(['as' => 'get'], function ()
		// {
			// Route::get('Settings/Professions/Profession/{ProfessionId?}', 'Settings\Professions\Controllers\ProfessionController@getData');
			// Route::get('Settings/Professions/Profession/company/{companyId?}', 'Settings\Professions\Controllers\ProfessionController@getProfessionData');
		// });
		
		// insert data post request
		Route::post('Settings/Professions/Profession', 'Settings\Professions\Controllers\ProfessionController@store');
		
		// update data post request
		// Route::post('Settings/Professions/Profession/{ProfessionId}', 'Settings\Professions\Controllers\ProfessionController@update');
	}
}



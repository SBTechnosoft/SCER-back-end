<?php
namespace ERP\Api\V1_0\Settings\Routes;

use ERP\Api\V1_0\Settings\Controllers\SettingController;
use ERP\Support\Interfaces\RouteRegistrarInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarInterface;
use Illuminate\Support\Facades\Route;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class Setting implements RouteRegistrarInterface
{
    /**
     * @param RegistrarInterface $registrar
	 * description : this function is going to the controller page
     */
    public function register(RegistrarInterface $Registrar)
    {
		// insert data post request
		Route::post('Settings/Setting', 'Settings\Controllers\SettingController@store');
		
		// update data post request
		Route::post('Settings/Setting/{settingId}', 'Settings\Controllers\SettingController@update');
	}
}



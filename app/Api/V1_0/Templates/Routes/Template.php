<?php
namespace ERP\Api\V1_0\Templates\Routes;

use ERP\Api\V1_0\Templates\Controllers\TemplateController;
use ERP\Support\Interfaces\RouteRegistrarInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarInterface;
use Illuminate\Support\Facades\Route;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class Template implements RouteRegistrarInterface
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
			Route::get('Templates/Template/{templateId?}', 'TemplateController@getData');
		});
		
		// update data post request
		Route::post('Templates/Template/{templateId}', 'TemplateController@update');
	}
}



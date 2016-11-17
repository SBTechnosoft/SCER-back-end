<?php
namespace ERP\Api\V1_0\Accounting\Journals\Routes;

use ERP\Api\V1_0\Accounting\Journals\Controllers\JournalController;
use ERP\Support\Interfaces\RouteRegistrarInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarInterface;
use Illuminate\Support\Facades\Route;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class Journal implements RouteRegistrarInterface
{
    /**
     * @param RegistrarInterface $registrar
	 * description : this function is going to the controller page
     */
    public function register(RegistrarInterface $Registrar)
    {
		// get request 
		Route::get('Accounting/Journals/Journal/next', 'Accounting\Journals\Controllers\JournalController@getData');
		Route::get('Accounting/Journals/Journal', 'Accounting\Journals\Controllers\JournalController@getSpecificData');
		
		// insert data post request
		Route::post('Accounting/Journals/Journal', 'Accounting\Journals\Controllers\JournalController@store');
	
	}
}



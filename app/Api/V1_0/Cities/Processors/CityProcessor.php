<?php
namespace ERP\Api\V1_0\Cities\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Cities\Persistables\CityPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CityProcessor extends BaseProcessor
{
	/**
     * @var cityPersistable
	 * @var cityName
	 * @var cityId
	 * @var request
     */
	private $cityPersistable;
	private $cityName;
	private $cityId;   
	private $request;    
	
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return City Persistable object
     */	
    public function createPersistable(Request $request)
	{	
		$this->request = $request;	
		// check the requested Http method
		$requestMethod = $_SERVER['REQUEST_METHOD'];
	
		// insert
		if($requestMethod == 'POST')
		{
			$cityName = $request->input('city_name'); 
			$isDisplay = $request->input('is_display'); 			
			$stateAbb = $request->input('state_abb'); 			
			
			$cityPersistable = new CityPersistable();		
			$cityPersistable->setName($cityName);		 
			$cityPersistable->setIsDisplay($isDisplay);		 
			$cityPersistable->setStateAbb($stateAbb);		 
			return $cityPersistable;	
		}		
		else{	
		}		
    }
	public function createPersistableChange(Request $request,$cityId)
	{
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		
		// update
		if($requestMethod == 'POST')
		{
			$cityName = $request->input('city_name'); 
			$stateAbb= $request->input('state_abb'); 
			$isDisplay= $request->input('is_display'); 
			$cityPersistable = new CityPersistable();		
			$cityPersistable->setName($cityName);		 
			$cityPersistable->setStateAbb($stateAbb);		 
			$cityPersistable->setIsDisplay($isDisplay);		 
			$cityPersistable->setId($cityId);		 
			return $cityPersistable;
			
		}
		//delete
		else if($requestMethod == 'DELETE')
		{
			$cityPersistable = new CityPersistable();		
			$cityPersistable->setId($cityId);			
			return $cityPersistable;
		}
	}	
}
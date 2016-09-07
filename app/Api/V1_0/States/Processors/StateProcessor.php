<?php
namespace ERP\Api\V1_0\States\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\States\Persistables\StatePersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class StateProcessor extends BaseProcessor
{
	/**
     * @var statePersistable
	 * @var request
     */
	private $statePersistable;
	private $request;    
	
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return State Persistable object
     */	
    public function createPersistable(Request $request)
	{	
		$this->request = $request;	
		// check the requested Http method
		$requestMethod = $_SERVER['REQUEST_METHOD'];
	
		// insert
		if($requestMethod == 'POST')
		{
			$stateName = $request->input('state_name'); 
			$stateAbb = $request->input('state_abb'); 
			$isDisplay = $request->input('is_display'); 
			$statePersistable = new StatePersistable();		
			$statePersistable->setName($stateName);		 
			$statePersistable->setStateAbb($stateAbb);		 
			$statePersistable->setIsDisplay($isDisplay);		 
			return $statePersistable;	
		}		
		else{	
		}		
    }
	
	/**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * $param state_abb
     * @return State Persistable object
     */
	public function createPersistableChange(Request $request,$stateAbb)
	{
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		// update
		if($requestMethod == 'POST')
		{
			$stateName = $request->input('state_name'); 
			$isDisplay = $request->input('is_display'); 
			$statePersistable = new StatePersistable();		
			$statePersistable->setName($stateName);		 
			$statePersistable->setStateAbb($stateAbb);		 
			$statePersistable->setIsDisplay($isDisplay);		 
			return $statePersistable;
		}
		//delete
		else if($requestMethod == 'DELETE')
		{
			$statePersistable = new StatePersistable();		
			$statePersistable->setStateAbb($stateAbb);			
			return $statePersistable;
		}
	}	
}
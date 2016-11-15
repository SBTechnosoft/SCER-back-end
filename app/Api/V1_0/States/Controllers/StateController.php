<?php
namespace ERP\Api\V1_0\States\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\States\Services\StateService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\States\Processors\StateProcessor;
use ERP\Core\States\Persistables\StatePersistable;
use ERP\Core\Support\Service\ContainerInterface;
use ERP\Model\States\StateModel;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class StateController extends BaseController implements ContainerInterface
{
	/**
     * @var stateService
     * @var processor
     * @var request
     * @var statePersistable
     */
	private $stateService;
	private $processor;
	private $request;
	private $statePersistable;	
	
	/**
	 * get and invoke method is of ContainerInterface method
	 */		
    public function get($id,$name)
	{
		// echo "get";
	}
	public function invoke(callable $method)
	{
		// echo "invoke";
	}
	
	/**
	 * insert the specified resource 
	 * @param  Request object[Request $request]
	 * method calls the processor for creating persistable object & setting the data
	*/
    public function store(Request $request)
    {
		$this->request = $request;
		// check the requested Http method
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		// insert
		if($requestMethod == 'POST')
		{
			$processor = new StateProcessor();
			$statePersistable = new StatePersistable();		
			$stateService= new StateService();			
			$statePersistable = $processor->createPersistable($this->request);
			
			if($statePersistable[0][0]=='[')
			{
				return $statePersistable;
			}
			else if(is_array($statePersistable))
			{
				$status = $stateService->insert($statePersistable);
				return $status;
			}
			else
			{
				return $statePersistable;
			}
		}
		else
		{
			return $status;
		}
	}
	
	/**
     * get the specified resource.
     * @param  state_id
     */
    public function getData($stateId=null)
    {
		if($stateId==null)
		{			
			$stateService= new StateService();
			$status = $stateService->getAllStateData();
			return $status;
		}
		else
		{	
			$stateService= new StateService();
			$status = $stateService->getStateData($stateId);
			return $status;
		}        
    }
	
    /**
     * Update the specified resource in storage.
     * @param  Request object[Request $request]
     * @param  state_abb
     */
	public function update(Request $request,$stateAbb)
    {    
		$this->request = $request;
		$processor = new StateProcessor();
		$statePersistable = new StatePersistable();		
		$stateService= new StateService();
		$stateModel = new StateModel();	
		$result = $stateModel->getData($stateAbb);
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		
		if(strcmp($result,$fileSizeArray['404'])==0)
		{
			return $fileSizeArray['404'];
		}
		else
		{
			$statePersistable = $processor->createPersistableChange($this->request,$stateAbb);
			
			if(is_array($statePersistable))
			{
				$status = $stateService->update($statePersistable);
				return $status;
			}
			else
			{
				return $statePersistable;
			}
		}
	}
	
    /**
     * Remove the specified resource from storage.
     * @param  Request object[Request $request]     
     * @param  state_abb     
     */
    public function Destroy(Request $request,$stateAbb)
    {
        $this->request = $request;
		$processor = new StateProcessor();
		$statePersistable = new StatePersistable();		
		$stateService= new StateService();	
		
		$stateModel = new StateModel();	
		$result = $stateModel->getData($stateAbb);
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		
		if(strcmp($result,$fileSizeArray['404'])==0)
		{
			return $fileSizeArray['404'];
		}
		else
		{		
			$statePersistable = $processor->createPersistableChange($this->request,$stateAbb);
			$status = $stateService->delete($statePersistable);
			return $status;
		}
    }
}

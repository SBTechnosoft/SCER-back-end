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
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class StateController extends BaseController implements ContainerInterface
{
	/**
     * @var stateService
     * @var Processor
     * @var request
     * @var statePersistable
     */
	private $stateService;
	private $Processor;
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
		$Processor = new StateProcessor();
		$statePersistable = new StatePersistable();		
		$stateService= new StateService();			
		$statePersistable = $Processor->createPersistable($this->request);
		$stateService->create($statePersistable);
		$status = $stateService->insert($statePersistable);
		return $status;
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
		$Processor = new StateProcessor();
		$statePersistable = new StatePersistable();		
		$stateService= new StateService();			
		$statePersistable = $Processor->createPersistableChange($this->request,$stateAbb);
		$stateService->create($statePersistable);
		$status = $stateService->update($statePersistable);
		return $status;
    }
	
    /**
     * Remove the specified resource from storage.
     * @param  Request object[Request $request]     
     * @param  state_abb     
     */
    public function Destroy(Request $request,$stateAbb)
    {
        $this->request = $request;
		$Processor = new StateProcessor();
		$statePersistable = new StatePersistable();		
		$stateService= new StateService();			
		$statePersistable = $Processor->createPersistableChange($this->request,$stateAbb);
		$stateService->create($statePersistable);
		$status = $stateService->delete($statePersistable);
		return $status;
    }
}

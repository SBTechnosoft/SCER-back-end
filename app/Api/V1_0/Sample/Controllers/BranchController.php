<?php
namespace ERP\Api\V1_0\Sample\Controllers;
use Illuminate\Http\Request;
use ERP\Core\Sample\Services\BranchService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use Illuminate\Http\Response;
use ERP\Api\V1_0\Sample\Processors\BranchProcessor;
use ERP\Core\Sample\Persistables\BranchPersistable;
use ERP\Core\Support\Service\ContainerInterface;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BranchController extends BaseController implements ContainerInterface
{
	/**
     * @var branchService
     * @var Processor
     * @var name
     * @var request
     * @var branchPersistable
     */
	private $branchService;
	private $Processor;
	private $name;
	private $request;
	private $branchPersistable;	
	
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
		$Processor = new BranchProcessor();
		$branchPersistable = new BranchPersistable();		
		$branchService= new BranchService();			
		$branchPersistable = $Processor->createPersistable($this->request);
		$branchService->create($branchPersistable);
		$status = $branchService->insert($branchPersistable);
		return $status;
	}
	
	/**
     * get the specified resource.
     * @param  int  $id
     */
	public function getData($id=null)
    {
		if($id==null)
		{			
			$branchService= new BranchService();
			$status = $branchService->getAllBranchData();
			// return $status;
		}
		else
		{	
			$branchService= new BranchService();
			$status = $branchService->getBranchData($id);
			return $status;
		}        
    } 
    
    /**
     * Update the specified resource in storage.
     * @param  Request object[Request $request]
     */
	public function update(Request $request,$id)
    {  
		$this->request = $request;
		$Processor = new BranchProcessor();
		$branchPersistable = new BranchPersistable();		
		$branchService= new BranchService();			
		$branchPersistable = $Processor->createPersistableChange($this->request,$id);
		$branchService->create($branchPersistable);
		$status = $branchService->update($branchPersistable);
		return $status;
    }
	
    /**
     * Remove the specified resource from storage.
     * @param  Request object[Request $request]     
     */
    public function Destroy(Request $request,$id)
    {
        $this->request = $request;
		$Processor = new BranchProcessor();
		$branchPersistable = new BranchPersistable();		
		$branchService= new BranchService();			
		$branchPersistable = $Processor->createPersistableChange($this->request,$id);
		$branchService->create($branchPersistable);
		$status = $branchService->delete($branchPersistable);
		return $status;
    }
}

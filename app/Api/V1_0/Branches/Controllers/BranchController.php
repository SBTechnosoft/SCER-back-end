<?php
namespace ERP\Api\V1_0\Branches\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Branches\Services\BranchService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Branches\Processors\BranchProcessor;
use ERP\Core\Branches\Persistables\BranchPersistable;
use ERP\Core\Support\Service\ContainerInterface;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BranchController extends BaseController implements ContainerInterface
{
	/**
     * @var branchService
     * @var Processor
     * @var request
     * @var branchPersistable
     */
	private $branchService;
	private $Processor;
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
     * @param  int  $branchId
     */
    public function getData($branchId=null)
    {
		if($branchId==null)
		{	
			$branchService= new BranchService();
			$status = $branchService->getAllBranchData();
			return $status;
		}
		else
		{	
			$branchService= new BranchService();
			$status = $branchService->getBranchData($branchId);
			return $status;
		}        
    }
	
	/**
     * get the specified resource.
     * @param  int  $companyId
     */
    public function getAllData($companyId=null)
    {
		if($companyId=="null")
		{
			$branchService= new BranchService();
			$status = $branchService->getAllBranchData();
			return $status;
		}
		else
		{
			$branchService= new BranchService();
			$status = $branchService->getAllData($companyId);
			return $status;
		}
	}
	
    /**
     * Update the specified resource in storage.
     * @param  Request object[Request $request]
     * @param  branch_id
     */
	public function update(Request $request,$branchId)
    {    
		$this->request = $request;
		$Processor = new BranchProcessor();
		$branchPersistable = new BranchPersistable();		
		$branchService= new BranchService();			
		$branchPersistable = $Processor->createPersistableChange($this->request,$branchId);
		$branchService->create($branchPersistable);
		$status = $branchService->update($branchPersistable);
		return $status;
    }
	
    /**
     * Remove the specified resource from storage.
     * @param  Request object[Request $request]     
     * @param  branch_id     
     */
    public function Destroy(Request $request,$branchId)
    {
        $this->request = $request;
		$Processor = new BranchProcessor();
		$branchPersistable = new BranchPersistable();		
		$branchService= new BranchService();			
		$branchPersistable = $Processor->createPersistableChange($this->request,$branchId);
		$branchService->create($branchPersistable);
		$status = $branchService->delete($branchPersistable);
		return $status;
    }
}

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
use ERP\Exceptions\ExceptionMessage;
use ERP\Model\Branches\BranchModel;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BranchController extends BaseController implements ContainerInterface
{
	/**
     * @var branchService
     * @var processor
     * @var request
     * @var branchPersistable
     */
	private $branchService;
	private $processor;
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
		// check the requested Http method
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		// insert
		if($requestMethod == 'POST')
		{
			$processor = new BranchProcessor();
			$branchPersistable = new BranchPersistable();		
			$branchService= new BranchService();			
			$branchPersistable = $processor->createPersistable($this->request);
			
			if($branchPersistable[0][0]=='[')
			{
				return $branchPersistable;
			}
			else if(is_array($branchPersistable))
			{
				$status = $branchService->insert($branchPersistable);
				return $status;
			}
			else
			{
				return $branchPersistable;
			}
		}
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
		$processor = new BranchProcessor();
		$branchPersistable = new BranchPersistable();		
		$branchService= new BranchService();	
		$branchModel = new BranchModel();	
		$result = $branchModel->getData($branchId);
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(strcmp($result,$fileSizeArray['404'])==0)
		{
			return $result;
		}
		else
		{
			$branchPersistable = $processor->createPersistableChange($this->request,$branchId);
			//here two array and string is return at a time
			if(is_array($branchPersistable))
			{
				$status = $branchService->update($branchPersistable);
				return $status;
			}
			else
			{
				return $branchPersistable;
			}
		}
		
	}
	
    /**
     * Remove the specified resource from storage.
     * @param  Request object[Request $request]     
     * @param  branch_id     
     */
    public function Destroy(Request $request,$branchId)
    {
        $this->request = $request;
		$processor = new BranchProcessor();
		$branchPersistable = new BranchPersistable();		
		$branchService= new BranchService();	
		$branchModel = new BranchModel();	
		$result = $branchModel->getData($branchId);
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(strcmp($result,$fileSizeArray['404'])==0)
		{
			return $result;
		}
		else
		{		
			$branchPersistable = $processor->createPersistableChange($this->request,$branchId);
			$branchService->create($branchPersistable);
			$status = $branchService->delete($branchPersistable);
			return $status;
		}
    }
}

<?php
namespace ERP\Api\V1_0\Companies\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Companies\Services\CompanyService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
// use ERP\Api\V1_0\Companies\Processors\CompanyProcessor;
// use ERP\Core\Companies\Persistables\CompanyPersistable;
use ERP\Core\Support\Service\ContainerInterface;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CompanyController extends BaseController implements ContainerInterface
{
	/**
     * @var companyService
     * @var Processor
     * @var name
     * @var request
     * @var branchPersistable
     */
	private $companyService;
	private $Processor;
	private $companyName;
	private $request;
	private $companyPersistable;	
	
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
     * @param  int  $companyId
     */
    public function getData($companyId=null)
    {
		if($companyId==null)
		{			
			$companyService= new CompanyService();
			$status = $companyService->getAllCompanyData();
			return $status;
		}
		else
		{	
			$companyService= new CompanyService();
			$status = $companyService->getCompanyData($companyId);
			return $status;
		}        
    }
	
    /**
     * Update the specified resource in storage.
     * @param  Request object[Request $request]
     */
	public function update(Request $request)
    {    
		$this->request = $request;
		$data = json_decode(file_get_contents("php://input"));
		$Processor = new BranchProcessor();
		$branchPersistable = new BranchPersistable();		
		$branchService= new BranchService();			
		$branchPersistable = $Processor->createPersistableUpdate($this->request,$data);
		$branchService->create($branchPersistable);
		$status = $branchService->update($branchPersistable);
		return $status;
    }
	
    /**
     * Remove the specified resource from storage.
     * @param  Request object[Request $request]     
     */
    public function Destroy(Request $request)
    {
        $this->request = $request;
		$data = json_decode(file_get_contents("php://input"));
		$Processor = new BranchProcessor();
		$branchPersistable = new BranchPersistable();		
		$branchService= new BranchService();			
		$branchPersistable = $Processor->createPersistableUpdate($this->request,$data);
		$branchService->create($branchPersistable);
		$status = $branchService->delete($branchPersistable);
		return $status;
    }
}

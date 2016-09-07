<?php
namespace ERP\Api\V1_0\Companies\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Companies\Services\CompanyService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Companies\Processors\CompanyProcessor;
use ERP\Core\Companies\Persistables\CompanyPersistable;
use ERP\Core\Support\Service\ContainerInterface;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CompanyController extends BaseController implements ContainerInterface
{
	/**
     * @var companyService
     * @var Processor
     * @var request
     * @var companyPersistable
     */
	private $companyService;
	private $processor;
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
		$Processor = new CompanyProcessor();
		$companyPersistable = new CompanyPersistable();		
		$companyService= new CompanyService();			
		$companyPersistable = $Processor->createPersistable($this->request);
		$companyService->create($companyPersistable);
		$status = $companyService->insert($companyPersistable);
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
     * @param  company_id 
     */
	public function update(Request $request,$companyId)
    {    
		$this->request = $request;
		$Processor = new CompanyProcessor();
		$companyPersistable = new CompanyPersistable();		
		$companyService= new CompanyService();			
		$companyPersistable = $Processor->createPersistableChange($this->request,$companyId);
		$companyService->create($companyPersistable);
		$status = $companyService->update($companyPersistable);
		return $status;
    }
	
    /**
     * Remove the specified resource from storage.
     * @param  Request object[Request $request]     
     * @param  company_id     
     */
    public function destroy(Request $request,$companyId)
    {
        $this->request = $request;
		$Processor = new CompanyProcessor();
		$companyPersistable = new CompanyPersistable();		
		$companyService= new CompanyService();			
		$companyPersistable = $Processor->createPersistableChange($this->request,$companyId);
		$companyService->create($companyPersistable);
		$status = $companyService->delete($companyPersistable);
		return $status;
    }
}

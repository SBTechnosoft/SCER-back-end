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
use ERP\Core\Companies\Validations\CompanyValidate;
use ERP\Exceptions\ExceptionMessage;
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
		// check the requested Http method
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		// insert
		if($requestMethod == 'POST')
		{
			$Processor = new CompanyProcessor();
			$companyPersistable = new CompanyPersistable();		
			$companyService= new CompanyService();			
			$companyPersistable = $Processor->createPersistable($this->request);
			
			//get exception message
			$exception = new ExceptionMessage();
			$fileSizeArray = $exception->messageArrays();
			
			if($companyPersistable[0][0]=='[')
			{
				return $companyPersistable;
			}
			else if(strcmp($companyPersistable,$fileSizeArray['fileFormat'])==0 || strcmp($companyPersistable,$fileSizeArray['fileSize'])==0)
			{
				return $companyPersistable;
			}
			else
			{
				$status = $companyService->insert($companyPersistable);
				return $status;
			}
		}
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
		$companyPersistable = $Processor->createPersistableChange($this->request,$companyId);
		
		//get exception message 
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(strcmp($companyPersistable,$fileSizeArray['204'])==0 || strcmp($companyPersistable,$fileSizeArray['fileSize'])==0 || strcmp($companyPersistable,$fileSizeArray['fileFormat'])==0)
		{
			return $companyPersistable;
		}
		else if(is_array($companyPersistable))
		{
			$companyService= new CompanyService();
			$status = $companyService->update($companyPersistable);
			return $status;
		}
		else
		{
			return $companyPersistable;
		}
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
		$status = $companyService->delete($companyPersistable);
		return $status;
    }
}

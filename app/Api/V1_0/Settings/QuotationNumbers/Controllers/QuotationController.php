<?php
namespace ERP\Api\V1_0\Settings\QuotationNumbers\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Settings\QuotationNumbers\Services\QuotationService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Settings\QuotationNumbers\Processors\QuotationProcessor;
use ERP\Core\Settings\QuotationNumbers\Persistables\QuotationPersistable;
use ERP\Core\Support\Service\ContainerInterface;
use ERP\Model\Settings\QuotationNumbers\QuotationModel;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class QuotationController extends BaseController implements ContainerInterface
{
	/**
     * @var quotationService
     * @var Processor
     * @var request
     * @var quotationPersistable
     */
	private $quotationService;
	private $Processor;
	private $request;
	private $quotationPersistable;	
	
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
			$processor = new QuotationProcessor();
			$quotationPersistable = new QuotationPersistable();		
			$quotationService= new QuotationService();			
			$quotationPersistable = $processor->createPersistable($this->request);
			if($quotationPersistable[0][0]=='[')
			{
				return $quotationPersistable;
			}
			else if(is_array($quotationPersistable))
			{
				$status = $quotationService->insert($quotationPersistable);
				return $status;
			}
			else
			{
				return $quotationPersistable;
			}
		}
	}
	
	/**
     * Update the specified resource in storage.
     * @param  Request object[Request $request]
     * @param  ledger_id
     */
	public function update(Request $request,$quotationId)
    {    
		$this->request = $request;
		$processor = new QuotationProcessor();
		$quotationPersistable = new QuotationPersistable();		
		$quotationService= new QuotationService();		
		$quotationModel = new QuotationModel();
		$result = $quotationModel->getData($quotationId);
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
	
		if(strcmp($result,$exceptionArray['404'])==0)
		{
			return $result;
		}
		else
		{
			$quotationPersistable = $processor->createPersistableChange($this->request,$quotationId);
			
			//here two array and string is return at a time
			if(is_array($quotationPersistable))
			{
				$status = $quotationService->update($quotationPersistable);
				return $status;
			}
			else
			{
				return $quotationPersistable;
			}
		}
	}
	
	/**
     * get the specified resource.
     * @param  int  $quotationId
     */
    public function getData($quotationId=null)
    {
		$quotationService= new QuotationService();
		if($quotationId==null)
		{	
			$status = $quotationService->getAllQuotationData();
			return $status;
		}
		else
		{	
			$status = $quotationService->getQuotationData($quotationId);
			return $status;
		}        
    }
	
	/**
     * get the specified resource.
     * @param  int  $companyId
     */
    public function getAllData($companyId=null)
    {
		$quotationService= new QuotationService();
		if($companyId=="null")
		{
			
			$status = $quotationService->getAllQuotationData();
			return $status;
		}
		else
		{
			$status = $quotationService->getAllData($companyId);
			return $status;
		}
	}
	
	/**
     * get the latest quotation number data.
     * @param  int  $companyId
     */
    public function getLatestData($companyId)
    {
		$quotationService= new QuotationService();
		$status = $quotationService->getLatestQuotationData($companyId);
		return $status;
	}
}

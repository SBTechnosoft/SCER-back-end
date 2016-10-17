<?php
namespace ERP\Api\V1_0\Quotations\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Quotations\Services\QuotationService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Quotations\Processors\QuotationProcessor;
use ERP\Core\Quotations\Persistables\QuotationPersistable;
use ERP\Core\Support\Service\ContainerInterface;
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
			$Processor = new QuotationProcessor();
			$quotationPersistable = new QuotationPersistable();		
			$quotationService= new QuotationService();			
			$quotationPersistable = $Processor->createPersistable($this->request);
			if($quotationPersistable[0][0]=='[')
			{
				return $quotationPersistable;
			}
			else
			{
				$status = $quotationService->insert($quotationPersistable);
				return $status;
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
}

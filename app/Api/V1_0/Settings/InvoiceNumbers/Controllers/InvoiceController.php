<?php
namespace ERP\Api\V1_0\Settings\InvoiceNumbers\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Settings\InvoiceNumbers\Services\InvoiceService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Settings\InvoiceNumbers\Processors\InvoiceProcessor;
use ERP\Core\Settings\InvoiceNumbers\Persistables\InvoicePersistable;
use ERP\Core\Support\Service\ContainerInterface;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class InvoiceController extends BaseController implements ContainerInterface
{
	/**
     * @var invoiceService
     * @var processor
     * @var request
     * @var invoicePersistable
     */
	private $invoiceService;
	private $processor;
	private $request;
	private $invoicePersistable;	
	
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
			$processor = new InvoiceProcessor();
			$invoicePersistable = new InvoicePersistable();		
			$invoiceService= new InvoiceService();			
			$invoicePersistable = $processor->createPersistable($this->request);
			if($invoicePersistable[0][0]=='[')
			{
				return $invoicePersistable;
			}
			else
			{
				$status = $invoiceService->insert($invoicePersistable);
				return $status;
			}
		}
	}
	
	/**
     * get the specified resource.
     * @param  int  $invoiceId
     */
    public function getData($invoiceId=null)
    {
		if($invoiceId==null)
		{	
			$invoiceService= new InvoiceService();
			$status = $invoiceService->getAllInvoiceData();
			return $status;
		}
		else
		{	
			$invoiceService= new InvoiceService();
			$status = $invoiceService->getInvoiceData($invoiceId);
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
			$invoiceService= new InvoiceService();
			$status = $invoiceService->getAllInvoiceData();
			return $status;
		}
		else
		{
			$invoiceService= new InvoiceService();
			$status = $invoiceService->getAllData($companyId);
			return $status;
		}
	}
	
	/**
     * get the latest invoice number data.
     * @param  int  $companyId
     */
    public function getLatestData($companyId)
    {
		$invoiceService= new InvoiceService();
		$status = $invoiceService->getLatestInvoiceData($companyId);
		return $status;
	}
}
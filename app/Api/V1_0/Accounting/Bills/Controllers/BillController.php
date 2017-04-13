<?php
namespace ERP\Api\V1_0\Accounting\Bills\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Accounting\Bills\Services\BillService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Accounting\Bills\Processors\BillProcessor;
use ERP\Core\Accounting\Bills\Persistables\BillPersistable;
use ERP\Core\Support\Service\ContainerInterface;
use ERP\Exceptions\ExceptionMessage;
use ERP\Core\Settings\Templates\Services\TemplateService;
use ERP\Core\Accounting\Bills\Entities\BillMpdf;
use ERP\Entities\AuthenticationClass\TokenAuthentication;
use ERP\Entities\Constants\ConstantClass;
use ERP\Core\Settings\Templates\Entities\TemplateTypeEnum;
use ERP\Core\Settings\InvoiceNumbers\Services\InvoiceService;
use ERP\Api\V1_0\Settings\InvoiceNumbers\Controllers\InvoiceController;
use Illuminate\Container\Container;
use ERP\Api\V1_0\Documents\Controllers\DocumentController;
use ERP\Model\Accounting\Bills\BillModel;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BillController extends BaseController implements ContainerInterface
{
	/**
     * @var billService
     * @var processor
     * @var request
     * @var billPersistable
     */
	private $billService;
	private $processor;
	private $request;
	private $billPersistable;	
	
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
		//Authentication
		$tokenAuthentication = new TokenAuthentication();
		$authenticationResult = $tokenAuthentication->authenticate($request->header());
		
		//get constant array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		if(strcmp($constantArray['success'],$authenticationResult)==0)
		{
			$this->request = $request;
			// check the requested Http method
			$requestMethod = $_SERVER['REQUEST_METHOD'];

			// get exception message
			$exception = new ExceptionMessage();
			$msgArray = $exception->messageArrays();
			
			// insert
			if($requestMethod == 'POST')
			{
				if(count($_POST)==0)
				{
					return $msgArray['204'];
				}
				else
				{
					$processor = new BillProcessor();
					$billPersistable = new BillPersistable();
					$billPersistable = $processor->createPersistable($this->request);
					if(is_array($billPersistable) || is_object($billPersistable))
					{
						$billService= new BillService();
						$status = $billService->insert($billPersistable);
						if(strcmp($status,$msgArray['500'])==0)
						{
							return $status;
						}
						else
						{
							$decodedSaleData = json_decode($status);
							$invoiceService = new InvoiceService();	
							$invoiceData = $invoiceService->getLatestInvoiceData($decodedSaleData->company->companyId);
							if(strcmp($msgArray['204'],$invoiceData)==0)
							{
								return $invoiceData;
							}
							$endAt = json_decode($invoiceData)->endAt;
							$invoiceController = new InvoiceController(new Container());
							$invoiceMethod=$constantArray['postMethod'];
							$invoicePath=$constantArray['invoiceUrl'];
							$invoiceDataArray = array();
							$invoiceDataArray['endAt'] = $endAt+1;
							
							$invoiceRequest = Request::create($invoicePath,$invoiceMethod,$invoiceDataArray);
							$updateResult = $invoiceController->update($invoiceRequest,json_decode($invoiceData)->invoiceId);
							
							$saleId = $decodedSaleData->saleId;
							$saleIdArray = array();
							$saleIdArray['saleId'] = $saleId;
							$documentController = new DocumentController(new Container());
							$method=$constantArray['postMethod'];
							$path=$constantArray['documentGenerateUrl'];
							$documentRequest = Request::create($path,$method,$saleIdArray);
							if(array_key_exists('operation',$request->header()))
							{
								$documentRequest->headers->set('operation',$request->header()['operation'][0]);
							}
							else
							{
								$documentRequest->headers->set('key',$request->header());
							}
							$processedData = $documentController->getData($documentRequest);
							return $processedData;
						}
					}
					else
					{
						return $billPersistable;
					}
				}
			}
		}
		else
		{
			return $authenticationResult;
		}
	}
	
	/**
	 * get the specified resource 
	 * @param  Request object[Request $request] and companyId
	 * method calls the processor for creating persistable object & setting the data
	*/
	public function getData(Request $request,$companyId)
	{
		//Authentication
		$tokenAuthentication = new TokenAuthentication();
		$authenticationResult = $tokenAuthentication->authenticate($request->header());
		
		// get constant array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		if(strcmp($constantArray['success'],$authenticationResult)==0)
		{
			$processor = new BillProcessor();
			$billPersistable = new BillPersistable();
			$billPersistable = $processor->getPersistableData($request->header());
			
			if(is_object($billPersistable))
			{
				$billService= new BillService();
				$status = $billService->getData($billPersistable,$companyId);
				return $status;
			}
			else
			{
				return $billPersistable;
			}
		}
		else
		{
			return $authenticationResult;
		}
	}
	
	/**
	 * get the Previos-next data
	 * @param  Request object[Request $request]
	 * @return array-data/error message
	*/
	public function getPreviosNextData(Request $request)
	{
		//Authentication
		$tokenAuthentication = new TokenAuthentication();
		$authenticationResult = $tokenAuthentication->authenticate($request->header());
		
		// get constant array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		if(strcmp($constantArray['success'],$authenticationResult)==0)
		{
			$billService= new BillService();
			$status = $billService->getPreviousNextData($request->header());
			return $status;
		}
		else
		{
			return $authenticationResult;
		}
	}
	
	/**
	 * update the specified resource (bill-payment)
	 * @param  Request object[Request $request]
	 * method calls the processor for creating persistable object & setting the data
	*/
	public function updateBillPayment(Request $request,$saleId)
	{
		//Authentication
		$tokenAuthentication = new TokenAuthentication();
		$authenticationResult = $tokenAuthentication->authenticate($request->header());
		
		// get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();
		
		// get constant array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		if(strcmp($constantArray['success'],$authenticationResult)==0)
		{
			$processor = new BillProcessor();
			$billPersistable = new BillPersistable();
			$billPersistable = $processor->getPersistablePaymentData($request,$saleId);
			
			if(is_object($billPersistable))
			{
				$billService= new BillService();
				$status = $billService->updatePaymentData($billPersistable);
				if(strcmp($status,$msgArray['200'])==0)
				{
					$saleIdArray = array();
					$saleIdArray['saleId'] = $saleId;
					$documentController = new DocumentController(new Container());
					
					$method=$constantArray['postMethod'];
					$path=$constantArray['documentGenerateUrl'];
					$documentRequest = Request::create($path,$method,$saleIdArray);
					$processedData = $documentController->getData($documentRequest);
					return $processedData;
				}
			}
			else
			{
				return $billPersistable;
			}
		}
		else
		{
			return $authenticationResult;
		}
	}
	
	/**
	 * update the specified resource 
	 * @param  Request object[Request $request]
	 * method calls the processor for creating persistable object & setting the data
	*/
	public function update(Request $request,$saleId)
	{
		//Authentication
		$tokenAuthentication = new TokenAuthentication();
		$authenticationResult = $tokenAuthentication->authenticate($request->header());
		
		// get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();
			
		// get constant array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		if(strcmp($constantArray['success'],$authenticationResult)==0)
		{
			//check the condition for image or data or both available
			if(empty($request->input()) && in_array(true,$request->file()) || !empty($request->input()))
			{
				//check saleId exist or not?
				$billModel = new BillModel();
				$billData = $billModel->getSaleIdData($saleId);
				if(strcmp($billData,$msgArray['404'])==0)
				{
					return $msgArray['404'];
				}
				$processor = new BillProcessor();
				$billPersistable = new BillPersistable();
				$billPersistable = $processor->createPersistableChange($request,$saleId,$billData);
				
				if(is_array($billPersistable))
				{
					$billService= new BillService();
					$status = $billService->updateData($billPersistable,$saleId);
					return $status;
				}
				else
				{
					return $billPersistable;
				}
			}
			else
			{
				return $msgArray['204'];
			}
		}
		else
		{
			return $authenticationResult;
		}
	}
	
	/**
	 * update the specified resource 
	 * @param  Request object[Request $request]
	 * method calls the processor for creating persistable object & setting the data
	*/
	public function destroy(Request $request,$saleId)
	{
		//Authentication
		$tokenAuthentication = new TokenAuthentication();
		$authenticationResult = $tokenAuthentication->authenticate($request->header());
		
		// get constant array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		if(strcmp($constantArray['success'],$authenticationResult)==0)
		{
			$billModel = new BillModel();
			$deleteBillResult = $billModel->deleteBillData($saleId);
			return $deleteBillResult;
		}
		else
		{
			return $authenticationResult;
		}
	}
}

<?php
namespace ERP\Api\V1_0\Accounting\Quotations\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Accounting\Quotations\Services\QuotationService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Accounting\Quotations\Processors\QuotationProcessor;
use ERP\Core\Accounting\Quotations\Persistables\QuotationPersistable;
use ERP\Core\Support\Service\ContainerInterface;
use ERP\Exceptions\ExceptionMessage;
use ERP\Core\Settings\Templates\Services\TemplateService;
// use ERP\Core\Accounting\Quotation\Entities\BillMpdf;
use ERP\Entities\AuthenticationClass\TokenAuthentication;
use ERP\Entities\Constants\ConstantClass;
use ERP\Core\Settings\Templates\Entities\TemplateTypeEnum;
use ERP\Core\Settings\InvoiceNumbers\Services\InvoiceService;
// use ERP\Api\V1_0\Settings\InvoiceNumbers\Controllers\InvoiceController;
use Illuminate\Container\Container;
use ERP\Api\V1_0\Documents\Controllers\DocumentController;
// use ERP\Model\Accounting\Quotation\QuotationModel;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class QuotationController extends BaseController implements ContainerInterface
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
					$processor = new QuotationProcessor();
					$quotationPersistable = new QuotationPersistable();
					$quotationPersistable = $processor->createPersistable($this->request);
					
					if(is_object($quotationPersistable))
					{
						$quotationService= new QuotationService();
						$status = $quotationService->insert($quotationPersistable);
						if(strcmp($status,$msgArray['500'])==0)
						{
							return $status;
						}
						else
						{
							$decodedQuotationData = json_decode($status);
							$quotationBillId = $decodedQuotationData->quotationBillId;
							$quotationBillIdArray = array();
							$quotationBillIdArray['quotationBillId'] = $quotationBillId;
							$quotationBillIdArray['companyId'] = $decodedQuotationData->company->companyId;
							$quotationBillIdArray['quotationData'] = $decodedQuotationData;
							$documentController = new DocumentController(new Container());
							$method=$constantArray['postMethod'];
							$path=$constantArray['documentGenerateQuotationUrl'];
							$documentRequest = Request::create($path,$method,$quotationBillIdArray);
							$processedData = $documentController->getQuotationData($documentRequest);
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
}

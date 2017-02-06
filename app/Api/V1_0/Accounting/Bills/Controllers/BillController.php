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
							$templateType = new TemplateTypeEnum();
							$templateArray = $templateType->enumArrays();
							$templateType = $templateArray['invoiceTemplate'];
							$templateService = new TemplateService();
							$templateData = $templateService->getSpecificData($request->input()['companyId'],$templateType);
							
							if(strcmp($templateData,$msgArray['404'])==0)
							{
								return $templateData;
							}
							else
							{
								$billMpdf = new BillMpdf();
								$billPdf = $billMpdf->mpdfGenerate($templateData,$status);
								return $billPdf;
							}
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
}

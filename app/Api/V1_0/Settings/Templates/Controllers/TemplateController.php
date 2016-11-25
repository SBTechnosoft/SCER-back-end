<?php
namespace ERP\Api\V1_0\Settings\Templates\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Settings\Templates\Services\TemplateService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Settings\Templates\Processors\TemplateProcessor;
use ERP\Core\Settings\Templates\Persistables\TemplatePersistable;
use ERP\Core\Support\Service\ContainerInterface;
use ERP\Exceptions\ExceptionMessage;
use ERP\Model\Settings\Templates\TemplateModel;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class TemplateController extends BaseController implements ContainerInterface
{
	/**
     * @var templateService
     * @var processor
     * @var request
     * @var templatePersistable
     */
	private $templateService;
	private $processor;
	private $request;
	private $templatePersistable;	
	
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
			$processor = new TemplateProcessor();
			$templatePersistable = new TemplatePersistable();		
			$templateService= new TemplateService();			
			$templatePersistable = $processor->createPersistable($this->request);
			
			if($templatePersistable[0][0]=='[')
			{
				return $templatePersistable;
			}
			else if(is_array($templatePersistable))
			{
				$status = $templateService->insert($templatePersistable);
				return $status;
			}
			else
			{
				return $templatePersistable;
			}
		}
	}
	
	/**
     * get the specified resource.
     * @param  int  $templateId
     */
    public function getData($templateId=null)
    {
		if($templateId==null)
		{	
			$templateService= new TemplateService();
			$status = $templateService->getAllTemplateData();
			return $status;
		}
		else
		{	
			$templateService= new TemplateService();
			$status = $templateService->getTemplateData($templateId);
			return $status;
		}        
    }
	
	/**
     * get the specified resource.
     * @param  int  $companyId
     */
    public function getTemplateData($companyId)
    {
		$templateService= new TemplateService();
		$status = $templateService->getSpecificData($companyId);
		return $status;
	}
	
	/**
     * Update the specified resource in storage.
     * @param  Request object[Request $request]
     * @param  branch_id
     */
	public function update(Request $request,$templateId)
    {    
		$this->request = $request;
		$processor = new TemplateProcessor();
		$templatePersistable = new TemplatePersistable();
		$templateModel = new TemplateModel();		
		$result = $templateModel->getData($templateId);
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(strcmp($result,$fileSizeArray['404'])==0)
		{
			return $result;
		}
		else
		{
			$templatePersistable = $processor->createPersistableChange($this->request,$templateId);
			//here two array and string is return at a time
			if(is_array($templatePersistable))
			{
				$templateService= new TemplateService();	
				$status = $templateService->update($templatePersistable);
				return $status;
			}
			else
			{
				return $templatePersistable;
			}
		}
		
	}
}

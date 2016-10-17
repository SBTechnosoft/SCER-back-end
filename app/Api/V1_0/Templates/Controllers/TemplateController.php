<?php
namespace ERP\Api\V1_0\Templates\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Templates\Services\TemplateService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Templates\Processors\TemplateProcessor;
use ERP\Core\Templates\Persistables\TemplatePersistable;
use ERP\Core\Support\Service\ContainerInterface;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class TemplateController extends BaseController implements ContainerInterface
{
	/**
     * @var templateService
     * @var Processor
     * @var request
     * @var templatePersistable
     */
	private $templateService;
	private $Processor;
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
     * Update the specified resource in storage.
     * @param  Request object[Request $request]
     * @param  branch_id
     */
	public function update(Request $request,$templateId)
    {    
		$this->request = $request;
		$Processor = new TemplateProcessor();
		$templatePersistable = new TemplatePersistable();		
		$templatePersistable = $Processor->createPersistableChange($this->request,$templateId);
		//here two array and string is return at a time
		if($templatePersistable=="204: No Content Found For Update")
		{
			return $templatePersistable;
		}
		else if(is_array($templatePersistable))
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

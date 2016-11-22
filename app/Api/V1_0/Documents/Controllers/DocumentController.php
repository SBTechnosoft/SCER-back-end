<?php
namespace ERP\Api\V1_0\Documents\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Api\V1_0\Documents\Processors\DocumentProcessor;
use ERP\Core\Documents\Persistables\DocumentPersistable;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Core\Support\Service\ContainerInterface;
use ERP\Core\Documents\Services\DocumentService;
// use ERP\Exceptions\ExceptionMessage;
// use ERP\Model\Branches\BranchModel;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class DocumentController extends BaseController implements ContainerInterface
{
	/**
     * @var branchService
     * @var processor
     * @var request
     * @var branchPersistable
     */
	// private $branchService;
	// private $processor;
	// private $request;
	// private $branchPersistable;	
	
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
	 * set data for insert the specified resource 
	 * @param  Request object[Request $request]
	 * method calls the processor for creating persistable object & setting the data
	*/
    public function insertUpdate(Request $request,$documentPath)
    {
		// check the requested Http method
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		// insert
		if($requestMethod == 'POST')
		{
			$documentProcessor = new DocumentProcessor();
			$documentPersistable = new DocumentPersistable();	
			$documentService= new DocumentService();			
			$documentPersistable = $documentProcessor->createPersistable($request,$documentPath);
			
			if(is_array($documentPersistable))
			{
				$status = $documentService->insert($documentPersistable);
				return $status;
			}
			else
			{
				return $documentPersistable;
			}
		}
	}
}

<?php
namespace ERP\Api\V1_0\ProductCategories\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\ProductCategories\Services\ProductCategoryService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\ProductCategories\Processors\ProductCategoryProcessor;
use ERP\Core\ProductCategories\Persistables\ProductCategoryPersistable;
use ERP\Core\Support\Service\ContainerInterface;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductCategoryController extends BaseController implements ContainerInterface
{
	/**
     * @var ProductCategoryService
     * @var Processor
     * @var name
     * @var request
     * @var ProductCategoryPersistable
     */
	private $productCategoryService;
	private $processor;
	private $productCategoryName;
	private $request;
	private $productCategoryPersistable;	
	
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
			$Processor = new ProductCategoryProcessor();
			$productCategoryPersistable = new ProductCategoryPersistable();		
			$productCategoryService= new ProductCategoryService();			
			$productCategoryPersistable = $Processor->createPersistable($this->request);
			if($productCategoryPersistable[0][0]=='[')
			{
				return $productCategoryPersistable;
			}
			else
			{
				$status = $productCategoryService->insert($productCategoryPersistable);
				return $status;
			}
		}
		else
		{
			return $status;
		}
	}
	
	/**
     * get the specified resource.
     * @param  int  $companyId
     */
    public function getData($productCategoryId=null)
    {
		if($productCategoryId==null)
		{			
			$productCategoryService= new ProductCategoryService();
			$status = $productCategoryService->getAllProductCatData();
			return $status;
		}
		else
		{	
			$productCategoryService= new ProductCategoryService();
			$status = $productCategoryService->getProductCatData($productCategoryId);
			return $status;
		}        
    }
	
    /**
     * Update the specified resource in storage.
     * @param  Request object[Request $request]
     */
	public function update(Request $request,$productCategoryId)
    {    
		$this->request = $request;
		$Processor = new ProductCategoryProcessor();
		$productCategoryPersistable = new ProductCategoryPersistable();		
		$productCategoryService= new ProductCategoryService();			
		$productCategoryPersistable = $Processor->createPersistableChange($this->request,$productCategoryId);
		if($productCategoryPersistable=="204: No Content Found For Update")
		{
			return $productCategoryPersistable;
		}
		else if(is_array($productCategoryPersistable))
		{
			$status = $productCategoryService->update($productCategoryPersistable);
			return $status;
		}
		else
		{
			return $productCategoryPersistable;
		}
	}
	
    /**
     * Remove the specified resource from storage.
     * @param  Request object[Request $request]     
     */
    public function Destroy(Request $request,$productCategoryId)
    {
        $this->request = $request;
		$Processor = new ProductCategoryProcessor();
		$productCategoryPersistable = new ProductCategoryPersistable();		
		$productCategoryService= new ProductCategoryService();			
		$productCategoryPersistable = $Processor->createPersistableChange($this->request,$productCategoryId);
		$status = $productCategoryService->delete($productCategoryPersistable);
		return $status;
    }
}

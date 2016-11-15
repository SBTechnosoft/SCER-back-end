<?php
namespace ERP\Api\V1_0\Products\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Products\Services\ProductService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Products\Processors\ProductProcessor;
use ERP\Core\Products\Persistables\ProductPersistable;
use ERP\Core\Support\Service\ContainerInterface;
use ERP\Exceptions\ExceptionMessage;
use ERP\Model\Products\ProductModel;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductController extends BaseController implements ContainerInterface
{
	/**
     * @var productService
     * @var processor
     * @var name
     * @var request
     * @var productPersistable
     */
	private $productService;
	private $processor;
	private $request;
	private $productPersistable;	
	
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
			$processor = new ProductProcessor();
			$productPersistable = new ProductPersistable();		
			$productService= new ProductService();			
			$productPersistable = $processor->createPersistable($this->request);
			if($productPersistable[0][0]=='[')
			{
				return $productPersistable;
			}
			else
			{
				$status = $productService->insert($productPersistable);
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
     * @param  int  $productId
     */
    public function getData($productId=null)
    {
		if($productId==null)
		{	
			$productService= new ProductService();
			$status = $productService->getAllProductData();
			return $status;
		}
		else
		{	
			$productService= new ProductService();
			$status = $productService->getProductData($productId);
			return $status;
		}        
    }
	
	/**
     * get the specified resource.
     * @param $productId and $branchId
     */
    public function getAllProductData($companyId=null,$branchId=null)
    {
		if($branchId=="null" && $companyId=="null")
		{	
			$productService= new ProductService();
			$status = $productService->getAllProductData();
			return $status;
		}
		else if($branchId=="null" || $companyId=="null")
		{
			if($branchId=="null")	
			{
				$productService= new ProductService();
				$status = $productService->getCBProductData($branchId,$companyId);
				return $status;
			}
			else
			{
				$productService= new ProductService();
				$status = $productService->getCBProductData($branchId,$companyId);
				return $status;
			}
		}
		else
		{	
			$productService= new ProductService();
			$status = $productService->getCBProductData($branchId,$companyId);
			return $status;
		}        
    }
	
	/**
     * Update the specified resource in storage.
     * @param  Request object[Request $request]
     */
	public function update(Request $request,$productId)
    {    
		$this->request = $request;
		$processor = new ProductProcessor();
		$productPersistable = new ProductPersistable();		
		$productService= new ProductService();			
		$productModel = new ProductModel();
		$result = $productModel->getData($productId);
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(strcmp($result,$fileSizeArray['404'])==0)
		{
			return $result;
		}
		else
		{
			$productPersistable = $processor->createPersistableChange($this->request,$productId);
			if(is_array($productPersistable))
			{
				$status = $productService->update($productPersistable);
				return $status;
			}
			else
			{
				return $productPersistable;
			}
		}
	}
	
    /**
     * Remove the specified resource from storage.
     * @param  Request object[Request $request]     
     */
    public function Destroy(Request $request,$productId)
    {
        $this->request = $request;
		$processor = new ProductProcessor();
		$productPersistable = new ProductPersistable();		
		$productService= new ProductService();		
		$productModel = new ProductModel();
		$result = $productModel->getData($productId);
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(strcmp($result,$fileSizeArray['404'])==0)
		{
			return $result;
		}
		else
		{		
			$productPersistable = $processor->createPersistableChange($this->request,$productId);
			$productService->create($productPersistable);
			$status = $productService->delete($productPersistable);
			return $status;
		}
    }
}

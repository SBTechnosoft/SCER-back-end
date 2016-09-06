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
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductController extends BaseController implements ContainerInterface
{
	/**
     * @var productService
     * @var Processor
     * @var name
     * @var request
     * @var productPersistable
     */
	private $productService;
	private $Processor;
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
		$Processor = new ProductProcessor();
		$productPersistable = new ProductPersistable();		
		$productService= new ProductService();			
		$productPersistable = $Processor->createPersistable($this->request);
		$productService->create($productPersistable);
		$status = $productService->insert($productPersistable);
		return $status;
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
		$Processor = new ProductProcessor();
		$productPersistable = new ProductPersistable();		
		$productService= new ProductService();			
		$productPersistable = $Processor->createPersistableChange($this->request,$productId);
		$productService->create($productPersistable);
		$status = $productService->update($productPersistable);
		return $status;
    }
	
    /**
     * Remove the specified resource from storage.
     * @param  Request object[Request $request]     
     */
    public function Destroy(Request $request,$productId)
    {
        $this->request = $request;
		$Processor = new ProductProcessor();
		$productPersistable = new ProductPersistable();		
		$productService= new ProductService();			
		$productPersistable = $Processor->createPersistableChange($this->request,$productId);
		$productService->create($productPersistable);
		$status = $productService->delete($productPersistable);
		return $status;
    }
}

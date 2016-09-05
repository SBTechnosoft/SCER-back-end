<?php
namespace ERP\Api\V1_0\ProductGroups\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\ProductGroups\Services\ProductGroupService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\ProductGroups\Processors\ProductGroupProcessor;
use ERP\Core\ProductGroups\Persistables\ProductGroupPersistable;
use ERP\Core\Support\Service\ContainerInterface;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductGroupController extends BaseController implements ContainerInterface
{
	/**
     * @var productGroupService
     * @var processor
     * @var productGroupName
     * @var request
     * @var productGroupPersistable
     */
	private $productGroupService;
	private $processor;
	private $name;
	private $request;
	private $productGroupPersistable;	
	
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
		$Processor = new productGroupProcessor();
		$productGroupPersistable = new ProductGroupPersistable();		
		$productGroupService= new ProductGroupService();			
		$productGroupPersistable = $Processor->createPersistable($this->request);
		$productGroupService->create($productGroupPersistable);
		$status = $productGroupService->insert($productGroupPersistable);
		return $status;
	}
	
	/**
     * get the specified resource.
     * @param  int  $companyId
     */
    public function getData($productGroupId=null)
    {
		if($productGroupId==null)
		{			
			$productGroupService= new productGroupService();
			$status = $productGroupService->getAllproductGrpData();
			return $status;
		}
		else
		{	
			$productGroupService= new ProductGroupService();
			$status = $productGroupService->getproductGrpData($productGroupId);
			return $status;
		}        
    }
	
    /**
     * Update the specified resource in storage.
     * @param  Request object[Request $request]
     */
	public function update(Request $request,$productGroupId)
    {    
		$this->request = $request;
		$Processor = new ProductGroupProcessor();
		$productGroupPersistable = new ProductGroupPersistable();		
		$productGroupService= new ProductGroupService();			
		$productGroupPersistable = $Processor->createPersistableChange($this->request,$productGroupId);
		$productGroupService->create($productGroupPersistable);
		$status = $productGroupService->update($productGroupPersistable);
		return $status;
    }
	
    /**
     * Remove the specified resource from storage.
     * @param  Request object[Request $request]     
     */
    public function Destroy(Request $request,$productGroupId)
    {
        $this->request = $request;
		$Processor = new ProductGroupProcessor();
		$productGroupPersistable = new ProductGroupPersistable();		
		$productGroupService= new ProductGroupService();			
		$productGroupPersistable = $Processor->createPersistableChange($this->request,$productGroupId);
		$productGroupService->create($productGroupPersistable);
		$status = $productGroupService->delete($productGroupPersistable);
		return $status;
    }
}

<?php
namespace ERP\Api\V1_0\Products\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Products\Persistables\ProductPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductProcessor extends BaseProcessor
{
	/**
     * @var productPersistable
	 * @var request
     */
	private $productPersistable;
	private $request;    
	
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return product Persistable object
     */	
    public function createPersistable(Request $request)
	{	
		$this->request = $request;	
		// check the requested Http method
		$requestMethod = $_SERVER['REQUEST_METHOD'];
	
		// insert
		if($requestMethod == 'POST')
		{
			$productName = $request->input('product_name'); 
			$measurementUnit = $request->input('measurement_unit'); 
			$isDisplay = $request->input('is_display'); 			
			$companyId = $request->input('company_id'); 			
			$productCatId = $request->input('product_cat_id'); 			
			$productGrpId = $request->input('product_group_id'); 			
			$branchId = $request->input('branch_id'); 			
			
			$productPersistable = new productPersistable();		
			$productPersistable->setName($productName);		 
			$productPersistable->setIsDisplay($isDisplay);		 
			$productPersistable->setCompanyId($companyId);		 
			$productPersistable->setMeasureUnit($measurementUnit);		 
			$productPersistable->setId($productCatId);		 
			$productPersistable->setProductGrpId($productGrpId);		 
			$productPersistable->setBranchId($branchId);		 
			
			return $productPersistable;	
		}		
		else{
			
		}	
    }
	public function createPersistableChange(Request $request,$productId)
	{
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		
		// update
		if($requestMethod == 'POST')
		{
			$productName = $request->input('product_name'); 
			$isDisplay = $request->input('is_display'); 			
			$companyId = $request->input('company_id');
			$productCatId = $request->input('product_cat_id'); 			
			$productGrpId = $request->input('product_group_id'); 			
			$branchId = $request->input('branch_id'); 	
			$measurementUnit = $request->input('measurement_unit'); 
			
			$productPersistable = new productPersistable();		
			$productPersistable->setName($productName);		 
			$productPersistable->setIsDisplay($isDisplay);		 
			$productPersistable->setCompanyId($companyId);
			$productPersistable->setMeasureUnit($measurementUnit);
			$productPersistable->setproductId($productId);
			$productPersistable->setId($productCatId);
			$productPersistable->setProductGrpId($productGrpId);
			$productPersistable->setBranchId($branchId);
			return $productPersistable;
		}
		//delete
		else if($requestMethod == 'DELETE')
		{
			$productPersistable = new productPersistable();		
			$productPersistable->setproductId($productId);			
			return $productPersistable;
		}
	}	
}
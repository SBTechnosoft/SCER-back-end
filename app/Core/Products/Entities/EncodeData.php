<?php
namespace ERP\Core\Products\Entities;

use ERP\Core\Products\Entities\Product;
use ERP\Core\ProductCategories\Services\ProductCategoryService;
use ERP\Core\Entities\ProductGroupDetail;
use ERP\Core\Entities\CompanyDetail;
use ERP\Core\Entities\BranchDetail;
use Carbon;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeData extends ProductCategoryService 
{
	
    public function getEncodedData($status)
	{
		$decodedJson = json_decode($status,true);
			
		$createdAt = $decodedJson[0]['created_at'];
		$updatedAt= $decodedJson[0]['updated_at'];
		$productId= $decodedJson[0]['product_id'];
		$productName= $decodedJson[0]['product_name'];
		$measurementUnit= $decodedJson[0]['measurement_unit'];
		$isDisplay= $decodedJson[0]['is_display'];
		$productCatId= $decodedJson[0]['product_cat_id'];
		$productGrpId= $decodedJson[0]['product_group_id'];
		$companyId= $decodedJson[0]['company_id'];
		$branchId= $decodedJson[0]['branch_id'];
		
		//get the product_cat_details from database
		$encodeProductCatDataClass = new EncodeData();
		$productCatStatus = $encodeProductCatDataClass->getProductCatData($productCatId);
		
		$productCatDecodedJson = json_decode($productCatStatus,true);
		$pCatId= $productCatDecodedJson['product_cat_id'];
		$pCatName= $productCatDecodedJson['product_cat_name'];
		$pCatDesc= $productCatDecodedJson['product_cat_desc'];
		$pCatIsDisplay= $productCatDecodedJson['is_display'];
		$pParentCatId= $productCatDecodedJson['product_parent_cat_id'];
		$pCatCreatedAt= $productCatDecodedJson['created_at'];
		$pCatUpdatedAt= $productCatDecodedJson['updated_at'];
		
		//get the product group detail from database
		$productGroupDetail  = new ProductGroupDetail();
		$getProductGrpDetails = $productGroupDetail->getProductGrpDetails($productGrpId);
		
		//get the company detail from database
		$companyDetail  = new CompanyDetail();
		$getCompanyDetails = $companyDetail->getCompanyDetails($companyId);
		
		//get the branch detail from database
		$branchDetail  = new BranchDetail();
		$getBranchDetails = $branchDetail->getBranchDetails($branchId);
		
		//date format conversion['created_at','updated_at'] product
		$product = new Product();
		$convertedCreatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt)->format('d-m-Y');
		$product->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $product->getCreated_at();
			
		$convertedUpdatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt)->format('d-m-Y');
		$product->setUpdated_at($convertedUpdatedDate);
		$getUpdatedDate = $product->getUpdated_at();
		
		//set all data into json array
		$data = array();
		$data['product_id'] = $productId;
		$data['product_name'] = $productName;
		$data['measurement_unit'] = $measurementUnit;
		$data['is_display'] = $isDisplay;
		$data['created_at'] = $getCreatedDate;
		$data['updated_at'] = $getUpdatedDate;	
		
		$data['pCat_name'] = $pCatName;	
		$data['pCat_id'] = $pCatId;	
		$data['pCat_desc'] = $pCatDesc;	
		$data['pCat_is_display'] = $pCatIsDisplay;	
		$data['pCat_parent_id'] = $pParentCatId;	
		$data['pCat_created_at'] = $pCatCreatedAt;	
		$data['pCat_updated_at'] = $pCatUpdatedAt;	
		
		$data['pGrp_name'] = $getProductGrpDetails['product_group_name'];	
		$data['pGrp_id'] = $getProductGrpDetails['product_group_id'];	
		$data['pGrp_desc'] = $getProductGrpDetails['product_group_desc'];	
		$data['pGrp_parent_id'] = $getProductGrpDetails['product_group_parent_id'];	
		$data['pGrp_is_display'] = $getProductGrpDetails['is_display'];	
		$data['pGrp_created_at'] = $getProductGrpDetails['created_at'];	
		$data['pGrp_updated_at'] = $getProductGrpDetails['updated_at'];	
		
		$data['company_id'] = $getCompanyDetails['company_id'];	
		$data['company_name'] = $getCompanyDetails['company_name'];	
		$data['company_display_name'] = $getCompanyDetails['company_display_name'];	
		$data['companyAddress1'] = $getCompanyDetails['address1'];	
		$data['companyAddress2'] = $getCompanyDetails['address2'];	
		$data['companyPincode'] = $getCompanyDetails['pincode'];	
		$data['pan'] = $getCompanyDetails['pan'];	
		$data['tin'] = $getCompanyDetails['tin'];	
		$data['vat_no'] = $getCompanyDetails['vat_no'];	
		$data['service_tax_no'] = $getCompanyDetails['service_tax_no'];	
		$data['basic_currency_symbol'] = $getCompanyDetails['basic_currency_symbol'];	
		$data['formal_name'] = $getCompanyDetails['formal_name'];	
		$data['no_of_decimal_points'] = $getCompanyDetails['no_of_decimal_points'];	
		$data['currency_symbol'] = $getCompanyDetails['currency_symbol'];	
		$data['document_name'] = $getCompanyDetails['document_name'];	
		$data['document_url'] = $getCompanyDetails['document_url'];	
		$data['document_size'] = $getCompanyDetails['document_size'];	
		$data['document_format'] = $getCompanyDetails['document_format'];	
		$data['companyIs_display'] = $getCompanyDetails['is_display'];	
		$data['companyIs_default'] = $getCompanyDetails['is_default'];	
		$data['companyCreated_at'] = $getCompanyDetails['created_at'];	
		$data['companyUpdated_at'] = $getCompanyDetails['updated_at'];	
		$data['companyState_abb'] = $getCompanyDetails['state_abb'];	
		$data['companyCity_id'] = $getCompanyDetails['city_id'];	
		$data['companyState_name'] = $getCompanyDetails['state_name'];	
		$data['companyCity_name'] = $getCompanyDetails['city_name'];	
		
		$data['branch_id'] = $getBranchDetails['branch_id'];	
		$data['branch_name'] = $getBranchDetails['branch_name'];	
		$data['branchAddress1'] = $getBranchDetails['address1'];	
		$data['branchAddress2'] = $getBranchDetails['address2'];	
		$data['branchPincode'] = $getBranchDetails['pincode'];	
		$data['branchIs_display'] = $getBranchDetails['is_display'];	
		$data['branchIs_default'] = $getBranchDetails['is_default'];	
		$data['branchCreated_at'] = $getBranchDetails['created_at'];	
		$data['branchUpdated_at'] = $getBranchDetails['updated_at'];	
		$data['branchState_abb'] = $getBranchDetails['state_abb'];	
		$data['branchCity_id'] = $getBranchDetails['city_id'];	
		$data['branchCompany_id'] = $getBranchDetails['company_id'];	
		$data['branchCity_name'] = $getBranchDetails['city_name'];	
		$data['branchCompany_name'] = $getBranchDetails['company_name'];	
		$data['branchState_name'] = $getBranchDetails['state_name'];	
			
		$encodeData = json_encode($data);
		return $encodeData;
	}
}
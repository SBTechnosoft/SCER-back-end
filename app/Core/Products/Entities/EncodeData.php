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
		$data['product_category_id'] = $pCatId;	
		$data['product_group_id'] = $getProductGrpDetails['product_group_id'];
		$data['company_id'] = $getCompanyDetails['company_id'];	
		$data['branch_id'] = $getBranchDetails['branch_id'];
		
		$data['product_category_id'] = array(
			'product_category_name' => $pCatName,	
			'product_category_id' => $pCatId,
			'product_category_desc' => $pCatDesc,	
			'is_display' => $pCatIsDisplay,
			'product_parent_category_id' => $pParentCatId,	
			'created_at' => $pCatCreatedAt,	
			'updated_at' => $pCatUpdatedAt,	
		);
		$data['product_group_id' = array(
			'product_group_name' => $getProductGrpDetails['product_group_name'],	
			'product_group_id' => $getProductGrpDetails['product_group_id'],	
			'product_group_desc' => $getProductGrpDetails['product_group_desc'],	
			'product_parent_group_id' => $getProductGrpDetails['product_group_parent_id'],
			'is_display' => $getProductGrpDetails['is_display'],	
			'created_at' => $getProductGrpDetails['created_at'],	
			'updated_at' => $getProductGrpDetails['updated_at']	
		);	
		$data['company_id'] = array(
			'company_id' => $getCompanyDetails['company_id'],	
			'company_name' => $getCompanyDetails['company_name'],
			'company_display_name' => $getCompanyDetails['company_display_name'],
			'companyAddress1' => $getCompanyDetails['address1'],	
			'companyAddress2' => $getCompanyDetails['address2'],	
			'companyPincode' => $getCompanyDetails['pincode'],
			'pan' => $getCompanyDetails['pan'],	
			'tin' => $getCompanyDetails['tin'],	
			'vat_no' => $getCompanyDetails['vat_no'],	
			'service_tax_no' => $getCompanyDetails['service_tax_no'],	
			'basic_currency_symbol' => $getCompanyDetails['basic_currency_symbol'],
			'formal_name' => $getCompanyDetails['formal_name'],	
			'no_of_decimal_points' => $getCompanyDetails['no_of_decimal_points'],
			'currency_symbol' => $getCompanyDetails['currency_symbol'],	
			'document_name' => $getCompanyDetails['document_name'],	
			'document_url' => $getCompanyDetails['document_url'],	
			'document_size' => $getCompanyDetails['document_size'],	
			'document_format' => $getCompanyDetails['document_format'],	
			'companyIs_display' => $getCompanyDetails['is_display'],	
			'companyIs_default' => $getCompanyDetails['is_default'],	
			'companyCreated_at' => $getCompanyDetails['created_at'],	
			'companyUpdated_at' => $getCompanyDetails['updated_at'],
			'companyState_abb' => $getCompanyDetails['state_abb'],	
			'companyCity_id' => $getCompanyDetails['city_id'],
			'companyState_name' => $getCompanyDetails['state_name'],	
			'companyCity_name' => $getCompanyDetails['city_name']	
		);
		$data['branch_id'] = array(
			'branch_id' => $getBranchDetails['branch_id'],
			'branch_name' => $getBranchDetails['branch_name'],	
			'address1' => $getBranchDetails['address1'],	
			'address2' => $getBranchDetails['address2'],	
			'pincode' => $getBranchDetails['pincode'],	
			's_display' => $getBranchDetails['is_display'],	
			'is_default' => $getBranchDetails['is_default'],	
			'created_at' => $getBranchDetails['created_at'],	
			'updated_at' => $getBranchDetails['updated_at'],	
			'state_abb' => $getBranchDetails['state_abb'],	
			'city_id' => $getBranchDetails['city_id'],	
			'company_id' => $getBranchDetails['company_id'],	
			'city_name' => $getBranchDetails['city_name'],
			'company_name' => $getBranchDetails['company_name'],	
			'state_name' => $getBranchDetails['state_name']
		);	
		$encodeData = json_encode($data);
		return $encodeData;
	}
}
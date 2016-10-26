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
class EncodeAllData extends ProductCategoryService
{
	public function getEncodedAllData($status)
	{
		$convertedCreatedDate =  array();
		$convertedUpdatedDate =  array();
		$encodeAllData =  array();
			
		$decodedJson = json_decode($status,true);
		$product = new Product();
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$createdAt[$decodedData] = $decodedJson[$decodedData]['created_at'];
			$updatedAt[$decodedData] = $decodedJson[$decodedData]['updated_at'];
			$productId[$decodedData] = $decodedJson[$decodedData]['product_id'];
			$productName[$decodedData] = $decodedJson[$decodedData]['product_name'];
			$measurementUnit[$decodedData] = $decodedJson[$decodedData]['measurement_unit'];
			$isDisplay[$decodedData] = $decodedJson[$decodedData]['is_display'];
			$productCatId[$decodedData] = $decodedJson[$decodedData]['product_cat_id'];
			$productGrpId[$decodedData] = $decodedJson[$decodedData]['product_group_id'];
			$companyId[$decodedData] = $decodedJson[$decodedData]['company_id'];
			$branchId[$decodedData] = $decodedJson[$decodedData]['branch_id'];
			
			//get the categoryData from database
			$encodeDataClass = new EncodeAllData();
			$productStatus[$decodedData] = $encodeDataClass->getProductCatData($productId[$decodedData]);
			$productDecodedJson[$decodedData] = json_decode($productStatus[$decodedData],true);
			$productCatId[$decodedData]= $productDecodedJson[$decodedData]['product_cat_id'];
			$productCatName[$decodedData]= $productDecodedJson[$decodedData]['product_cat_name'];
			$productCatDesc[$decodedData]= $productDecodedJson[$decodedData]['product_cat_desc'];
			$productParentCatId[$decodedData]= $productDecodedJson[$decodedData]['product_parent_cat_id'];
			$productCatIsDisplay[$decodedData]= $productDecodedJson[$decodedData]['is_display'];
			$pCatCreatedAt[$decodedData]= $productDecodedJson[$decodedData]['created_at'];
			$pCatUpdatedAt[$decodedData]= $productDecodedJson[$decodedData]['updated_at'];
			
			//product group details from database
			$productGroupDetail = new ProductGroupDetail();
			$getProductGrpDetails[$decodedData] = $productGroupDetail->getProductGrpDetails($productGrpId[$decodedData]);
			
			//get the company detail from database
			$companyDetail  = new CompanyDetail();
			$getCompanyDetails[$decodedData] = $companyDetail->getCompanyDetails($companyId[$decodedData]);
			
			//get the branch detail from database
			$branchDetail  = new BranchDetail();
			$getBranchDetails[$decodedData] = $branchDetail->getBranchDetails($branchId[$decodedData]);
			
			//product date convertion
			$convertedCreatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$createdAt[$decodedData])->format('d-m-Y');
			$convertedUpdatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$updatedAt[$decodedData])->format('d-m-Y');
			
		}
		$product->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $product->getCreated_at();
			
		$product->setUpdated_at($convertedUpdatedDate);
		$getUpdatedDate = $product->getUpdated_at();
		$data = array();
		
		for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
		{
			$data[$jsonData]= array(
				'product_id'=>$productId[$jsonData],
				'product_name' => $productName[$jsonData],
				'is_display' => $isDisplay[$jsonData],
				'measurement_unit' => $measurementUnit[$jsonData],
				'created_at' => $getCreatedDate[$jsonData],
				'updated_at' => $getUpdatedDate[$jsonData],
				'product_category_id' => $productCatId[$jsonData],
				'product_group_id' => $getProductGrpDetails[$jsonData]['product_group_id'],	
				'company_id' => $getCompanyDetails[$jsonData]['company_id'],
				
				'product_category_id' => array(
					'product_category_id' => $productCatId[$jsonData],
					'product_category_name' => $productCatName[$jsonData],
					'product_category_desc' => $productCatDesc[$jsonData],
					'product_parent_category_id' => $productParentCatId[$jsonData],
					'created_at' => $pCatCreatedAt[$jsonData],
					'updated_at' => $pCatUpdatedAt[$jsonData]
				),
				
				'product_group_id' => array(
					'product_group_name' => $getProductGrpDetails[$jsonData]['product_group_name'],	
					'product_group_id' => $getProductGrpDetails[$jsonData]['product_group_id'],	
					'product_group_desc' => $getProductGrpDetails[$jsonData]['product_group_desc'],	
					'product_parent_group_id' => $getProductGrpDetails[$jsonData]['product_group_parent_id'],	
					'is_display' => $getProductGrpDetails[$jsonData]['is_display'],	
					'created_at' => $getProductGrpDetails[$jsonData]['created_at'],	
					'updated_at' => $getProductGrpDetails[$jsonData]['updated_at']
				),
				
				'company_id' => array(
					'company_id' => $getCompanyDetails[$jsonData]['company_id'],	
					'company_name' => $getCompanyDetails[$jsonData]['company_name'],	
					'company_display_name' => $getCompanyDetails[$jsonData]['company_display_name'],	
					'address1' => $getCompanyDetails[$jsonData]['address1'],	
					'address2'=> $getCompanyDetails[$jsonData]['address2'],	
					'pincode' => $getCompanyDetails[$jsonData]['pincode'],	
					'pan' => $getCompanyDetails[$jsonData]['pan'],	
					'tin'=> $getCompanyDetails[$jsonData]['tin'],	
					'vat_no' => $getCompanyDetails[$jsonData]['vat_no'],	
					'service_tax_no' => $getCompanyDetails[$jsonData]['service_tax_no'],	
					'basic_currency_symbol' => $getCompanyDetails[$jsonData]['basic_currency_symbol'],	
					'formal_name' => $getCompanyDetails[$jsonData]['formal_name'],	
					'no_of_decimal_points' => $getCompanyDetails[$jsonData]['no_of_decimal_points'],	
					'currency_symbol' => $getCompanyDetails[$jsonData]['currency_symbol'],	
					'document_name' => $getCompanyDetails[$jsonData]['document_name'],	
					'document_url' => $getCompanyDetails[$jsonData]['document_url'],	
					'document_size' =>$getCompanyDetails[$jsonData]['document_size'],	
					'document_format' => $getCompanyDetails[$jsonData]['document_format'],	
					'is_display' => $getCompanyDetails[$jsonData]['is_display'],	
					'is_default' => $getCompanyDetails[$jsonData]['is_default'],	
					'created_at' => $getCompanyDetails[$jsonData]['created_at'],	
					'updated_at' => $getCompanyDetails[$jsonData]['updated_at'],	
					'state_abb' => $getCompanyDetails[$jsonData]['state_abb'],	
					'city_id' => $getCompanyDetails[$jsonData]['city_id'],	
					'state_name' => $getCompanyDetails[$jsonData]['state_name'],	
					'city_name' => $getCompanyDetails[$jsonData]['city_name']	
				),
				
				'branch_id' => array(
					'branch_id' => $getBranchDetails[$jsonData]['branch_id'],	
					'branch_name'=> $getBranchDetails[$jsonData]['branch_name'],	
					'address1' => $getBranchDetails[$jsonData]['address1'],	
					'address2' => $getBranchDetails[$jsonData]['address2'],	
					'pincode' => $getBranchDetails[$jsonData]['pincode'],	
					'is_display' => $getBranchDetails[$jsonData]['is_display'],	
					'is_default' => $getBranchDetails[$jsonData]['is_default'],	
					'created_at' => $getBranchDetails[$jsonData]['created_at'],	
					'updated_at' => $getBranchDetails[$jsonData]['updated_at'],	
					'state_abb' => $getBranchDetails[$jsonData]['state_abb'],	
					'city_id' => $getBranchDetails[$jsonData]['city_id'],	
					'company_id' => $getBranchDetails[$jsonData]['company_id'],	
					'city_name' => $getBranchDetails[$jsonData]['city_name'],	
					'company_name' => $getBranchDetails[$jsonData]['company_name'],	
					'state_name' => $getBranchDetails[$jsonData]['state_name']
				)
			);
		}
		return json_encode($data);
	}
}
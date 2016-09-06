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
				
				'pCatId' => $productCatId[$jsonData],
				'pCatName' => $productCatName[$jsonData],
				'pCatDesc' => $productCatDesc[$jsonData],
				'pParentCatId' => $productParentCatId[$jsonData],
				'pCat_created_at' => $pCatCreatedAt[$jsonData],
				'pCat_updated_at' => $pCatUpdatedAt[$jsonData],
				
				'pGrp_name' => $getProductGrpDetails[$jsonData]['product_group_name'],	
				'pGrp_id' => $getProductGrpDetails[$jsonData]['product_group_id'],	
				'pGrp_desc' => $getProductGrpDetails[$jsonData]['product_group_desc'],	
				'pGrp_parent_id' => $getProductGrpDetails[$jsonData]['product_group_parent_id'],	
				'pGrp_is_display' => $getProductGrpDetails[$jsonData]['is_display'],	
				'pGrp_created_at' => $getProductGrpDetails[$jsonData]['created_at'],	
				'pGrp_updated_at' => $getProductGrpDetails[$jsonData]['updated_at'],	
				
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
				'cIs_display' => $getCompanyDetails[$jsonData]['is_display'],	
				'cIs_default' => $getCompanyDetails[$jsonData]['is_default'],	
				'cCreated_at' => $getCompanyDetails[$jsonData]['created_at'],	
				'cUpdated_at' => $getCompanyDetails[$jsonData]['updated_at'],	
				'cState_abb' => $getCompanyDetails[$jsonData]['state_abb'],	
				'cCity_id' => $getCompanyDetails[$jsonData]['city_id'],	
				'cState_name' => $getCompanyDetails[$jsonData]['state_name'],	
				'cCity_name' => $getCompanyDetails[$jsonData]['city_name'],	
				
				'branch_id' => $getBranchDetails[$jsonData]['branch_id'],	
				'branch_name'=> $getBranchDetails[$jsonData]['branch_name'],	
				'address1' => $getBranchDetails[$jsonData]['address1'],	
				'address2' => $getBranchDetails[$jsonData]['address2'],	
				'pincode' => $getBranchDetails[$jsonData]['pincode'],	
				'bIs_display' => $getBranchDetails[$jsonData]['is_display'],	
				'bIs_default' => $getBranchDetails[$jsonData]['is_default'],	
				'bCreated_at' => $getBranchDetails[$jsonData]['created_at'],	
				'bUpdated_at' => $getBranchDetails[$jsonData]['updated_at'],	
				'bState_abb' => $getBranchDetails[$jsonData]['state_abb'],	
				'bCity_id' => $getBranchDetails[$jsonData]['city_id'],	
				'bCompany_id' => $getBranchDetails[$jsonData]['company_id'],	
				'bCity_name' => $getBranchDetails[$jsonData]['city_name'],	
				'bCompany_name' => $getBranchDetails[$jsonData]['company_name'],	
				'bState_name' => $getBranchDetails[$jsonData]['state_name']	
				
			);
		}
		return json_encode($data);
	}
	
}
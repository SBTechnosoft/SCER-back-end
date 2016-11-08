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
		$productCatId= $decodedJson[0]['product_category_id'];
		$productGrpId= $decodedJson[0]['product_group_id'];
		$companyId= $decodedJson[0]['company_id'];
		$branchId= $decodedJson[0]['branch_id'];
		
		//get the product_cat_details from database
		$encodeProductCatDataClass = new EncodeData();
		$productCatStatus = $encodeProductCatDataClass->getProductCatData($productCatId);
		
		$productCatDecodedJson = json_decode($productCatStatus,true);
		$pCatId= $productCatDecodedJson['productCategoryId'];
		$pCatName= $productCatDecodedJson['productCategoryName'];
		$pCatDesc= $productCatDecodedJson['productCategoryDescription'];
		$pCatIsDisplay= $productCatDecodedJson['isDisplay'];
		$pParentCatId= $productCatDecodedJson['productParentCategoryId'];
		$pCatCreatedAt= $productCatDecodedJson['createdAt'];
		$pCatUpdatedAt= $productCatDecodedJson['updatedAt'];
		
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
		$data['productId'] = $productId;
		$data['productName'] = $productName;
		$data['measurementUnit'] = $measurementUnit;
		$data['isDisplay'] = $isDisplay;
		$data['createdAt'] = $getCreatedDate;
		$data['updatedAt'] = $getUpdatedDate;	
		
		$data['productCategory'] = array(
			'productCategoryName' => $pCatName,	
			'productCategoryId' => $pCatId,
			'productCategoryDescription' => $pCatDesc,	
			'isDisplay' => $pCatIsDisplay,
			'productParentCategoryId' => $pParentCatId,	
			'createdAt' => $pCatCreatedAt,	
			'updatedAt' => $pCatUpdatedAt
		);
		$data['productGroup'] = array(
			'productGroupName' => $getProductGrpDetails['productGroupName'],	
			'productGroupId' => $getProductGrpDetails['productGroupId'],	
			'productGroupDescription' => $getProductGrpDetails['productGroupDescription'],	
			'productParentGroupId' => $getProductGrpDetails['productGroupParentId'],
			'isDisplay' => $getProductGrpDetails['isDisplay'],	
			'createdAt' => $getProductGrpDetails['createdAt'],	
			'updatedAt' => $getProductGrpDetails['updatedAt']	
		);	
		$data['company'] = array(
			'companyId' => $getCompanyDetails['companyId'],	
			'companyName' => $getCompanyDetails['companyName'],
			'companyDisplayName' => $getCompanyDetails['companyDisplayName'],
			'address1' => $getCompanyDetails['address1'],	
			'address2' => $getCompanyDetails['address2'],	
			'pincode' => $getCompanyDetails['pincode'],
			'pan' => $getCompanyDetails['pan'],	
			'tin' => $getCompanyDetails['tin'],	
			'vatNo' => $getCompanyDetails['vatNo'],	
			'serviceTaxNo' => $getCompanyDetails['serviceTaxNo'],	
			'basicCurrencySymbol' => $getCompanyDetails['basicCurrencySymbol'],
			'formalName' => $getCompanyDetails['formalName'],	
			'noOfDecimalPoints' => $getCompanyDetails['noOfDecimalPoints'],
			'currencySymbol' => $getCompanyDetails['currencySymbol'],	
			'documentName' => $getCompanyDetails['documentName'],	
			'documentUrl' => $getCompanyDetails['documentUrl'],	
			'documentSize' => $getCompanyDetails['documentSize'],	
			'documentFormat' => $getCompanyDetails['documentFormat'],	
			'isDisplay' => $getCompanyDetails['isDisplay'],	
			'isDefault' => $getCompanyDetails['isDefault'],	
			'createdAt' => $getCompanyDetails['createdAt'],	
			'updatedAt' => $getCompanyDetails['updatedAt'],
			'stateAbb' => $getCompanyDetails['state']['stateAbb'],	
			'cityId' => $getCompanyDetails['city']['cityId']
		);
		$data['branch'] = array(
			'branchId' => $getBranchDetails['branchId'],
			'branchName' => $getBranchDetails['branchName'],	
			'address1' => $getBranchDetails['address1'],	
			'address2' => $getBranchDetails['address2'],	
			'pincode' => $getBranchDetails['pincode'],	
			'isDisplay' => $getBranchDetails['isDisplay'],	
			'isDefault' => $getBranchDetails['isDefault'],	
			'createdAt' => $getBranchDetails['createdAt'],	
			'updatedAt' => $getBranchDetails['updatedAt'],	
			'stateAbb' => $getBranchDetails['state']['stateAbb'],	
			'cityId' => $getBranchDetails['city']['cityId'],	
			'companyId' => $getBranchDetails['company']['companyId']
		);	
		$encodeData = json_encode($data);
		return $encodeData;
	}
}
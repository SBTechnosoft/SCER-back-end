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
		$purchasePrice= $decodedJson[0]['purchase_price'];
		$wholesaleMargin= $decodedJson[0]['wholesale_margin'];
		$semiWholeSaleMargin= $decodedJson[0]['semi_wholesale_margin'];
		$vat= $decodedJson[0]['vat'];
		$mrp= $decodedJson[0]['mrp'];
		$color= $decodedJson[0]['color'];
		$size= $decodedJson[0]['size'];
		$margin= $decodedJson[0]['margin'];
		$productDescription= $decodedJson[0]['product_description'];
		$additionalTax= $decodedJson[0]['additional_tax'];
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
		
		//convert amount(round) into their company's selected decimal points
		$purchasePrice = round($purchasePrice,$getCompanyDetails['noOfDecimalPoints']);
		$wholesaleMargin = round($wholesaleMargin,$getCompanyDetails['noOfDecimalPoints']);
		$semiWholeSaleMargin = round($semiWholeSaleMargin,$getCompanyDetails['noOfDecimalPoints']);
		$vat= round($vat,$getCompanyDetails['noOfDecimalPoints']);
		$mrp= round($mrp,$getCompanyDetails['noOfDecimalPoints']);
		$margin= round($margin,$getCompanyDetails['noOfDecimalPoints']);
		$additionalTax = round($additionalTax,$getCompanyDetails['noOfDecimalPoints']);
		
		//date format conversion['created_at','updated_at'] product
		$product = new Product();
		$convertedCreatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt)->format('d-m-Y');
		$product->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $product->getCreated_at();
		
		if(strcmp($updatedAt,'0000-00-00 00:00:00')==0)
		{
			$getUpdatedDate = "00-00-0000";
		}
		else
		{
			$convertedUpdatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt)->format('d-m-Y');
			$product->setUpdated_at($convertedUpdatedDate);
			$getUpdatedDate = $product->getUpdated_at();
		}
		//set all data into json array
		$data = array();
		$data['productId'] = $productId;
		$data['productName'] = $productName;
		$data['measurementUnit'] = $measurementUnit;
		$data['isDisplay'] = $isDisplay;
		$data['purchasePrice'] = $purchasePrice;
		$data['wholesaleMargin'] = $wholesaleMargin;
		$data['semiWholesaleMargin'] = $semiWholeSaleMargin;
		$data['vat'] = $vat;
		$data['mrp'] = $mrp;
		$data['color'] = $color;
		$data['size'] = $size;
		$data['margin'] = $margin;
		$data['productDescription'] = $productDescription;
		$data['additionalTax'] = $additionalTax;
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
			'logo' => array(
				'documentName' => $getCompanyDetails['logo']['documentName'],	
				'documentUrl' => $getCompanyDetails['logo']['documentUrl'],	
				'documentSize' => $getCompanyDetails['logo']['documentSize'],	
				'documentFormat' => $getCompanyDetails['logo']['documentFormat']
			),
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
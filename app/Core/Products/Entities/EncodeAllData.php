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
			$purchasePrice[$decodedData] = $decodedJson[$decodedData]['purchase_price'];
			$wholesaleMargin[$decodedData] = $decodedJson[$decodedData]['wholesale_margin'];
			$semiWholesaleMargin[$decodedData] = $decodedJson[$decodedData]['semi_wholesale_margin'];
			$vat[$decodedData] = $decodedJson[$decodedData]['vat'];
			$margin[$decodedData] = $decodedJson[$decodedData]['margin'];
			$mrp[$decodedData] = $decodedJson[$decodedData]['mrp'];
			$productDescription[$decodedData] = $decodedJson[$decodedData]['product_description'];
			$additionalTax[$decodedData] = $decodedJson[$decodedData]['additional_tax'];
			$productCatId[$decodedData] = $decodedJson[$decodedData]['product_category_id'];
			$productGrpId[$decodedData] = $decodedJson[$decodedData]['product_group_id'];
			$companyId[$decodedData] = $decodedJson[$decodedData]['company_id'];
			$branchId[$decodedData] = $decodedJson[$decodedData]['branch_id'];
			
			//get the categoryData from database
			$encodeDataClass = new EncodeAllData();
			$productStatus[$decodedData] = $encodeDataClass->getProductCatData($productCatId[$decodedData]);
			$productDecodedJson[$decodedData] = json_decode($productStatus[$decodedData],true);
			$productCatId[$decodedData]= $productDecodedJson[$decodedData]['productCategoryId'];
			$productCatName[$decodedData]= $productDecodedJson[$decodedData]['productCategoryName'];
			$productCatDesc[$decodedData]= $productDecodedJson[$decodedData]['productCategoryDescription'];
			$productParentCatId[$decodedData]= $productDecodedJson[$decodedData]['productParentCategoryId'];
			$productCatIsDisplay[$decodedData]= $productDecodedJson[$decodedData]['isDisplay'];
			$pCatCreatedAt[$decodedData]= $productDecodedJson[$decodedData]['createdAt'];
			$pCatUpdatedAt[$decodedData]= $productDecodedJson[$decodedData]['updatedAt'];
			
			//product group details from database
			$productGroupDetail = new ProductGroupDetail();
			$getProductGrpDetails[$decodedData] = $productGroupDetail->getProductGrpDetails($productGrpId[$decodedData]);
			
			//get the company detail from database
			$companyDetail  = new CompanyDetail();
			$getCompanyDetails[$decodedData] = $companyDetail->getCompanyDetails($companyId[$decodedData]);
			
			//get the branch detail from database
			$branchDetail  = new BranchDetail();
			$getBranchDetails[$decodedData] = $branchDetail->getBranchDetails($branchId[$decodedData]);
			
			//convert amount(round) into their company's selected decimal points
			$purchasePrice[$decodedData] = round($purchasePrice[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints']);
			$wholesaleMargin[$decodedData] = round($wholesaleMargin[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints']);
			$semiWholesaleMargin[$decodedData] = round($semiWholesaleMargin[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints']);
			$vat[$decodedData] = round($vat[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints']);
			$margin[$decodedData] = round($margin[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints']);
			$mrp[$decodedData] = round($mrp[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints']);
			$additionalTax[$decodedData] = round($additionalTax[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints']);
			
			//product date convertion
			$convertedCreatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$createdAt[$decodedData])->format('d-m-Y');
			$product->setCreated_at($convertedCreatedDate[$decodedData]);
			$getCreatedDate[$decodedData] = $product->getCreated_at();
			
			if(strcmp($updatedAt[$decodedData],'0000-00-00 00:00:00')==0)
			{
				$getUpdatedDate[$decodedData] = "00-00-0000";
			}
			else
			{
				$convertedUpdatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$updatedAt[$decodedData])->format('d-m-Y');
				$product->setUpdated_at($convertedUpdatedDate[$decodedData]);
				$getUpdatedDate[$decodedData] = $product->getUpdated_at();
			}
		}
		$data = array();
		for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
		{
			$data[$jsonData]= array(
				'productId'=>$productId[$jsonData],
				'productName' => $productName[$jsonData],
				'isDisplay' => $isDisplay[$jsonData],
				'purchasePrice' => $purchasePrice[$jsonData],
				'wholesaleMargin' => $wholesaleMargin[$jsonData],
				'semiWholesaleMargin' => $semiWholesaleMargin[$jsonData],
				'vat' => $vat[$jsonData],
				'margin' => $margin[$jsonData],
				'mrp' => $mrp[$jsonData],
				'productDescription' => $productDescription[$jsonData],
				'additionalTax' => $additionalTax[$jsonData],
				'measurementUnit' => $measurementUnit[$jsonData],
				'createdAt' => $getCreatedDate[$jsonData],
				'updatedAt' => $getUpdatedDate[$jsonData],
				
				'productCategory' => array(
					'productCategoryId' => $productCatId[$jsonData],
					'productCategoryName' => $productCatName[$jsonData],
					'productCategoryDescription' => $productCatDesc[$jsonData],
					'productParentCategoryId' => $productParentCatId[$jsonData],
					'createdAt' => $pCatCreatedAt[$jsonData],
					'updatedAt' => $pCatUpdatedAt[$jsonData]
				),
				
				'productGroup' => array(
					'productGroupName' => $getProductGrpDetails[$jsonData]['productGroupName'],	
					'productGroupId' => $getProductGrpDetails[$jsonData]['productGroupId'],	
					'productGroupDescription' => $getProductGrpDetails[$jsonData]['productGroupDescription'],	
					'productParentGroupId' => $getProductGrpDetails[$jsonData]['productGroupParentId'],	
					'isDisplay' => $getProductGrpDetails[$jsonData]['isDisplay'],	
					'createdAt' => $getProductGrpDetails[$jsonData]['createdAt'],	
					'updatedAt' => $getProductGrpDetails[$jsonData]['updatedAt']
				),
				
				'company' => array(
					'companyId' => $getCompanyDetails[$jsonData]['companyId'],	
					'companyName' => $getCompanyDetails[$jsonData]['companyName'],	
					'companyDisplayName' => $getCompanyDetails[$jsonData]['companyDisplayName'],	
					'address1' => $getCompanyDetails[$jsonData]['address1'],	
					'address2'=> $getCompanyDetails[$jsonData]['address2'],	
					'pincode' => $getCompanyDetails[$jsonData]['pincode'],	
					'pan' => $getCompanyDetails[$jsonData]['pan'],	
					'tin'=> $getCompanyDetails[$jsonData]['tin'],	
					'vatNo' => $getCompanyDetails[$jsonData]['vatNo'],	
					'serviceTaxNo' => $getCompanyDetails[$jsonData]['serviceTaxNo'],	
					'basicCurrencySymbol' => $getCompanyDetails[$jsonData]['basicCurrencySymbol'],	
					'formalName' => $getCompanyDetails[$jsonData]['formalName'],	
					'noOfDecimalPoints' => $getCompanyDetails[$jsonData]['noOfDecimalPoints'],	
					'currencySymbol' => $getCompanyDetails[$jsonData]['currencySymbol'],
					'logo' => array(
						'documentName' => $getCompanyDetails[$jsonData]['logo']['documentName'],	
						'documentUrl' => $getCompanyDetails[$jsonData]['logo']['documentUrl'],	
						'documentSize' =>$getCompanyDetails[$jsonData]['logo']['documentSize'],	
						'documentFormat' => $getCompanyDetails[$jsonData]['logo']['documentFormat']
					),
					'isDisplay' => $getCompanyDetails[$jsonData]['isDisplay'],	
					'isDefault' => $getCompanyDetails[$jsonData]['isDefault'],	
					'createdAt' => $getCompanyDetails[$jsonData]['createdAt'],	
					'updatedAt' => $getCompanyDetails[$jsonData]['updatedAt'],	
					'stateAbb' => $getCompanyDetails[$jsonData]['state']['stateAbb'],	
					'cityId' => $getCompanyDetails[$jsonData]['city']['cityId']	
				),
				
				'branch' => array(
					'branchId' => $getBranchDetails[$jsonData]['branchId'],	
					'branchName'=> $getBranchDetails[$jsonData]['branchName'],	
					'address1' => $getBranchDetails[$jsonData]['address1'],	
					'address2' => $getBranchDetails[$jsonData]['address2'],	
					'pincode' => $getBranchDetails[$jsonData]['pincode'],	
					'isDisplay' => $getBranchDetails[$jsonData]['isDisplay'],	
					'isDefault' => $getBranchDetails[$jsonData]['isDefault'],	
					'createdAt' => $getBranchDetails[$jsonData]['createdAt'],	
					'updatedAt' => $getBranchDetails[$jsonData]['updatedAt'],	
					'stateAbb' => $getBranchDetails[$jsonData]['state']['stateAbb'],	
					'cityId' => $getBranchDetails[$jsonData]['city']['cityId'],	
					'companyId' => $getBranchDetails[$jsonData]['company']['companyId']	
				)
			);
		}
		return json_encode($data);
	}
}
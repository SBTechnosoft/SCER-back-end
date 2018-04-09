<?php
namespace ERP\Core\Products\Entities;

use ERP\Core\Products\Entities\Product;
use ERP\Core\ProductCategories\Services\ProductCategoryService;
use ERP\Core\Entities\ProductGroupDetail;
use ERP\Core\Entities\CompanyDetail;
use ERP\Core\Entities\BranchDetail;
use Carbon;
use ERP\Entities\Constants\ConstantClass;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeAllData extends ProductCategoryService
{
	public function getEncodedAllData($status)
	{
		$constantArray = new ConstantClass();
		$constantArrayData = $constantArray->constantVariable();
		$convertedCreatedDate =  array();
		$convertedUpdatedDate =  array();
		$encodeAllData =  array();
		$getCompanyDetails = array();
			
		$decodedJson = json_decode($status,true);
		$product = new Product();
		$dataCount = count($decodedJson);
		$documentDataArray = array();

		for($decodedData=0;$decodedData<$dataCount;$decodedData++)
		{
			$createdAt[$decodedData] = $decodedJson[$decodedData]['created_at'];
			$updatedAt[$decodedData] = $decodedJson[$decodedData]['updated_at'];
			$productId[$decodedData] = $decodedJson[$decodedData]['product_id'];
			$productName[$decodedData] = $decodedJson[$decodedData]['product_name'];
			$measurementUnit[$decodedData] = $decodedJson[$decodedData]['measurement_unit'];
			$isDisplay[$decodedData] = $decodedJson[$decodedData]['is_display'];
			$purchasePrice[$decodedData] = $decodedJson[$decodedData]['purchase_price'];
			$wholesaleMargin[$decodedData] = $decodedJson[$decodedData]['wholesale_margin'];
			$wholesaleMarginFlat[$decodedData] = $decodedJson[$decodedData]['wholesale_margin_flat'];
			$semiWholesaleMargin[$decodedData] = $decodedJson[$decodedData]['semi_wholesale_margin'];
			$vat[$decodedData] = $decodedJson[$decodedData]['vat'];
			$purchaseCgst[$decodedData] = $decodedJson[$decodedData]['purchase_cgst'];
			$purchaseSgst[$decodedData] = $decodedJson[$decodedData]['purchase_sgst'];
			$purchaseIgst[$decodedData] = $decodedJson[$decodedData]['purchase_igst'];
			$margin[$decodedData] = $decodedJson[$decodedData]['margin'];
			$marginFlat[$decodedData] = $decodedJson[$decodedData]['margin_flat'];
			$mrp[$decodedData] = $decodedJson[$decodedData]['mrp'];
			$igst[$decodedData] = $decodedJson[$decodedData]['igst'];
			$hsn[$decodedData] = $decodedJson[$decodedData]['hsn'];
			$color[$decodedData] = $decodedJson[$decodedData]['color'];
			$size[$decodedData] = $decodedJson[$decodedData]['size'];
			$productDescription[$decodedData] = $decodedJson[$decodedData]['product_description'];
			$additionalTax[$decodedData] = $decodedJson[$decodedData]['additional_tax'];
			$minimumStockLevel[$decodedData] = $decodedJson[$decodedData]['minimum_stock_level'];

			$productCode[$decodedData] = $decodedJson[$decodedData]['product_code'];
			$productMenu[$decodedData] = $decodedJson[$decodedData]['product_menu'];
			$productType[$decodedData] = $decodedJson[$decodedData]['product_type'];
			$notForSale[$decodedData] = $decodedJson[$decodedData]['not_for_sale'];
			$maxSaleQty[$decodedData] = $decodedJson[$decodedData]['max_sale_qty'];
			$bestBeforeTime[$decodedData] = $decodedJson[$decodedData]['best_before_time'];
			$bestBeforeType[$decodedData] = $decodedJson[$decodedData]['best_before_type'];
			$cessFlat[$decodedData] = $decodedJson[$decodedData]['cess_flat'];
			$cessPercentage[$decodedData] = $decodedJson[$decodedData]['cess_percentage'];
			$opening[$decodedData] = $decodedJson[$decodedData]['opening'];
			$commission[$decodedData] = $decodedJson[$decodedData]['commission'];
			$remark[$decodedData] = $decodedJson[$decodedData]['remark'];
			$productCoverId[$decodedData] = $decodedJson[$decodedData]['product_cover_id'];

			$documentName[$decodedData] = $decodedJson[$decodedData]['document_name'];
			$documentFormat[$decodedData] = $decodedJson[$decodedData]['document_format'];
			$createdBy[$decodedData] = $decodedJson[$decodedData]['created_by'];
			$updatedBy[$decodedData] = $decodedJson[$decodedData]['updated_by'];
			$productCatId[$decodedData] = $decodedJson[$decodedData]['product_category_id'];
			$productGrpId[$decodedData] = $decodedJson[$decodedData]['product_group_id'];
			$companyId[$decodedData] = $decodedJson[$decodedData]['company_id'];
			$branchId[$decodedData] = $decodedJson[$decodedData]['branch_id'];
			
			//get the categoryData from database
			$encodeDataClass = new EncodeAllData();
			$productStatus[$decodedData] = $encodeDataClass->getProductCatData($productCatId[$decodedData]);
			$productDecodedJson[$decodedData] = json_decode($productStatus[$decodedData],true);
			//product group details from database
			$productGroupDetail = new ProductGroupDetail();
			$getProductGrpDetails[$decodedData] = $productGroupDetail->getProductGrpDetails($productGrpId[$decodedData]);
			
			//get the company detail from database
			$companyDetail  = new CompanyDetail();
			$getCompanyDetails[$decodedData] = $companyDetail->getCompanyDetails($companyId[$decodedData]);
			
			//get the branch detail from database
			$branchDetail  = new BranchDetail();
			$getBranchDetails[$decodedData] = $branchDetail->getBranchDetails($branchId[$decodedData]);
			
			//convert amount(number_format) into their company's selected decimal points
			$purchasePrice[$decodedData] = number_format($purchasePrice[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
			$wholesaleMargin[$decodedData] = number_format($wholesaleMargin[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
			$semiWholesaleMargin[$decodedData] = number_format($semiWholesaleMargin[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
			$vat[$decodedData] = number_format($vat[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
			$purchaseCgst[$decodedData] = number_format($purchaseCgst[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
			$purchaseSgst[$decodedData] = number_format($purchaseSgst[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
			$purchaseIgst[$decodedData] = number_format($purchaseIgst[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
			$margin[$decodedData] = number_format($margin[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
			$mrp[$decodedData] = number_format($mrp[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
			$additionalTax[$decodedData] = number_format($additionalTax[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
			$marginFlat[$decodedData] = number_format($marginFlat[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
			$wholesaleMarginFlat[$decodedData] = number_format($wholesaleMarginFlat[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
			
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
			$documentCount = count($decodedJson[$decodedData]['document']);
			if($documentCount!=0)
			{
				for($documentArray=0;$documentArray<$documentCount;$documentArray++)
				{
					$documentDataArray[$decodedData][$documentArray]['documentName'] = $decodedJson[$decodedData]['document'][$documentArray]['document_name'];
					$documentDataArray[$decodedData][$documentArray]['documentSize'] = $decodedJson[$decodedData]['document'][$documentArray]['document_size'];
					$documentDataArray[$decodedData][$documentArray]['documentFormat'] = $decodedJson[$decodedData]['document'][$documentArray]['document_format'];
					$documentDataArray[$decodedData][$documentArray]['documentType'] = $decodedJson[$decodedData]['document'][$documentArray]['document_type'];
					$documentDataArray[$decodedData][$documentArray]['productId'] = $decodedJson[$decodedData]['document'][$documentArray]['product_id'];
					$documentDataArray[$decodedData][$documentArray]['documentPath'] = 
					strcmp($decodedJson[$decodedData]['document'][$documentArray]['document_type'],'CoverImage')==0 ? $constantArrayData['productCoverDocumentUrl'] : $constantArrayData['productDocumentUrl'];

					$documentDataArray[$decodedData][$documentArray]['createdAt'] = 
					$decodedJson[$decodedData]['document'][$documentArray]['created_at'] == "0000-00-00 00:00:00" ? "0000-00-00" : Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$decodedJson[$decodedData]['document'][$documentArray]['created_at'])->format('d-m-Y');

					$documentDataArray[$decodedData][$documentArray]['updatedAt'] = 
					$decodedJson[$decodedData]['document'][$documentArray]['updated_at'] == "0000-00-00 00:00:00" ? "0000-00-00" : Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$decodedJson[$decodedData]['document'][$documentArray]['updated_at'])->format('d-m-Y');
				}	
			}
			else
			{
				$documentDataArray[$decodedData] = array();
			}
		}
		
		$documentPath = $constantArrayData['productBarcode'];
		$data = array();
		for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
		{
			$data[$jsonData]= array(
				'productId'=>$productId[$jsonData],
				'productName' => $productName[$jsonData],
				'isDisplay' => $isDisplay[$jsonData],
				'purchasePrice' => $purchasePrice[$jsonData],
				'wholesaleMargin' => $wholesaleMargin[$jsonData],
				'wholesaleMarginFlat' => $wholesaleMarginFlat[$jsonData],
				'semiWholesaleMargin' => $semiWholesaleMargin[$jsonData],
				'vat' => $vat[$jsonData],
				'purchaseCgst' => $purchaseCgst[$jsonData],
				'purchaseSgst' => $purchaseSgst[$jsonData],
				'purchaseIgst' => $purchaseIgst[$jsonData],
				'margin' => $margin[$jsonData],
				'marginFlat' => $marginFlat[$jsonData],
				'mrp' => $mrp[$jsonData],
				'igst' => $igst[$jsonData],
				'hsn' => $hsn[$jsonData],
				'color' => $color[$jsonData],
				'size' => $size[$jsonData],
				'productDescription' => $productDescription[$jsonData],
				'additionalTax' => $additionalTax[$jsonData],
				'minimumStockLevel' => $minimumStockLevel[$jsonData],
				'documentName' => $documentName[$jsonData],
				'documentFormat' => $documentFormat[$jsonData],
				'documentPath' => $documentPath,
				'measurementUnit' => $measurementUnit[$jsonData],
				'productCode' => $productCode[$jsonData],
				'productMenu' => $productMenu[$jsonData],
				'productType' => $productType[$jsonData],
				'notForSale' => $notForSale[$jsonData],
				'maxSaleQty' => $maxSaleQty[$jsonData],
				'bestBeforeTime' => $bestBeforeTime[$jsonData],
				'bestBeforeType' => $bestBeforeType[$jsonData],
				'cessFlat' => $cessFlat[$jsonData],
				'cessPercentage' => $cessPercentage[$jsonData],
				'cessPercentage' => $cessPercentage[$jsonData],
				'opening' => $opening[$jsonData],
				'commission' => $commission[$jsonData],
				'remark' => $remark[$jsonData],
				'productCoverId' => $productCoverId[$jsonData],
				'createdBy' => $createdBy[$jsonData],
				'updatedBy' => $updatedBy[$jsonData],
				'createdAt' => $getCreatedDate[$jsonData],
				'updatedAt' => $getUpdatedDate[$jsonData],
				'company' => $getCompanyDetails[$jsonData],
				'branch' => $getBranchDetails[$jsonData],
				'productCategory' => $productDecodedJson[$jsonData],
				'productGroup' => $getProductGrpDetails[$jsonData],
				'document' => $documentDataArray[$jsonData]
			);
		}
		return json_encode($data);
	}
}
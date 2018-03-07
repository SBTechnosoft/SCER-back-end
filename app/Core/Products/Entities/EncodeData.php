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
		$wholesaleMarginFlat= $decodedJson[0]['wholesale_margin_flat'];
		$semiWholeSaleMargin= $decodedJson[0]['semi_wholesale_margin'];
		$vat= $decodedJson[0]['vat'];
		$purchaseCgst= $decodedJson[0]['purchase_cgst'];
		$purchaseSgst= $decodedJson[0]['purchase_sgst'];
		$purchaseIgst= $decodedJson[0]['purchase_igst'];
		$mrp= $decodedJson[0]['mrp'];
		$igst= $decodedJson[0]['igst'];
		$hsn= $decodedJson[0]['hsn'];
		$color= $decodedJson[0]['color'];
		$size= $decodedJson[0]['size'];
		$margin = $decodedJson[0]['margin'];
		$marginFlat= $decodedJson[0]['margin_flat'];
		$productDescription= $decodedJson[0]['product_description'];
		$additionalTax= $decodedJson[0]['additional_tax'];
		$minimumStockLevel= $decodedJson[0]['minimum_stock_level'];

		$productMenu = $decodedJson[0]['product_menu'];
		$productType = $decodedJson[0]['product_type'];
		$notForSale = $decodedJson[0]['not_for_sale'];
		$maxSaleQty = $decodedJson[0]['max_sale_qty'];
		$bestBeforeTime = $decodedJson[0]['best_before_time'];
		$bestBeforeType = $decodedJson[0]['best_before_type'];
		$cessFlat = $decodedJson[0]['cess_flat'];
		$cessPercentage = $decodedJson[0]['cess_percentage'];
		$productCoverId = $decodedJson[0]['product_cover_id'];

		$documentName= $decodedJson[0]['document_name'];
		$documentFormat= $decodedJson[0]['document_format'];
		$productCatId= $decodedJson[0]['product_category_id'];
		$productGrpId= $decodedJson[0]['product_group_id'];
		$companyId= $decodedJson[0]['company_id'];
		$branchId= $decodedJson[0]['branch_id'];
		
		//get the product_cat_details from database
		$encodeProductCatDataClass = new EncodeData();
		$productCatStatus = $encodeProductCatDataClass->getProductCatData($productCatId);
		$productCatDecodedJson = json_decode($productCatStatus,true);
		//get the product group detail from database
		$productGroupDetail  = new ProductGroupDetail();
		$getProductGrpDetails = $productGroupDetail->getProductGrpDetails($productGrpId);
		
		//get the company detail from database
		$companyDetail  = new CompanyDetail();
		$getCompanyDetails = $companyDetail->getCompanyDetails($companyId);
		
		//get the branch detail from database
		$branchDetail  = new BranchDetail();
		$getBranchDetails = $branchDetail->getBranchDetails($branchId);
		
		//convert amount(number_format) into their company's selected decimal points
		$purchasePrice = number_format($purchasePrice,$getCompanyDetails['noOfDecimalPoints'],'.','');
		$wholesaleMargin = number_format($wholesaleMargin,$getCompanyDetails['noOfDecimalPoints'],'.','');
		$semiWholeSaleMargin = number_format($semiWholeSaleMargin,$getCompanyDetails['noOfDecimalPoints'],'.','');
		$vat= number_format($vat,$getCompanyDetails['noOfDecimalPoints'],'.','');
		$purchaseCgst= number_format($purchaseCgst,$getCompanyDetails['noOfDecimalPoints'],'.','');
		$purchaseSgst= number_format($purchaseSgst,$getCompanyDetails['noOfDecimalPoints'],'.','');
		$purchaseIgst= number_format($purchaseIgst,$getCompanyDetails['noOfDecimalPoints'],'.','');
		$mrp= number_format($mrp,$getCompanyDetails['noOfDecimalPoints'],'.','');
		$margin= number_format($margin,$getCompanyDetails['noOfDecimalPoints'],'.','');
		$additionalTax = number_format($additionalTax,$getCompanyDetails['noOfDecimalPoints'],'.','');
		$marginFlat = number_format($marginFlat,$getCompanyDetails['noOfDecimalPoints'],'.','');
		$wholesaleMarginFlat = number_format($wholesaleMarginFlat,$getCompanyDetails['noOfDecimalPoints'],'.','');
		
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
		$documentDataArray = array();
		$documentCount = count($decodedJson[0]['document']);
		if($documentCount!=0)
		{
			for($documentArray=0;$documentArray<$documentCount;$documentArray++)
			{
				$documentDataArray[$documentArray]['documentName'] = $decodedJson[0]['document'][$documentArray]['document_name'];
				$documentDataArray[$documentArray]['documentSize'] = $decodedJson[0]['document'][$documentArray]['document_size'];
				$documentDataArray[$documentArray]['documentFormat'] = $decodedJson[0]['document'][$documentArray]['document_format'];
				$documentDataArray[$documentArray]['documentType'] = $decodedJson[0]['document'][$documentArray]['document_type'];
				$documentDataArray[$documentArray]['productId'] = $decodedJson[0]['document'][$documentArray]['product_id'];
				$documentDataArray[$documentArray]['createdAt'] = 
				$decodedJson[0]['document'][$documentArray]['created_at'] == "0000-00-00 00:00:00" ? "0000-00-00" : Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$decodedJson[0]['document'][$documentArray]['created_at'])->format('d-m-Y');

				$documentDataArray[$documentArray]['updatedAt'] = 
				$decodedJson[0]['document'][$documentArray]['updated_at'] == "0000-00-00 00:00:00" ? "0000-00-00" : Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$decodedJson[0]['document'][$documentArray]['updated_at'])->format('d-m-Y');
			}	
		}
		else
		{
			$documentDataArray = array();
		}
		$constantArray = new ConstantClass();
		$constantArrayData = $constantArray->constantVariable();
		$documentPath = $constantArrayData['productBarcode'];
		
		//set all data into json array
		$data = array();
		$data['productId'] = $productId;
		$data['productName'] = $productName;
		$data['measurementUnit'] = $measurementUnit;
		$data['isDisplay'] = $isDisplay;
		$data['purchasePrice'] = $purchasePrice;
		$data['wholesaleMargin'] = $wholesaleMargin;
		$data['wholesaleMarginFlat'] = $wholesaleMarginFlat;
		$data['semiWholesaleMargin'] = $semiWholeSaleMargin;
		$data['vat'] = $vat;
		$data['purchaseCgst'] = $purchaseCgst;
		$data['purchaseSgst'] = $purchaseSgst;
		$data['purchaseIgst'] = $purchaseIgst;
		$data['mrp'] = $mrp;
		$data['igst'] = $igst;
		$data['hsn'] = $hsn;
		$data['color'] = $color;
		$data['size'] = $size;
		$data['margin'] = $margin;
		$data['marginFlat'] = $marginFlat;
		$data['productDescription'] = $productDescription;
		$data['additionalTax'] = $additionalTax;
		$data['minimumStockLevel'] = $minimumStockLevel;
		$data['productMenu'] = $productMenu;
		$data['productType'] = $productType;
		$data['notForSale'] = $notForSale;
		$data['maxSaleQty'] = $maxSaleQty;
		$data['bestBeforeTime'] = $bestBeforeTime;
		$data['bestBeforeType'] = $bestBeforeType;
		$data['cessFlat'] = $cessFlat;
		$data['cessPercentage'] = $cessPercentage;
		$data['cessPercentage'] = $cessPercentage;
		$data['productCoverId'] = $productCoverId;
		$data['documentName'] = $documentName;
		$data['documentFormat'] = $documentFormat;
		$data['documentPath'] = $documentPath;
		$data['createdAt'] = $getCreatedDate;
		$data['updatedAt'] = $getUpdatedDate;	
		$data['productCategory'] = $productCatDecodedJson;
		$data['productGroup'] = $getProductGrpDetails;
		$data['company'] = $getCompanyDetails;
		$data['branch'] = $getBranchDetails;
		$data['document'] = $documentDataArray;
		$encodeData = json_encode($data);
		return $encodeData;
	}
}
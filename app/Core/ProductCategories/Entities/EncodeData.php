<?php
namespace ERP\Core\ProductCategories\Entities;

use ERP\Core\ProductCategories\Entities\ProductCategory;
use Carbon;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeData
{
	
    public function getEncodedData($status)
	{
		$decodedJson = json_decode($status,true);
			
		$createdAt = $decodedJson[0]['created_at'];
		$updatedAt= $decodedJson[0]['updated_at'];
		$isDisplay= $decodedJson[0]['is_display'];
		$productCatId= $decodedJson[0]['product_cat_id'];
		$productCatName= $decodedJson[0]['product_cat_name'];
		$productCatDesc= $decodedJson[0]['product_cat_desc'];
		
		//date format conversion['created_at','updated_at']
		$productCategory = new ProductCategory();
		$convertedCreatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt)->format('d-m-Y');
		$productCategory->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $productCategory->getCreated_at();
			
		$convertedUpdatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt)->format('d-m-Y');
		$productCategory->setCreated_at($convertedUpdatedDate);
		$getUpdatedDate = $productCategory->getUpdated_at();
		
		//set all data into json array
		$data = array();
		$data['product_cat_name'] = $productCatName;
		$data['product_cat_id'] = $productCatId;
		$data['product_cat_desc'] = $productCatDesc;
		$data['is_display'] = $isDisplay;
		$data['created_at'] = $getCreatedDate;
		$data['updated_at'] = $getUpdatedDate;	
		
		$encodeData = json_encode($data);
		return $encodeData;
	}
}
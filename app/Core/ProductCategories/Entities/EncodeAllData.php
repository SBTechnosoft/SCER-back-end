<?php
namespace ERP\Core\ProductCategories\Entities;

use ERP\Core\ProductCategories\Entities\State;
use Carbon;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeAllData
{
	public function getEncodedAllData($status)
	{
		$convertedCreatedDate =  array();
		$convertedUpdatedDate =  array();
		$encodeAllData =  array();
			
		$decodedJson = json_decode($status,true);
		$productCategory = new ProductCategory();
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$createdAt[$decodedData] = $decodedJson[$decodedData]['created_at'];
			$updatedAt[$decodedData] = $decodedJson[$decodedData]['updated_at'];
			$productCatId[$decodedData] = $decodedJson[$decodedData]['product_category_id'];
			$productCatName[$decodedData] = $decodedJson[$decodedData]['product_category_name'];
			$productCatDesc[$decodedData] = $decodedJson[$decodedData]['product_category_description'];
			$productParentCatId[$decodedData] = $decodedJson[$decodedData]['product_parent_category_id'];
			$isDisplay[$decodedData] = $decodedJson[$decodedData]['is_display'];
			
			$convertedCreatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt[$decodedData])->format('d-m-Y');
				
			$convertedUpdatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt[$decodedData])->format('d-m-Y');
				
		}
		$productCategory->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $productCategory->getCreated_at();
			
		$productCategory->setUpdated_at($convertedUpdatedDate);
		$getUpdatedDate = $productCategory->getUpdated_at();
		$data = array();
		for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
		{
			$data[$jsonData]= array(
				'productCategoryName' => $productCatName[$jsonData],
				'productCategoryId' =>$productCatId[$jsonData],
				'productCategoryDescription' =>$productCatDesc[$jsonData],
				'isDisplay' => $isDisplay[$jsonData],
				'createdAt' => $getCreatedDate[$jsonData],
				'updatedAt' =>$getUpdatedDate[$jsonData],
				'productParentCategoryId' =>$productParentCatId[$jsonData]
			);	
		}
		return json_encode($data);
	}
}
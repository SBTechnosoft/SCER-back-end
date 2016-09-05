<?php
namespace ERP\Core\ProductGroups\Entities;

use ERP\Core\ProductGroups\Entities\State;
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
		$productGroup = new ProductGroup();
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$createdAt[$decodedData] = $decodedJson[$decodedData]['created_at'];
			$updatedAt[$decodedData] = $decodedJson[$decodedData]['updated_at'];
			$productGrpId[$decodedData] = $decodedJson[$decodedData]['product_group_id'];
			$productGrpName[$decodedData] = $decodedJson[$decodedData]['product_group_name'];
			$productGrpDesc[$decodedData] = $decodedJson[$decodedData]['product_group_desc'];
			$isDisplay[$decodedData] = $decodedJson[$decodedData]['is_display'];
			
			$convertedCreatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt[$decodedData])->format('d-m-Y');
				
			$convertedUpdatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt[$decodedData])->format('d-m-Y');
				
		}
		$productGroup->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $productGroup->getCreated_at();
			
		$productGroup->setCreated_at($convertedUpdatedDate);
		$getUpdatedDate = $productGroup->getUpdated_at();
		$data = array();
		for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
		{
			$data[$jsonData]= array(
				'product_group_name' => $productGrpName[$jsonData],
				'product_group_id' =>$productGrpId[$jsonData],
				'product_group_desc' =>$productGrpDesc[$jsonData],
				'isDisplay' => $isDisplay[$jsonData],
				'created_at' => $getCreatedDate[$jsonData],
				'updated_at' =>$getUpdatedDate[$jsonData]
				
			);	
		}
		return json_encode($data);
	}
}
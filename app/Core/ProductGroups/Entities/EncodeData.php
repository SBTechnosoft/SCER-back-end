<?php
namespace ERP\Core\ProductGroups\Entities;

use ERP\Core\ProductGroups\Entities\ProductCategory;
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
		$productGrpId= $decodedJson[0]['product_group_id'];
		$productGrpName= $decodedJson[0]['product_group_name'];
		$productGrpDesc= $decodedJson[0]['product_group_desc'];
		
		//date format conversion['created_at','updated_at']
		$productGroup = new ProductGroup();
		$convertedCreatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt)->format('d-m-Y');
		$productGroup->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $productGroup->getCreated_at();
			
		$convertedUpdatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt)->format('d-m-Y');
		$productGroup->setCreated_at($convertedUpdatedDate);
		$getUpdatedDate = $productGroup->getUpdated_at();
		
		//set all data into json array
		$data = array();
		$data['product_group_name'] = $productGrpName;
		$data['product_group_id'] = $productGrpId;
		$data['product_group_desc'] = $productGrpDesc;
		$data['is_display'] = $isDisplay;
		$data['created_at'] = $getCreatedDate;
		$data['updated_at'] = $getUpdatedDate;	
		
		$encodeData = json_encode($data);
		return $encodeData;
	}
}
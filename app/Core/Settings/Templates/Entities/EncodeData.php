<?php
namespace ERP\Core\Settings\Templates\Entities;

use ERP\Core\Settings\Templates\Entities\Template;
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
		$createdAt= $decodedJson[0]['created_at'];
		$updatedAt= $decodedJson[0]['updated_at'];
		$templateId= $decodedJson[0]['template_id'];
		$templateName= $decodedJson[0]['template_name'];
		$templateType= $decodedJson[0]['template_type'];
		$templateBody= $decodedJson[0]['template_body'];
		
		//date format conversion
		$template = new Template();
		
		$convertedCreatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt)->format('d-m-Y');
		$template->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $template->getCreated_at();
		
		$convertedUpdatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt)->format('d-m-Y');
		$template->setUpdated_at($convertedUpdatedDate);
		$getUpdatedDate = $template->getUpdated_at();
		
		//set all data into json array
		$data = array();
		$data['template_id'] = $templateId;
		$data['template_name'] = $templateName;
		$data['template_body'] = $templateBody;
		$data['template_type'] = $templateType;
		$data['created_at'] = $getCreatedDate;	
		$data['updated_at'] = $getUpdatedDate;	
		
		$encodeData = json_encode($data);
		return $encodeData;
	}
}
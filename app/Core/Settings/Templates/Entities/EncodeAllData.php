<?php
namespace ERP\Core\Settings\Templates\Entities;

use ERP\Core\Settings\Templates\Entities\Template;
use Carbon;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeAllData
{
	public function getEncodedAllData($status)
	{
		$convertedUpdatedDate =  array();
		$encodeAllData =  array();
		$decodedJson = json_decode($status,true);
		$template = new Template();
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$updatedAt[$decodedData] = $decodedJson[$decodedData]['updated_at'];
			$createdAt[$decodedData] = $decodedJson[$decodedData]['created_at'];
			$templateId[$decodedData] = $decodedJson[$decodedData]['template_id'];
			$templateName[$decodedData] = $decodedJson[$decodedData]['template_name'];
			$templateType[$decodedData] = $decodedJson[$decodedData]['template_type'];
			$templateBody[$decodedData] = $decodedJson[$decodedData]['template_body'];
			
			//date format conversion
			$convertedUpdatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt[$decodedData])->format('d-m-Y');
			$convertedCreatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt[$decodedData])->format('d-m-Y');
		}
		$template->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $template->getCreated_at();
		$template->setUpdated_at($convertedUpdatedDate);
		$getUpdatedDate = $template->getUpdated_at();
		
		$data = array();
		for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
		{
			$data[$jsonData]= array(
				'templateId'=>$templateId[$jsonData],
				'templateName' => $templateName[$jsonData],
				'templateType' => $templateType[$jsonData],
				'templateBody' => $templateBody[$jsonData],
				'createdAt' => $getCreatedDate[$jsonData],
				'updatedAt' => $getUpdatedDate[$jsonData]
			);
		}
		return json_encode($data);
	}
}
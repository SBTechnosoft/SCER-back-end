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
			$templateId[$decodedData] = $decodedJson[$decodedData]['template_id'];
			$templateName[$decodedData] = $decodedJson[$decodedData]['template_name'];
			$templateType[$decodedData] = $decodedJson[$decodedData]['template_type'];
			$templateBody[$decodedData] = $decodedJson[$decodedData]['template_body'];
			
			//date format conversion
			$convertedUpdatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt[$decodedData])->format('d-m-Y');
		}
		$template->setUpdated_at($convertedUpdatedDate);
		$getUpdatedDate = $template->getUpdated_at();
		$data = array();
		for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
		{
			$data[$jsonData]= array(
				'template_id'=>$templateId[$jsonData],
				'template_name' => $templateName[$jsonData],
				'template_type' => $templateType[$jsonData],
				'template_body' => $templateBody[$jsonData],
				'updated_at' => $getUpdatedDate[$jsonData]
			);
		}
		return json_encode($data);
	}
}
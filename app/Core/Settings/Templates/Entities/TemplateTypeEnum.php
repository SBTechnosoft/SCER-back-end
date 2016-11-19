<?php
namespace ERP\Core\Settings\Templates\Entities;

/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class TemplateTypeEnum
{
	public function enumArrays()
	{
		$enumArray = array();
		$enumArray['generalTemplate'] = "general";
		$enumArray['quotationTemplate'] = "quotation";
		$enumArray['emailTemplate'] = "email";
		$enumArray['smsTemplate'] = "sms";
		return $enumArray;
	}
}
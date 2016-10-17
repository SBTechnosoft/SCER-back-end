<?php
namespace ERP\Api\V1_0\Templates\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class TemplateTransformer
{
    /**
     * @param 
     * @return array
     */
   public function trimUpdateData()
	{
		$tTemplateArray = array();
		$templateValue;
		$keyValue = func_get_arg(0);
		$templateValue = func_get_arg(1);
		for($data=0;$data<count($templateValue);$data++)
		{
			$tTemplateArray[$data]= array($keyValue=> trim($templateValue));
			
		}
		return $tTemplateArray;
	}
}
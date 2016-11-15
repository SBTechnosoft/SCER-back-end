<?php
namespace ERP\Api\V1_0\Settings\Templates\Transformers;

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
		$convertedValue="";
		for($asciiChar=0;$asciiChar<strlen($keyValue);$asciiChar++)
		{
			if(ord($keyValue[$asciiChar])<=90 && ord($keyValue[$asciiChar])>=65) 
			{
				$convertedValue1 = "_".chr(ord($keyValue[$asciiChar])+32);
				$convertedValue=$convertedValue.$convertedValue1;
			}
			else
			{
				$convertedValue=$convertedValue.$keyValue[$asciiChar];
			}
		}
		$templateValue = func_get_arg(1);
		for($data=0;$data<count($templateValue);$data++)
		{
			$tTemplateArray[$data]= array($convertedValue=> trim($templateValue));
			
		}
		return $tTemplateArray;
	}
}
<?php
namespace ERP\Core\Products\Entities\EnumClasses;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class measurementUnitEnum 
{
    public function enumArrays()
	{
		$enumArray = array();
		$enumArray['type1'] = "kilo";
		$enumArray['type2'] = "litre";
		return $enumArray;
	}
}
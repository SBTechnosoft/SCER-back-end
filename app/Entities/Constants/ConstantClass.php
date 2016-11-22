<?php
namespace ERP\Entities\Constants;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ConstantClass 
{
    public function constantVariable()
	{
		$constantArray = array();
		$constantArray['documentUrl'] = "Storage/Document/";
		$constantArray['billDocumentUrl'] = "Storage/Bill/";
		// $constantArray['notDefault'] = "not";
		return $constantArray;
	}
}

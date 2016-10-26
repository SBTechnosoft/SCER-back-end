<?php
namespace ERP\Entities\EnumClasses;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class IsDisplayEnum 
{
    public function enumArrays()
	{
		$enumArray = array();
		$msgArray['fileFormat'] = "FileNotFoundException: File format is not valid(ex.valid format:jpg,jpeg,gif,png,pdf)";
		$msgArray['fileSize'] = "FileNotFoundException: The file is too long";
		$msgArray['500'] = "500: Internal Server Error";
		$msgArray['200'] = "200: OK";
		$msgArray['204'] = "204: No Content";
		$msgArray['404'] = "404: Not Found";
		return $msgArray;
	}
}

<?php
namespace ERP\Core\Entities;

use ERP\Core\Branches\Services\BranchService;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BranchDetail extends BranchService 
{
	public function getBranchDetails($branchId)
	{
		//get the branch data from database
		$encodeBranchDataClass = new BranchDetail();
		echo "hhh";
		$branchStatus = $encodeBranchDataClass->getBranchData($branchId);
		echo "hell";
		print_r($branchStatus);
		$branchDecodedJson = json_decode($branchStatus,true);
		return $branchDecodedJson;
	}
    
}
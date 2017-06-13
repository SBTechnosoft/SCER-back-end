<?php
namespace ERP\Core\Clients\Entities;

/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ClientArray
{
	public function getClientArrayData()
	{
		$clientArray = array();
		$clientArray['client_name'] = 'clientName';
		$clientArray['company_name'] = 'companyName';
		$clientArray['contact_no'] = 'contactNo';
		$clientArray['work_no'] = 'workNo';
		$clientArray['email_id'] = 'emailId';
		$clientArray['address1'] = 'address1';
		$clientArray['address2'] = 'address2';
		$clientArray['is_display'] = 'isDisplay';
		$clientArray['city_id'] = 'cityId';
		$clientArray['state_abb'] = 'stateAbb';
		return $clientArray;
	}
	
	public function searchClientData()
	{
		$clientArray = array();
		$clientArray['client_name'] = 'clientname';
		// $clientArray['company_id'] = 'companyid';
		// $clientArray['from_date'] = 'fromdate';
		// $clientArray['to_date'] = 'todate';
		return $clientArray;
	}
	
	public function getBillClientArrayData()
	{
		$clientArray = array();
		$clientArray['client_name'] = 'clientName';
		$clientArray['company_name'] = 'companyName';
		$clientArray['contact_no'] = 'contactNo';
		$clientArray['work_no'] = 'workNo';
		$clientArray['email_id'] = 'emailId';
		$clientArray['address1'] = 'address1';
		$clientArray['address2'] = 'address2';
		$clientArray['is_display'] = 'isDisplay';
		$clientArray['city_id'] = 'cityId';
		$clientArray['state_abb'] = 'stateAbb';
		$clientArray['transaction_date'] = 'transactionDate';
		return $clientArray;
	}
}
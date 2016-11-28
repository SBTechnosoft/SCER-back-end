<?php
namespace ERP\Api\V1_0\Clients\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
use ERP\Entities\EnumClasses\IsDisplayEnum;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ClientTransformer
{
    /**
     * @param Request $request
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		$isDisplayFlag=0;
		$clientDataFlag=0;
		//data get from body
		$clientName = $request->input('clientName'); 
		$companyName = $request->input('companyName'); 
		$contactNo = $request->input('contactNo'); 
		$workNo = $request->input('workNo'); 
		$emailId = $request->input('emailId'); 
		$address1 = $request->input('address1'); 
		$address2 = $request->input('address2'); 
		$isDisplay = $request->input('isDisplay'); 			
		$stateAbb = $request->input('stateAbb'); 			
		$cityId = $request->input('cityId'); 			
		
		//trim an input
		$tClientName = trim($clientName);
		$tCompanyName = trim($companyName);
		$tContactNo = trim($contactNo);
		$tWorkNo = trim($workNo);
		$tEmailId = trim($emailId);
		$tAddress1 = trim($address1);
		$tAddress2 = trim($address2);
		$tIsDisplay = trim($isDisplay);
		$tStateAbb = trim($stateAbb);
		$tCityId = trim($cityId);
		//check is_display is exist or not
		for($clientData=0;$clientData<count($request->input());$clientData++)
		{
			if(strcmp(array_keys($request->input())[$clientData],"isDisplay")==0)
			{
				$clientDataFlag=1;
				break;
			}
		}
		$enumIsDispArray = array();
		$isDispEnum = new IsDisplayEnum();
		$enumIsDispArray = $isDispEnum->enumArrays();
		if($clientDataFlag==1)
		{
			if($tIsDisplay=="")
			{
				$tIsDisplay=$enumIsDispArray['display'];
			}
			else
			{
				foreach ($enumIsDispArray as $key => $value)
				{
					if(strcmp($value,$tIsDisplay)==0)
					{
						$isDisplayFlag=1;
						break;
					}
					else
					{
						$isDisplayFlag=2;
					}
				}
			}
		}
		else
		{
			$tIsDisplay=$enumIsDispArray['display'];
		}
		if($isDisplayFlag==2)
		{
			return "1";
		}
		else
		{
			//make an array
			$data = array();
			$data['client_name'] = $tClientName;
			$data['company_name'] = $tCompanyName;
			$data['contact_no'] = $tContactNo;
			$data['work_no'] = $tWorkNo;
			$data['email_id'] = $tEmailId;
			$data['address1'] = $tAddress1;
			$data['address2'] = $tAddress2;
			$data['is_display'] = $tIsDisplay;
			$data['state_abb'] = $tStateAbb;
			$data['city_id'] = $tCityId;
			return $data;
		}
	}
}
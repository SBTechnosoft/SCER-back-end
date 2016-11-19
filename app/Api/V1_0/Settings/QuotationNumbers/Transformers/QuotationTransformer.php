<?php
namespace ERP\Api\V1_0\Settings\QuotationNumbers\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
use ERP\Core\Settings\QuotationNumbers\Entities\QuotationTypeEnum;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class QuotationTransformer
{
    /**
     * @param 
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		$quotationTypeFlag=0;
		//data get from body
		$quotationLabel = $request->input('quotationLabel'); 
		$quotationType = $request->input('quotationType'); 
		$startAt = $request->input('startAt'); 
		$endAt = $request->input('endAt'); 
		$companyId = $request->input('companyId');  
		
		//trim an input
		$tQuotationLabel = trim($quotationLabel);
		$tQuotationType = trim($quotationType);
		$tStartAt = trim($startAt);
		$tEndAt = trim($endAt);
		$tCompanyId = trim($companyId);
		
		$enumQuotationTypeArray = array();
		$quotationTypeEnum = new QuotationTypeEnum();
		$enumQuotationTypeArray = $quotationTypeEnum->enumArrays();
		foreach ($enumQuotationTypeArray as $key => $value)
		{
			if(strcmp($value,$tQuotationType)==0)
			{
				$quotationTypeFlag=1;
				break;
			}
		}
		if($quotationTypeFlag==0)
		{
			return "1";
		}
		else
		{
			//make an array
			$data = array();
			$data['quotation_label'] = $tQuotationLabel;
			$data['quotation_type'] = $tQuotationType;
			$data['start_at'] = $tStartAt;
			$data['end_at'] = $tEndAt;
			$data['company_id'] = $tCompanyId;
			return $data;
		}
	}
}
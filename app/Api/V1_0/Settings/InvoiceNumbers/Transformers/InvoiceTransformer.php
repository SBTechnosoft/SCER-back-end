<?php
namespace ERP\Api\V1_0\Settings\InvoiceNumbers\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
use ERP\Core\Settings\InvoiceNumbers\Entities\InvoiceTypeEnum;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class InvoiceTransformer
{
    /**
     * @param 
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		$invoiceTypeFlag=0;
		//data get from body
		$invoiceLabel = $request->input('invoiceLabel'); 
		$invoiceType = $request->input('invoiceType'); 
		$startAt = $request->input('startAt'); 
		$endAt = $request->input('endAt'); 
		$companyId = $request->input('companyId');  
		
		//trim an input
		$tInvoiceLabel = trim($invoiceLabel);
		$tInvoiceType = trim($invoiceType);
		$tStartAt = trim($startAt);
		$tEndAt = trim($endAt);
		$tCompanyId = trim($companyId);
		
		$enumInvoiceTypeArray = array();
		$invoiceTypeEnum = new InvoiceTypeEnum();
		$enumInvoiceTypeArray = $invoiceTypeEnum->enumArrays();
		foreach ($enumInvoiceTypeArray as $key => $value)
		{
			if(strcmp($value,$tInvoiceType)==0)
			{
				$invoiceTypeFlag=1;
				break;
			}
		}
		if($invoiceTypeFlag==0)
		{
			return "1";
		}
		else
		{
			//make an array
			$data = array();
			$data['invoice_label'] = $tInvoiceLabel;
			$data['invoice_type'] = $tInvoiceType;
			$data['start_at'] = $tStartAt;
			$data['end_at'] = $tEndAt;
			$data['company_id'] = $tCompanyId;
			return $data;
		}
	}
}
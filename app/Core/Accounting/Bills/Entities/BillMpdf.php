<?php
namespace ERP\Core\Accounting\Bills\Entities;

use mPDF;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BillMpdf 
{
	public function mpdfGenerate()
	{
		$templateArray = func_get_arg(0);
		$htmlBody = $templateArray[0]->template_body;
		
		$billArray = array();
		$billArray['Company']="Siliconbrain";
		$billArray['ClientName']="Reema";
		$billArray['OrderDate']="25-11-2016";
		$billArray['CLIENTADD']="dfs,sg/sgs-343434";
		$billArray['INVID']="rgrfd";
		$billArray['ClientCharge']="500";
		$billArray['Discount']="50%";
		$billArray['TaxAmt']="100";
		$billArray['Total']="300";
		
		$mpdf = new mPDF();
		$mpdf->SetDisplayMode('fullpage');
		foreach($billArray as $key => $value)
		{
			$htmlBody = str_replace('['.$key.']', $value, $htmlBody);
		}
		// print_r($htmlBody);
		$mpdf->WriteHTML($htmlBody);
		$path = "F:\www\htdocs\SCER-back-end\public\Storage";
		$mpdf->Output('filename1.pdf','F');
		
	}
}

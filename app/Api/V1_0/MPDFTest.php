<?php
namespace ERP\Api\V1_0;

use mPDF;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class MPDFTest 
{
	public function test()
	{
		$mpdf = new mPDF();
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->WriteHTML('<p>Your first taste of creating PDF from HTML</p>');
		$mpdf->Output();
		echo "test";
	}
}

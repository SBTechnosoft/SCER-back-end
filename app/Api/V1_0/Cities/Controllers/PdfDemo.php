<?php
namespace ERP\Api\V1_0\Cities\Controllers;
use mPDF;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class PdfDemo 
{
	public function demo()
	{
		echo "demo";
		$pdf  =new mPDF('utf-8');
		echo "hh";
		// $mpdf->WriteHTML('Hello World');
		// $mpdf->Output();
		echo "end";
		exit;
		// $pdf = \App::make('mpdf.wrapper',['th','A0','','',10,10,10,10,10,5,'L']);
		// $pdf->WriteHTML('<h1>test</h1>');
		// $pdf->AddPage('P');
		// $pdf->WriteHTML('<h1>test2</h1>');
		// $pdf->stream();
		// $html = '<html><body>'. '<p>Hello, Welcome to TechZoo.</p>'. '</body></html>';
		
		// $mpdf=new mPDF('c','A4','','GEORGIAN' , 25 , 25 , 30 , 15 , 0 , 0); 
	 // echo "hh";
	// $mpdf->SetDisplayMode('fullpage');
	 
	// $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
	 
	// $mpdf->WriteHTML($html);
			 
	// $mpdf->Output('meu-pdf','I');//this fn on 8174 line in mpdf.php
		// exit;
	}
}
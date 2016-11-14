<?php
namespace ERP\Api\V1_0;

use PHPMailer;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class MailTest 
{
	public function test()
	{
		$mail = new PHPMailer;
		// $mpdf = new mPDF();
		// $mpdf->SetDisplayMode('fullpage');
		// $mpdf->WriteHTML('<p>Your first taste of creating PDF from HTML</p>');
		// $mpdf->Output();
		echo "test1";
		$email = "reemapatel25@gmail.com";
		$message = "hello";
		$mail->IsSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'sg2plcpnl0073.prod.sin2.secureserver.net';                // Specify main and backup server //sg2plcpnl0073.prod.sin2.secureserver.net port=465
		$mail->Port =  465;                                    // Set the SMTP port
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		echo "test2";	
		// SMTP password
		$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
		$mail->Username = 'reema.p@siliconbrain.in';                // SMTP username
		$mail->Password = 'Abcd@1234'; 
		$mail->From = 'reema.p@siliconbrain.in';
		$mail->FromName = 'reema.p@siliconbrain.in';
		$mail->AddAddress($email);  // Add a recipient
		//$mail->AddAddress('ellen@example.com');               // Name is optional

		$mail->IsHTML(true);                                  // Set email format to HTML

		$mail->Subject = 'Here is the subject';
		$mail->Body    = $message;
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		if(!$mail->Send()) {
		   echo 'Message could not be sent.';
		   echo 'Mailer Error: ' . $mail->ErrorInfo;
		   exit;
		}
		else
		{
			echo 'Message has been sent';
		}
	}
}

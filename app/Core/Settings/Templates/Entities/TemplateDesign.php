<?php
namespace ERP\Core\Settings\Templates\Entities;

/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class TemplateDesign
{
	public function getTemplate()
	{
		$templateArray = array();
		$templateArray['invoice'] =  "<div style=''background-image: url(''http://4.bp.blogspot.com/-J9g2UmC8cJk/UCyoyj24VMI/AAAAAAAAEDg/Q3oUk33685w/s1600/Indian+Flag+Wallpapers-03.jpg''); background-size: 100% 100%;''>
									<table style=''border-collapse: collapse; border-spacing: 0px; margin-left: auto; margin-right: auto;'' border=''1'' cellspacing=''0''>
									<tbody>
									<tr style=''height: 250px;''>
									<th style=''height: 250px; text-align: center;'' colspan=''12''>[CMPLOGO]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Company]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style=''color: #00ffff;''><strong>INVOICE</strong></span></th>
									</tr>
									<tr style=''background-color: transparent; height: 50px; text-align: left;''>
									<td style=''font-family: Calibri; font-size: 12px; vertical-align: middle; height: 50px; text-align: left;'' colspan=''7''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; INVOICE SHIP TO: <span style=''color: #000000;''><strong>[ClientName]<br /></strong></span></td>
									<td style=''font-family: Calibri; font-size: 12px; vertical-align: middle; color: #4e4e4e; text-align: left; height: 50px;'' colspan=''5''>Invoice No : [INVID]</td>
									</tr>
									<tr class=''trhw'' style=''height: 50px; text-align: left;''>
									<td class=''tg-vi9z'' style=''font-family: Calibri; font-size: 12px; vertical-align: middle; color: #4e4e4e; height: 50px;'' colspan=''7''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Address : [CLIENTADD]</td>
									<td class=''tg-vi9z'' style=''font-family: Calibri; font-size: 12px; vertical-align: middle; color: #4e4e4e; height: 50px;'' colspan=''5''>Invoice Date: [OrderDate]</td>
									</tr>
									<tr class=''trhw'' style=''background-color: transparent; height: 50px; text-align: left;''>
									<td class=''tg-vi9z'' style=''font-family: Calibri; font-size: 12px; vertical-align: middle; color: #4e4e4e; height: 50px;'' colspan=''7''>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; Mobile No: [Mobile]</td>
									<td class=''tg-vi9z'' style=''font-family: Calibri; font-size: 12px; vertical-align: middle; color: #4e4e4e; height: 50px;'' colspan=''5''>Credit Limit:</td>
									</tr>
									<tr class=''trhw'' style=''font-family: Calibri; height: 30px; text-align: left;''>
									<td class=''tg-m36b thsrno'' style=''font-size: 12px; padding: 5px; color: #e1e1e1; text-align: center; height: 30px;''><span style=''color: #000000;''><strong>Sr.No</strong></span></td>
									<td class=''tg-m36b theqp'' style=''font-size: 12px; padding: 5px; color: #e1e1e1; height: 30px; text-align: left;''><span style=''color: #000000;''><strong>Particulars</strong></span></td>
									<td class=''tg-ullm thsrno'' style=''font-size: 12px; padding: 5px; color: #e1e1e1; height: 30px; text-align: center;''><span style=''color: #000000;''><strong>Color</strong></span></td>
									<td class=''tg-ullm thsrno'' style=''font-size: 12px; padding: 5px; color: #e1e1e1; height: 30px; text-align: left;''><span style=''color: #000000;''><strong>Frame No</strong></span></td>
									<td class=''tg-ullm thsrno'' style=''font-size: 12px; padding: 5px; color: #e1e1e1; height: 30px; text-align: center;''><span style=''color: #000000;''><strong>Qty</strong></span></td>
									<td class=''tg-ullm thsrno'' style=''font-size: 12px; padding: 5px; color: #e1e1e1; height: 30px; text-align: center;''><span style=''color: #000000;''><strong>Rate</strong></span></td>
									<td class=''tg-ullm thsrno'' style=''font-size: 12px; padding: 5px; color: #e1e1e1; height: 30px; text-align: center;''><span style=''color: #000000;''><strong>Discount</strong></span></td>
									<td class=''tg-ullm thamt'' style=''font-size: 12px; padding: 5px; color: #e1e1e1; height: 30px; text-align: center;''><span style=''color: #000000;''><strong>VAT%</strong></span></td>
									<td class=''tg-ullm thamt'' style=''font-size: 12px; padding: 5px; color: #e1e1e1; height: 30px; text-align: center;''><span style=''color: #000000;''><strong>VAT</strong></span></td>
									<td class=''tg-ullm thamt'' style=''font-size: 12px; padding: 5px; color: #e1e1e1; height: 30px; text-align: center;''><span style=''color: #000000;''><strong>A.Tax%</strong></span></td>
									<td class=''tg-ullm thamt'' style=''font-size: 12px; padding: 5px; color: #e1e1e1; height: 30px; text-align: center;''><span style=''color: #000000;''><strong>A.Tax</strong></span></td>
									<td class=''tg-ullm thamt'' style=''font-size: 12px; padding: 5px; color: #e1e1e1; height: 30px; text-align: center;''><span style=''color: #000000;''><strong>Amount</strong></span></td>
									</tr>
									<tr class=''trhw'' style=''font-family: Calibri; text-align: left; height: 14px; background-color: transparent; display: [displayNone];''>
									<td class=''tg-m36b thsrno'' style=''font-size: 12px; color: #000000; height: 14px;'' colspan=''12''>[Description]</td>
									</tr>
									<tr class=''trhw'' style=''height: 50px; text-align: left;''>
									<td class=''tg-jtyd'' style=''font-size: 10px; padding: 5px; color: #e1e1e1; height: 50px; text-align: left;'' colspan=''4''><span style=''color: #000000;''>[TotalInWord]</span>&nbsp;</td>
									<td class=''tg-jtyd'' style=''font-size: 12px; padding: 5px; color: #e1e1e1; height: 50px; text-align: center;''><span style=''color: #000000;''><strong>[TotalQty]</strong>&nbsp;</span></td>
									<td class=''tg-jtyd'' style=''text-align: right; font-size: 12px; padding: 5px; color: #e1e1e1; height: 50px;'' colspan=''2''>&nbsp;</td>
									<td class=''tg-jtyd'' style=''text-align: right; font-size: 12px; padding: 5px; color: #e1e1e1; height: 50px;'' colspan=''2''>&nbsp;</td>
									<td class=''tg-jtyd'' style=''text-align: right; font-size: 12px; padding: 5px; color: #e1e1e1; height: 50px;''><strong><span style=''color: #000000;''>Total</span></strong></td>
									<td class=''tg-jtyd'' style=''text-align: right; font-size: 12px; padding: 5px; color: #e1e1e1; height: 50px;''><span style=''color: #000000;''><strong>[TotalTax]</strong>&nbsp;</span></td>
									<td class=''tg-3gzm'' style=''font-size: 12px; padding: 5px; color: #4e4e4e; height: 20px; text-align: center;''><strong>&nbsp;<span style=''color: #000000;''>[Total]</span></strong></td>
									</tr>
									<tr class=''trhw'' style=''background-color: transparent; height: 80px; text-align: left;''>
									<td class=''tg-vi9z'' style=''padding: 5px; height: 80px; text-align: center; vertical-align: middle;'' colspan=''4''>
									<p>&nbsp;Status: Pending Payment</p>
									<p style=''padding-top: 10px;''><span style=''color: #000000;''><strong> [REMAINAMT]</strong></span></p>
									</td>
									<td class=''tg-vi9z'' style=''padding: 5px; height: 80px; text-align: center; vertical-align: middle;'' colspan=''4''>
									<p>Signature of Vat Dealer</p>
									<p>&nbsp;</p>
									</td>
									<td class=''tg-vi9z'' style=''padding: 5px; height: 80px; text-align: center; vertical-align: middle;'' colspan=''4''>
									<p style=''padding: 0 0 5px 0;''>Net Amount</p>
									<p><span style=''color: #000000;''><strong style=''padding: 5px;''>&nbsp;[Total]</strong></span></p>
									</td>
									</tr>
									<tr style=''height: 50px; text-align: left;''>
									<td class=''tg-3gzm'' style=''text-align: center; vertical-align: bottom; height: 80px;'' colspan=''1''>&nbsp;</td>
									<td style=''vertical-align: bottom; color: #4e4e4e; height: 80px;'' colspan=''6''>Remark</td>
									<td class=''tg-3gzm'' style=''text-align: center; vertical-align: bottom; color: #4e4e4e; height: 60px;'' colspan=''5''>Venture Of</td>
									</tr>
									<tr style=''background-color: transparent; height: 80px; text-align: left;''>
									<td class=''tg-3gzm'' style=''text-align: center; vertical-align: bottom;'' colspan=''1''>&nbsp;</td>
									<td style=''vertical-align: bottom; color: #4e4e4e; height: 80px;'' colspan=''6''>E.&amp;.O.E.</td>
									<td class=''tg-3gzm'' style=''text-align: center; height: 80px;'' colspan=''5''>[CMPLOGO]</td>
									</tr>
									</tbody>
									</table>
									<p style=''text-align: left;''>&nbsp;</p>
									</div>";
		$templateArray['payment'] = "<div style=''background-image: url(''http://4.bp.blogspot.com/-J9g2UmC8cJk/UCyoyj24VMI/AAAAAAAAEDg/Q3oUk33685w/s1600/Indian+Flag+Wallpapers-03.jpg''); background-size: 100% 100%;''>

									<table class=''tg'' style=''border-collapse: collapse; border-spacing: 0;''>

									<tbody>

									<tr>

									<th style=''height: 150px; font-size: 26px; border: none; padding: 5px;''>[CmpLogo]</th>

									<th style=''height: 150px; font-size: 26px; border: none;''>

									<h3>Payment Receipt</h3>

									</th>

									</tr>

									</tbody>

									</table>

									<table class=''tg'' style=''border-collapse: collapse; border-spacing: 0;margin-left: auto; margin-right: auto; width: 100%;'' margin-left: auto; margin-right: auto; width: 100%;border=''1''>

									<tbody>

									<tr style=''height: 50px;''>

									<td style=''font-size: 16px; padding: 10px;''>Receipt No:[INVID]</td>

									<td style=''font-size: 16px; padding: 10px;''>Date:[Date]</td>

									</tr>

									<tr style=''height: 50px; background-color: transparent;''>

									<td style=''font-size: 16px; padding: 10px;'' colspan=''2''>Received With Thanks From:[ClientName]</td>

									</tr>

									<tr style=''height: 50px;''>

									<td style=''font-size: 16px; padding: 10px; text-align: right;'' colspan=''2''>Amount:[Total]</td>

									</tr>

									<tr style=''height: 50px; background-color: transparent;''>

									<td style=''font-size: 16px; padding: 10px; text-align: right;'' colspan=''2''>In Words:[TotalInWord]</td>

									</tr>

									<tr style=''height: 50px;''>

									<td style=''font-size: 16px; padding: 10px; text-align: right;'' colspan=''2''>By:[TransType]</td>

									</tr>

									<tr style=''height: 50px; background-color: transparent;''>

									<td style=''font-size: 16px; padding: 10px;''>

									<p>&nbsp;</p>

									<p>&nbsp;</p>

									<p>Prepared By:</p>

									</td>

									<td style=''font-size: 16px; padding: 10px;''>

									<p>&nbsp;</p>

									<p>&nbsp;</p>

									<p>Received By:</p>

									</td>

									</tr>

									</tbody>

									</table>

									</div>";
		$templateArray['email'] = " <p>Dear [ClientName],</p>
									<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Welcome to [Company] Family.</p>
									<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Thank you for shopping with us. Please find your invoice attached with this Mail</p>
									<p>Thanks &amp; Regards</p>
									<p>The Team [Company].</p>";
		$templateArray['sms'] = "<p>Dear [ClientName],</p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Thank you for shopping with us.</p>";
		$templateArray['blank'] = "<div style=''background-size: 100% 100%; height: 28.3cm; width: 20.5cm;''>
								<table style=''border-collapse: collapse; border-spacing: 0px; margin-left: auto; margin-right: auto;height: 27.8cm; width: 21.3cm;'' cellspacing=''0''>
								<tbody>
								<tr style=''height: 6cm;''>
								<th style=''height: 6cm; text-align: left;'' colspan=''12''></th>
								</tr>
								<tr style=''background-color: transparent; height: 0.8cm;  text-align: left;''>
								<td style=''font-family: Calibri; font-size: 12px; vertical-align: top; height: 0.8cm;width:13.6cm; text-align: left;'' colspan=''7'' rowspan=''2''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=''color: #000000;''><strong>[ClientName]</strong></span> <br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span >[CLIENTADD]</span></td>
								<td style=''font-family: Calibri; font-size: 12px; vertical-align: middle; color: #4e4e4e; text-align: left; height: 0.8cm;width:7.7cm;'' colspan=''5''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=''color: #000000;''>[INVID]</span></td>
								</tr>
								<tr class=''trhw'' style=''height: 0.8cm; text-align: left;''>

								<td class=''tg-vi9z'' style=''font-family: Calibri; font-size: 12px; vertical-align: middle; color: #4e4e4e; height: 0.8cm;'' colspan=''12''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span >[OrderDate]</span></td>
								</tr>
								<tr class=''trhw'' style=''background-color: transparent; height: 0.8cm; text-align: left;''>
								<td class=''tg-vi9z'' style=''font-family: Calibri; font-size: 12px; vertical-align: middle; color: #4e4e4e; height: 0.8cm;'' colspan=''7''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span >[TINNO]</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Mobile No:</strong>&nbsp;&nbsp;<span>[Mobile]</span></td>
								<td class=''tg-vi9z'' style=''font-family: Calibri; font-size: 12px; vertical-align: middle; color: #4e4e4e; height: 0.8cm;'' colspan=''5''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span >0</span></td>
								</tr>
								<tr class=''trhw'' style=''font-family: Calibri; height: 1.2cm; text-align: left;''>
								<td class=''tg-m36b thsrno'' style=''font-size: 12px; padding: 5px; text-align: center; height: 1.2cm;''><span style=''color: #000000;''><strong>Sr.No</strong></span></td>
								<td class=''tg-m36b theqp'' style=''font-size: 12px; padding: 5px; height: 1.2cm; text-align: left;''><span style=''color: #000000;''><strong>Particulars</strong></span></td>
								<td class=''tg-ullm thsrno'' style=''font-size: 12px; height: 1.2cm; text-align: center; padding: 5px 2px 5px 2px;''><span style=''color: #000000;''><strong>Color | Size</strong></span></td>
								<td class=''tg-ullm thsrno'' style=''font-size: 12px; padding: 5px; height: 1.2cm; text-align: left;''><span style=''color: #000000;''><strong>Frame No</strong></span></td>
								<td class=''tg-ullm thsrno'' style=''font-size: 12px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>Qty</strong></span></td>
								<td class=''tg-ullm thsrno'' style=''font-size: 12px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>Rate</strong></span></td>
								<td class=''tg-ullm thsrno'' style=''font-size: 12px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>Discount</strong></span></td>
								<td class=''tg-ullm thamt'' style=''font-size: 12px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>VAT%</strong></span></td>
								<td class=''tg-ullm thamt'' style=''font-size: 12px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>VAT</strong></span></td>
								<td class=''tg-ullm thamt'' style=''font-size: 12px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>A.Vat%</strong></span></td>
								<td class=''tg-ullm thamt'' style=''font-size: 12px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>A.Vat</strong></span></td>
								<td class=''tg-ullm thamt'' style=''font-size: 12px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>Amount</strong></span></td>
								</tr>
								<tr class=''trhw'' style=''font-family: Calibri; text-align: left; height: 9.8cm; background-color: transparent; display: [displayNone];''>
									<td class=''tg-m36b thsrno'' style=''font-size: 12px; color: #000000; height: 9.8cm;'' colspan=''12''>[Description]</td>
								</tr>
								<tr  style=''height: 0.7cm; text-align: left;''>
								<td class=''tg-jtyd'' style=''font-size: 10px; padding: 5px; height: 0.7cm; text-align: left;border-top: 1px solid black;border-bottom: 1px solid black;'' colspan=''4''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=''color: #000000;''>[TotalInWord]</span></td>
								<td class=''tg-jtyd'' style=''font-size: 12px; padding: 5px; height: 0.7cm; text-align: left;border-top: 1px solid black;border-bottom: 1px solid black;'' colspan=''3'' ><span style=''color: #000000;''><strong>&nbsp;&nbsp;&nbsp;[TotalQty]</strong></span></td>

								<td class=''tg-jtyd'' style=''text-align: right; font-size: 12px; padding: 5px; height: 0.7cm;border-top: 1px solid black;border-bottom: 1px solid black;'' colspan=''2''>&nbsp;</td>
								<td class=''tg-jtyd'' style=''text-align: right; font-size: 12px; padding: 5px; height: 0.7cm;border-top: 1px solid black;border-bottom: 1px solid black;''><strong><span style=''color: #000000;''>Total</span></strong></td>
								<td class=''tg-jtyd'' style=''text-align: right; font-size: 12px; padding: 5px; height: 0.7cm;border-top: 1px solid black;border-bottom: 1px solid black;''><span style=''color: #000000;''><strong>[TotalTax]</strong>&nbsp;</span></td>
								<td class=''tg-3gzm'' style=''font-size: 12px; padding: 5px; color: #4e4e4e; height: 0.7cm; text-align: center;border-top: 1px solid black;border-bottom: 1px solid black;''><strong>&nbsp;<span style=''color: #000000;''>[Total]</span></strong></td>
								</tr>
								<tr style=''background-color: transparent; height: 2.85cm; text-align: left;''>
								<td class=''tg-3gzm'' style=''text-align: center; vertical-align: bottom; height: 2.85cm;'' colspan=''12''></td>
								</tr>
								<tr class=''trhw'' style=''background-color: transparent; height: 2.4cm; text-align: left;''>
								<td class=''tg-vi9z'' style=''padding: 5px; height: 2.4cm; text-align: center; vertical-align: bottom;'' colspan=''4''>
								<p style=''visibility: hidden;''>&nbsp;</p>
								<p style=''padding-top: 10px;''><span style=''color: #000000;font-size: 22px;''><strong> [REMAINAMT]</strong></span></p>
								</td>
								<td class=''tg-vi9z'' style=''padding: 5px; height: 2.4cm; text-align: center; vertical-align: middle;visibility: hidden;'' colspan=''4''>
								<p></p>
								<p>&nbsp;</p>
								</td>
								<td class=''tg-vi9z'' style=''padding: 5px; height: 2.4cm; text-align: center; vertical-align: bottom;'' colspan=''4''>
								<p style=''padding: 0 0 5px 0;visibility: hidden;''>&nbsp;</p>
								<p><span style=''color: #000000;''><strong style=''padding: 5px;font-size: 22px;''>&nbsp;[Total]</strong></span></p>
								</td>
								</tr>
								<tr style=''background-color: transparent; height: 2.2cm; text-align: left;''>
								<td style=''padding: 5px; height: 2.2cm; text-align: center; vertical-align: middle;'' colspan=''12''>
								</td>
								</tr>
								</tbody>
								</table>
								</div>";
		return $templateArray;
	}
}
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

		$templateArray['Invoice'] =  "<table style=''height: 100%; width: 100%; margin: 0 0 0 0; font-family: calibri; border: 1px solid black; border-collapse: collapse; border-padding: 0;'' cellspacing=''0'' cellpadding=''0''>
<tbody style=''height: 10px;''>
<tr style=''padding: 0px; padding-top: 5px;''>
<td style=''text-align: left; vertical-align: top; font-size: 8px; padding-top: 5px;'' colspan=''2''>&nbsp;</td>
<td style=''text-align: center;'' colspan=''11''><strong><span style=''font-size: 10px; vertical-align: top; padding: 0; text-align: top !important; padding-top: 5px;''>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ''SWAMINARAYANO VIJAYTE''</span></strong></td>
<td style=''text-align: right; vertical-align: top; font-size: 11px; padding-top: 5px;'' colspan=''3''><strong style=''text-transform: uppercase;''>[BILLLABEL] &nbsp;</strong></td>
</tr>
</tbody>
<tbody>
<tr>
<td style=''text-align: left; vertical-align: top; font-size: 8px; padding: 0px;'' colspan=''2''>Original<br />Duplicate<br />Triplicate</td>
<td style=''text-align: center; font-size: 20px; height: 120px; padding: 5px 5px 2px 5px;'' colspan=''14''><strong>[Company]</strong><br /> <span style=''font-size: 12px;''>[CompanyAdd]</span></td>
</tr>
</tbody>
<tbody>
<tr style=''height: 20px; padding: 5px; border-bottom;none !important;border-bottom: 1px solid black;''>
<td style=''height: 20px; text-align: center; border-bottom;none !important;padding: 5px;'' colspan=''16''><span style=''font-size: 12px; vertical-align: top; text-align: top !important;''> <strong>Phone : [CompanyContact]</strong>&nbsp;&nbsp;&nbsp;&nbsp; <strong>Email : [CompanyEmail]</strong>&nbsp;&nbsp;&nbsp;&nbsp; <strong>Website : [CompanyWebsite]</strong> </span></td>
</tr>
</tbody>
<tbody>
<tr style=''height: 20px; padding: 5px;''>
<td style=''height: 20px; text-align: center; vertical-align: middle; font-size: 12px; border-bottom: 1px solid black; border-top: 1px solid black; padding: 5px;'' colspan=''16''><strong> GSTIN : 24CUCPM0422J1ZZ &nbsp;&nbsp;&nbsp;&nbsp; State Code : 24-GJ </strong></td>
</tr>
</tbody>
<tbody>
<tr style=''background-color: transparent; height: 20px; text-align: left;''>
<td style=''font-size: 12px; vertical-align: top; height: 20px; text-align: left; padding-top: 4px;'' colspan=''11'' rowspan=''3''>&nbsp;&nbsp;&nbsp;&nbsp; <strong>M/S.</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style=''color: #000000; font-size: 15px;''><strong>[ClientName]</strong></span> <br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style=''font-size: 12px;''>[CLIENTADD]</span></td>
<td style=''font-size: 12px; vertical-align: middle; text-align: left; height: 20px; border-left: 1px solid rgba(0, 0, 0, .3);'' colspan=''5''>&nbsp;&nbsp;<strong>Invoice No&nbsp; &nbsp; &nbsp;:</strong>&nbsp; &nbsp;[INVID]</td>
</tr>
<tr style=''height: 20px; text-align: left; background-color: transparent;''>
<td style=''font-size: 12px; vertical-align: middle; height: 20px; border-left: 1px solid rgba(0, 0, 0, .3);'' colspan=''5''>&nbsp; <strong>Invoice Date&nbsp; :</strong>&nbsp;&nbsp; [OrderDate]</td>
</tr>
<tr style=''height: 20px; text-align: left; background-color: transparent;''>
<td style=''font-size: 12px; vertical-align: middle; height: 20px; border-left: 1px solid rgba(0, 0, 0, .3);'' colspan=''5''>&nbsp; <strong>Challan No&nbsp; &nbsp; &nbsp;:</strong>&nbsp; &nbsp;[ChallanNo]</td>
</tr>
</tbody>
<tbody>
<tr style=''background-color: transparent; height: 20px; text-align: left;''>
<td style=''font-size: 12px; vertical-align: middle; height: 20px;'' colspan=''11''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Phone :</strong> &nbsp; &nbsp;&nbsp; [Mobile]</td>
<td style=''font-size: 12px; vertical-align: middle; height: 20px; border-left: 1px solid rgba(0, 0, 0, .3);'' colspan=''5''>&nbsp; <strong>Challan Date&nbsp; :</strong>&nbsp;&nbsp; [ChallanDate]</td>
</tr>
</tbody>
<tbody>
<tr style=''background-color: transparent; height: 20px; text-align: left;''>
<td style=''font-size: 12px; vertical-align: middle; height: 20px;'' colspan=''11''>&nbsp;&nbsp;&nbsp;&nbsp; <strong>GSTIN :</strong> &nbsp; &nbsp;&nbsp; [CLIENTTINNO] &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>State Code :</strong> &nbsp; &nbsp;&nbsp; 24-GJ</td>
<td style=''font-size: 12px; vertical-align: middle; height: 20px; border-left: 1px solid rgba(0, 0, 0, .3);'' colspan=''5''>&nbsp; <strong>PO No&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; :</strong>&nbsp; &nbsp;[PONO]</td>
</tr>
</tbody>
<tbody>
<tr style=''height: 15px; text-align: left; background-color: transparent;''>
<td class=''tg-m36b thsrno'' style=''font-size: 12px; text-align: center; height: 15px; width: 5px; padding: 1px; border: 1px solid black; border-left: 0px;'' colspan=''1'' rowspan=''2''><strong>Sr. No</strong></td>
<td class=''tg-m36b theqp'' style=''font-size: 12px; padding: 2px; height: 15px; text-align: left; border: 1px solid black; border-right: 0px; border-left: 0px; max-width: 120px; overflow-wrap: break-word;'' colspan=''3'' rowspan=''2''><strong>Perticular</strong></td>
<td class=''tg-m36b theqp'' style=''font-size: 12px; padding: 2px; height: 15px; text-align: center; border: 1px solid black; border-right: 0px;'' colspan=''1'' rowspan=''2''><strong>HSN Code</strong></td>
<td class=''tg-m36b theqp'' style=''font-size: 12px; padding: 2px; height: 15px; text-align: center; border: 1px solid black; border-right: 0px; overflow-wrap: break-word; max-width: 100px;'' colspan=''2'' rowspan=''2''><strong>Color | Size</strong></td>
<td class=''tg-m36b theqp'' style=''font-size: 12px; padding: 2px; height: 15px; text-align: center; border: 1px solid black; border-right: 0px;'' colspan=''1'' rowspan=''2''><strong>Frame No</strong></td>
<td class=''tg-ullm thsrno'' style=''font-size: 12px; padding: 2px; height: 15px; width: 10px; text-align: center; border: 1px solid black; border-right: 0px;'' colspan=''1'' rowspan=''2''><strong>Qty</strong></td>
<td class=''tg-ullm thsrno'' style=''font-size: 12px; padding: 2px; height: 15px; text-align: center; border: 1px solid black; border-right: 0px;'' colspan=''1'' rowspan=''2''><strong>Rate</strong></td>
<td class=''tg-ullm thsrno'' style=''font-size: 12px; padding: 2px; height: 15px; text-align: center; border: 1px solid black; border-right: 0px;'' colspan=''1'' rowspan=''2''><strong>Amt</strong></td>
<td class=''tg-ullm thamt'' style=''font-size: 12px; padding: 0px; height: 15px; text-align: center; border: 1px solid black; border-right: 0px; border-bottom: 0px;'' colspan=''2''><strong>Discount</strong></td>
<td class=''tg-ullm thsrno'' style=''font-size: 12px; padding: 2px; height: 15px; text-align: center; border: 1px solid black; border-right: 0px;'' colspan=''1'' rowspan=''2''><strong>Taxable Amt</strong></td>
<td class=''tg-m36b theqp'' style=''font-size: 12px; padding: 2px; height: 15px; text-align: center; border: 1px solid black; border-right: 0px;'' colspan=''1'' rowspan=''2''><strong>GST</strong></td>
<td class=''tg-ullm thamt'' style=''font-size: 12px; padding: 1px; height: 15px; text-align: center; border: 1px solid black; border-right: 0px; min-width: 50px;'' colspan=''1'' rowspan=''2''><strong>Amount</strong></td>
</tr>
<tr style=''height: 15px; text-align: left; background-color: transparent;''>
<td class=''tg-ullm thamt'' style=''font-size: 12px; padding: 1px; height: 15px; text-align: center; border: 1px solid black; border-right: 0px; border-top: 0px;'' colspan=''1''><strong>Rate</strong></td>
<td class=''tg-ullm thamt'' style=''font-size: 12px; padding: 1px; height: 15px; text-align: center; border: 1px solid black; border-right: 0px; border-top: 0px;'' colspan=''1''><strong>Amount</strong></td>
</tr>
</tbody>
<tbody>
<tr style=''text-align: left; height: 1px; background-color: transparent; display: [displayNone];''>
<td style=''font-size: 11px; height: 1px;'' colspan=''16''>[Description]</td>
</tr>
</tbody>
<tbody>
<tr style=''height: 20px; text-align: left; background-color: transparent; border-right: 0px; vertical-align: middle;''>
<td class=''tg-jtyd'' style=''font-size: 12px; padding: 0px; height: 25px; text-align: left; border: 1px solid black; border-left: 0px; border-right: 0px;'' colspan=''7''><strong>&nbsp; Our PAN NO : CUCPM0422J</strong></td>
<td class=''tg-jtyd'' style=''font-size: 12px; padding: 1px; height: 25px; text-align: right; border: 1px solid black; border-left: 0px;'' colspan=''1''><strong>Total &nbsp;</strong></td>
<td class=''tg-jtyd'' style=''font-size: 12px; padding: 1px; height: 25px; text-align: center; font-family: calibri; border: 1px solid black; border-left: 0px;'' colspan=''1''><strong>[TotalQty]</strong></td>
<td class=''tg-jtyd'' style=''font-size: 12px; padding: 5px; height: 25px; text-align: center; border: 1px solid black; border-left: 0px;'' colspan=''5''>&nbsp;</td>
<td class=''tg-jtyd'' style=''font-size: 12px; padding: 2px; height: 25px; border: 1px solid black; border-left: 0px; text-align: right; vertical-align: bottom; ;border-right: 0px;'' colspan=''2''><strong style=''font-size: 12px;''>[Total]</strong>&nbsp;</td>
</tr>
</tbody>
<tbody>
<tr style=''background-color: transparent; height: 20px; text-align: left; font-size: 11px;''>
<td style=''height: 20px; text-align: left; vertical-align: middle; font-size: 11px;'' colspan=''10''>&nbsp; <strong> Transporter : </strong> [Transport]</td>
<td style=''height: 20px; text-align: right; vertical-align: middle; font-size: 11px;'' colspan=''4''><strong>E.Charge &nbsp;</strong></td>
<td style=''height: 20px; text-align: right; vertical-align: middle; border-left: 1px solid black;'' colspan=''2''><strong style=''padding: 2px; font-size: 12px;''>[ExtraCharge]</strong>&nbsp;</td>
</tr>
</tbody>
<tbody>
<tr style=''background-color: transparent; height: 20px; text-align: left; font-size: 11px;''>
<td style=''height: 20px; text-align: left; vertical-align: middle; font-size: 11px;'' colspan=''10''>&nbsp; <strong> GC/LR No. : </strong> [GCLRNO] &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong> Eway Bill No : </strong> [GCLRNO]</td>
<td style=''height: 20px; text-align: right; vertical-align: middle; font-size: 11px;'' colspan=''4''><strong>Overall Discount &nbsp;</strong></td>
<td style=''height: 20px; text-align: right; vertical-align: middle; border-left: 1px solid black;'' colspan=''2''><strong style=''padding: 2px; font-size: 12px;''>[TotalDiscount]</strong>&nbsp;</td>
</tr>
</tbody>
<tbody>
<tr style=''background-color: transparent; height: 20px; text-align: left; font-size: 11px;''>
<td style=''height: 20px; text-align: left; vertical-align: middle; font-size: 11px;'' colspan=''10''>&nbsp; <strong> Reference : </strong> [Reference]</td>
<td style=''height: 20px; text-align: right; vertical-align: middle; font-size: 11px;'' colspan=''4''><strong>Roundable Amount &nbsp;</strong></td>
<td style=''height: 20px; text-align: right; vertical-align: middle; border-left: 1px solid black;'' colspan=''2''><strong style=''padding: 2px; font-size: 12px;''>[TotalRoundableAmount]</strong>&nbsp;</td>
</tr>
</tbody>
<tbody>
<tr style=''background-color: transparent; height: 20px; text-align: left; font-size: 11px;''>
<td style=''height: 20px; text-align: left; vertical-align: middle; font-size: 11px;'' colspan=''10''>&nbsp; <strong> Remarks : </strong> [REMARK]</td>
<td style=''height: 20px; text-align: right; vertical-align: middle; font-size: 11px;'' colspan=''4''><strong>Round Up &nbsp;</strong></td>
<td style=''height: 20px; text-align: right; vertical-align: middle; border-left: 1px solid black;'' colspan=''2''><strong style=''padding: 2px; font-size: 12px;''>[RoundFigure]</strong>&nbsp;</td>
</tr>
</tbody>
<tbody>
<tr style=''background-color: transparent; height: 22px; text-align: left; vertical-align: middle;''>
<td style=''height: 22px; text-align: left; vertical-align: middle; border-top: 1px dashed black; font-size: 10px;'' colspan=''10''>&nbsp; <strong>Rupees in Word: </strong><span style=''text-transform: uppercase;''>[TotalInWord]</span></td>
<td style=''height: 22px; text-align: right; vertical-align: middle; border-top: 1px dashed black; font-size: 11px;'' colspan=''4''><strong>Net Amount &nbsp;</strong></td>
<td style=''height: 22px; text-align: right; vertical-align: bottom; border-top: 1px dashed black; border-left: 1px solid black; padding: 2px;'' colspan=''2''><strong style=''font-size: 12px;''>[RoundTotal]</strong>&nbsp;</td>
</tr>
</tbody>
<tbody>
<tr style=''background-color: transparent; height: 15px; font-size: 12px;''>
<td style=''background-color: transparent; height: 15px; border-top: 1px dashed black; padding: 2px; font-size: 12px;'' colspan=''16'' align=''center'' valign=''bottom''><strong>GST Summary</strong></td>
</tr>
</tbody>
<tbody style=''padding: 2px;''>
<tr style=''background-color: transparent; height: 15px; font-size: 12px;''>
<td style=''border-right: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black; font-size: 10px;'' colspan=''2'' rowspan=''2'' align=''center'' valign=''middle''><strong>HSN/SAC&nbsp; &nbsp;</strong></td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black; font-size: 12px;'' colspan=''2'' rowspan=''2'' align=''center'' valign=''middle''><strong>Taxable Value</strong></td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; font-size: 12px;'' colspan=''3'' align=''center'' valign=''bottom''><strong>CGST</strong></td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; font-size: 12px;'' colspan=''3'' align=''center'' valign=''bottom''><strong>SGST</strong></td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; font-size: 12px;'' colspan=''3'' align=''center'' valign=''bottom''><strong>IGST</strong></td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; font-size: 12px;'' colspan=''3'' align=''center'' valign=''bottom''><strong>CESS</strong></td>
</tr>
<tr style=''background-color: transparent; height: 15px; font-size: 12px;''>
<td style=''border-right: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black; font-size: 12px;'' align=''center'' valign=''bottom''><strong>Rate</strong></td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black; font-size: 12px;'' colspan=''2'' align=''center'' valign=''bottom''><strong>Amount</strong></td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black; font-size: 12px;'' align=''center'' valign=''bottom''><strong>Rate</strong></td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black; font-size: 12px;'' colspan=''2'' align=''center'' valign=''bottom''><strong>Amount</strong></td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black; font-size: 12px;'' align=''center'' valign=''bottom''><strong>Rate</strong></td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black; font-size: 12px;'' colspan=''2'' align=''center'' valign=''bottom''><strong>Amount</strong></td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black; font-size: 12px;'' align=''center'' valign=''bottom''><strong>Rate</strong></td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black; font-size: 12px;'' colspan=''2'' align=''center'' valign=''bottom''><strong>Amount</strong></td>
</tr>
<tr style=''background-color: transparent; height: 1px; font-size: 11px;''>
<td style=''font-size: 11px; height: 1px;''>[gstSummary]</td>
</tr>
<tr style=''background-color: transparent; height: 15px; font-size: 12px;''>
<td style=''border-right: 1px solid rgba(0, 0, 0, .3);'' colspan=''2'' align=''center'' valign=''middle''>&nbsp;</td>
<td style=''border-right: 1px solid rgba(0, 0, 0, .3);'' colspan=''2'' align=''right'' valign=''bottom''>&nbsp;</td>
<td style=''border-right: 1px solid rgba(0, 0, 0, .3);'' align=''center'' valign=''bottom''>&nbsp;</td>
<td style=''border-right: 1px solid rgba(0, 0, 0, .3);'' colspan=''2'' align=''right'' valign=''bottom''>&nbsp;</td>
<td style=''border-right: 1px solid rgba(0, 0, 0, .3);'' align=''center'' valign=''bottom''>&nbsp;</td>
<td style=''border-right: 1px solid rgba(0, 0, 0, .3);'' colspan=''2'' align=''right'' valign=''bottom''>&nbsp;</td>
<td style=''border-right: 1px solid rgba(0, 0, 0, .3);'' align=''center'' valign=''bottom''>&nbsp;</td>
<td style=''border-right: 1px solid rgba(0, 0, 0, .3);'' colspan=''2'' align=''right'' valign=''bottom''>&nbsp;</td>
<td style=''border-right: 1px solid rgba(0, 0, 0, .3);'' align=''center'' valign=''bottom''>&nbsp;</td>
<td colspan=''2'' align=''right'' valign=''bottom''>&nbsp;</td>
</tr>
<tr style=''background-color: transparent; height: 15px; font-size: 12px;''>
<td style=''border-right: 1px solid black; border-top: 1px solid black; font-size: 12px;'' colspan=''2'' align=''center'' valign=''bottom''>&nbsp;</td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; font-size: 12px;'' colspan=''2'' align=''right'' valign=''bottom''><strong>[TotalTaxableAmt]</strong>&nbsp;</td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; font-size: 12px;'' align=''center'' valign=''bottom''><strong>[TotalCgst]</strong>&nbsp;</td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; font-size: 12px;'' colspan=''2'' align=''right'' valign=''bottom''><strong>[TotalCgstAmt]</strong>&nbsp;</td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; font-size: 12px;'' align=''center'' valign=''bottom''><strong>[TotalSgst]</strong>&nbsp;</td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; font-size: 12px;'' colspan=''2'' align=''right'' valign=''bottom''><strong>[TotalSgstAmt]</strong>&nbsp;</td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; font-size: 12px;'' align=''center'' valign=''bottom''><strong>[TotalIgst]</strong>&nbsp;</td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; font-size: 12px;'' colspan=''2'' align=''right'' valign=''bottom''><strong>[TotalIgstAmt]</strong>&nbsp;</td>
<td style=''border-right: 1px solid black; border-top: 1px solid black; font-size: 12px;'' align=''center'' valign=''bottom''>&nbsp;</td>
<td style=''border-top: 1px solid black; font-size: 12px;'' colspan=''2'' align=''right'' valign=''bottom''><strong>0</strong>&nbsp;</td>
</tr>
</tbody>
<tbody>
<tr style=''background-color: transparent; height: 113px; text-align: left; padding: 0 0 0 0; vertical-align: top;''>
<td style=''padding: 3px; height: 113px; text-align: left; font-size: 11px; font-family: calibri; border-top: 1px solid black;'' colspan=''9''>
<p style=''line-height: 0.5; vertical-align: top;''><strong>Terms &amp; Conditions: </strong></p>
&nbsp; * Service-Date:&nbsp;[serviceDate]<br />
<p style=''line-height: 0.5;''>&nbsp; * Ineterest at 24% p.a. will be charged for late payments.</p>
<p style=''line-height: 0.5;''>&nbsp; * We check and pack the goods carefully before dispatch.</p>
<p style=''line-height: 0.5;''>&nbsp; * Cheque Retunrn Charge 150 Rs. Compulsory.</p>
<p style=''line-height: 0.5;''>&nbsp; * All Disputes are subject to SURAT jurisdiction only.</p>
</td>
<td style=''padding: 1px; height: 113px; text-align: right; font-family: calibri; border-top: 1px solid black;'' colspan=''7''><strong style=''vertical-align: top; font-size: 14px !important; padding: 0px;''>FOR, [Company] &nbsp;</strong><br /> &nbsp;<br /> &nbsp;<br />&nbsp;<br /> <strong style=''text-align: right; vertical-align: bottom; font-size: 11px !important; padding: 0px;''>Proprietor / Authorised Signature &nbsp;&nbsp;</strong></td>
</tr>
</tbody>
</table>";

		$templateArray['Payment'] = "<div style=''background-image: url(''http://4.bp.blogspot.com/-J9g2UmC8cJk/UCyoyj24VMI/AAAAAAAAEDg/Q3oUk33685w/s1600/Indian+Flag+Wallpapers-03.jpg''); background-size: 100% 100%;''>



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

		$templateArray['Email_NewOrder'] = " <p>Dear [ClientName],</p>

									<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Welcome to [Company] Family.</p>

									<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Thank you for shopping with us. Please find your invoice attached with this Mail</p>

									<p>Thanks &amp; Regards</p>

									<p>The Team [Company].</p>";
		$templateArray['Email_DuePayment'] = "<p>Dear [ClientName],</p> 
									<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Welcome to [Company] Family.<
									/p> <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Thank you for shopping with 
									us.Your remaining amount =&nbsp;[RemainingPayment]</p> <p>Thanks &amp; Regards</p> <p>The 
									Team [Company].</p>";

		$templateArray['Email_BirthDay'] = "<p>Dear [ClientName] ,</p>
									<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;Wish u a many many happy returns of the day.Happy BirthDay.</p>";

		$templateArray['Email_AnniversaryDay'] = "<p>Dear [ClientName],</p>
									<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wish u a happy Anniversary.Happy Anniversary.</p>";

		$templateArray['Sms_NewOrder'] = "<p>Dear [ClientName],</p><
									p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Thank you for shopping with us.</p>";

		$templateArray['Sms_DuePayment'] = "<p>Dear [ClientName],</p>
									<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Your remaining payment =&nbsp;[RemainingPayment]</p>";

		$templateArray['Sms_BirthDay'] = "<p>Dear [ClientName],</p>
									<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Happy Birthday</p>";

		$templateArray['Sms_AnniversaryDay'] = "<p>Dear [ClientName],</p>
									<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Happy Anniversary</p>";

		$templateArray['Blank'] =  "<div style=''background-size: 100% 100%; height: 29cm; width: 21.7cm; padding-top: 12px;''>
									<table style=''border-collapse: collapse; border-spacing: 0px; margin-left: auto; margin-right: auto; height: 29cm; width: 21.3cm;'' cellspacing=''0''>
									<tbody>
									<tr style=''height: 6cm;''>
									<td style=''height: 6cm; text-align: center; vertical-align: top; font-size: 12px; padding-top: 8px;'' colspan=''6''><br />
									<p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [CompanySGST]</strong><br /><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [CompanyCGST]<br /></strong></p>
									</td>
									<td style=''height: 6cm; text-align: left;'' colspan=''6''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>[Company]</strong><br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=''font-family: Agency FB; font-size: 12px;''>[CompanyAdd]</span></td>
									<td style=''height: 6cm; font-family: Agency FB; text-align: left; font-size: 19px;'' colspan=''4''>&nbsp;<br /> &nbsp;<br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br /> [CreditCashMemo]&nbsp;<span style=''text-transform: uppercase;''>MEMO</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[RetailOrTax]<span style=''text-transform: uppercase;''>&nbsp;INVOICE</span></td>
									</tr>
									<tr style=''background-color: transparent; height: 0.8cm; text-align: left;''>
									<td style=''font-family: Agency FB; font-size: 15px; vertical-align: top; height: 0.75cm; text-align: left;'' colspan=''9'' rowspan=''2''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=''color: #000000; font-size: 18px;''><strong>[ClientName]</strong></span> <br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=''font-size: 16px;''>[CLIENTADD]</span></td>
									<td style=''font-family: Agency FB; font-size: 14px; vertical-align: middle; color: black; height: 0.75cm;'' colspan=''7''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=''color: #000000;''>[INVID]</span></td>
									</tr>
									<tr class=''trhw'' style=''height: 0.8cm; text-align: left;''>
									<td class=''tg-vi9z'' style=''font-family: Agency FB; font-size: 14px; vertical-align: middle; color: black; height: 0.8cm;'' colspan=''16''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[OrderDate]</td>
									</tr>
									<tr class=''trhw'' style=''background-color: transparent; height: 0.8cm; text-align: left;''>
									<td class=''tg-vi9z'' style=''font-family: Agency FB; font-size: 14px; vertical-align: middle; color: black; height: 0.8cm;'' colspan=''9''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[CLIENTTINNO] &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Mobile No:</strong>&nbsp;&nbsp;[Mobile]</td>
									<td class=''tg-vi9z'' style=''font-family: Agency FB; font-size: 14px; vertical-align: middle; color: black; height: 0.8cm; text-align: left;'' colspan=''7''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0.00</td>
									</tr>
									<tr class=''trhw'' style=''font-family: Agency FB; height: 1.2cm; text-align: left;''>
									<td class=''tg-m36b thsrno'' style=''font-size: 14px; padding: 5px; text-align: center; height: 1.2cm;''><span style=''color: #000000;''><strong>Sr.No</strong></span></td>
									<td class=''tg-m36b theqp'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: left;'' colspan=''3''><span style=''color: #000000;''><strong>Particulars</strong></span></td>
									<td class=''tg-m36b theqp'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: left;''><strong>HSN</strong></td>
									<td class=''tg-ullm thsrno'' style=''font-size: 14px; height: 1.2cm; text-align: center; padding: 5px 2px;'' colspan=''2''><span style=''color: #000000;''><strong>Color | Size</strong></span></td>
									<td class=''tg-ullm thsrno'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: left;''><span style=''color: #000000;''><strong>Frame No</strong></span></td>
									<td class=''tg-ullm thsrno'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>Qty</strong></span></td>
									<td class=''tg-ullm thsrno'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>Rate</strong></span></td>
									<td class=''tg-ullm thsrno'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>Amt</strong></span></td>
									<td class=''tg-ullm thamt'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>Dis Rate</strong></span></td>
									<td class=''tg-ullm thamt'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>Dis Amt</strong></span></td>
									<td class=''tg-ullm thamt'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>Taxable Amt</strong></span></td>
									<td class=''tg-ullm thamt'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>GST</strong></span></td>
									<td class=''tg-ullm thamt'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>Amount</strong></span></td>
									</tr>
									<tr class=''trhw'' style=''font-family: Agency FB; text-align: left; height: 0.1cm; background-color: transparent; display: [displayNone];''>
									<td class=''tg-m36b thsrno'' style=''font-size: 12px; color: #000000; height: 0.1cm;'' colspan=''16''>[Description]</td>
									</tr>
									<tr style=''height: 0.7cm; text-align: left;''>
									<td class=''tg-jtyd'' style=''font-family: Agency FB; font-size: 12px; padding: 5px; height: 0.7cm; text-align: left; border-top: 1px solid black; border-bottom: 1px solid black; text-decoration: uppercase;'' colspan=''6''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=''color: black;''><strong>[TotalInWord]</strong></span></td>
									<td class=''tg-jtyd'' style=''font-family: Agency FB; font-size: 18px; padding: 5px; height: 0.7cm; text-align: left; border-top: 1px solid black; border-bottom: 1px solid black;'' colspan=''4''><span style=''color: #000000;''><strong>&nbsp;&nbsp;&nbsp;[TotalQty]</strong></span></td>
									<td class=''tg-jtyd'' style=''text-align: right; font-size: 14px; padding: 5px; height: 0.7cm; border-top: 1px solid black; border-bottom: 1px solid black;'' colspan=''3''>&nbsp;</td>
									<td class=''tg-jtyd'' style=''font-family: Agency FB; text-align: right; font-size: 18px; padding: 5px; height: 0.7cm; border-top: 1px solid black; border-bottom: 1px solid black;''><strong>Total</strong></td>
									<td class=''tg-jtyd'' style=''text-align: right; font-family: Agency FB; font-size: 18px; padding: 5px; height: 0.7cm; border-top: 1px solid black; border-bottom: 1px solid black;''><span style=''color: #000000;''><strong>[TotalTax]</strong>&nbsp;</span></td>
									<td class=''tg-3gzm'' style=''font-family: Agency FB; font-size: 18px; padding: 5px; color: black; height: 0.7cm; text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;''><strong>&nbsp;[Total]</strong></td>
									</tr>
									<tr style=''background-color: transparent; height: 2.95cm; text-align: left;''>
									<td class=''tg-3gzm'' style=''text-align: center; vertical-align: bottom; height: 2.85cm;'' colspan=''16''>&nbsp;</td>
									</tr>
									<tr class=''trhw'' style=''background-color: transparent; height: 3cm; text-align: left;''>
									<td class=''tg-vi9z'' style=''height: 3cm; text-align: left; vertical-align: bottom;'' colspan=''6''>
									<p style=''visibility: hidden;''>&nbsp;</p>
									<p style=''padding-top: 10px;''><span style=''font-family: Agency FB; color: #000000; font-size: 40px;''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>[REMAINAMT]</strong></span></p>
									</td>
									<td class=''tg-vi9z'' style=''padding: 5px; height: 3cm; text-align: center; vertical-align: middle; visibility: hidden;'' colspan=''6''>
									<p>&nbsp;</p>
									<p>&nbsp;</p>
									</td>
									<td class=''tg-vi9z'' style=''height: 3cm; text-align: left; vertical-align: bottom;'' colspan=''4''>
									<p style=''padding: 0 0 5px 0; visibility: hidden;''>&nbsp;</p>
									<p><span style=''color: #000000;''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong style=''font-family: Agency FB; padding: 5px; font-size: 40px;''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[Total]</strong></span></p>
									</td>
									</tr>
									<tr style=''background-color: transparent; height: 2.1cm; text-align: left;''>
									<td style=''padding: 5px; height: 2.1cm; text-align: center; vertical-align: middle;'' colspan=''4''>&nbsp;</td>
									<td style=''padding: 5px; height: 2.1cm; text-align: center; vertical-align: middle;'' colspan=''2''>&nbsp;</td>
									<td style=''padding: 5px; height: 2.1cm; text-align: center; vertical-align: top;'' colspan=''5''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ExpireDate]</td>
									<td style=''padding: 5px; height: 2.1cm; text-align: center; vertical-align: middle;'' colspan=''2''>&nbsp;</td>
									<td style=''padding: 5px; height: 2.1cm; text-align: center; vertical-align: middle;'' colspan=''3''>&nbsp;</td>
									</tr>
									</tbody>
									</table>
									</div>";
		$templateArray['Quotation'] = "<div style=''background-size: 100% 100%; height: 29cm; width: 20.5cm;''>
										<table style=''border-collapse: collapse; border-spacing: 0px; margin-left: auto; margin-right: auto; height: 29cm; width: 21.3cm;'' border=''1'' cellspacing=''0''>
										<tbody>
										<tr style=''height: 5cm;''>
										<td style=''height: 5cm; text-align: center; vertical-align: top; font-size: 12px; padding-top: 8px;'' colspan=''2''>&nbsp;</td>
										<td style=''height: 5cm; text-align: left;'' colspan=''6''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>[Company]</strong><br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=''font-family: Agency FB; font-size: 12px;''>[CompanyAdd]</span></td>
										<td style=''height: 5cm; text-align: right; vertical-align: top; font-size: 16px; padding-top: 8px;'' colspan=''4''><br /> &nbsp;&nbsp;<strong style=''text-transform: uppercase;''><u>SALES ORDER</u></strong></td>
										</tr>
										<tr style=''background-color: transparent; height: 0.8cm; text-align: left;''>
										<td style=''font-family: Agency FB; font-size: 15px; vertical-align: top; height: 0.8cm; width: 13.5cm; text-align: left;'' colspan=''7'' rowspan=''2''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>M/S.</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=''color: #000000; font-size: 18px;''><strong>[ClientName]</strong></span> <br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=''font-size: 16px;''>[CLIENTADD]</span></td>
										<td style=''font-family: Agency FB; font-size: 14px; vertical-align: middle; color: black; text-align: left; height: 0.8cm; width: 7.6cm;'' colspan=''5''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Order No:</strong>&nbsp;&nbsp;[QuotationNo]</td>
										</tr>
										<tr class=''trhw'' style=''height: 0.8cm; text-align: left;''>
										<td class=''tg-vi9z'' style=''font-family: Agency FB; font-size: 14px; vertical-align: middle; color: black; height: 0.8cm; width: 7.6cm;'' colspan=''12''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Order Date:</strong>&nbsp; [OrderDate]</td>
										</tr>
										<tr class=''trhw'' style=''background-color: transparent; height: 0.8cm; text-align: left;''>
										<td class=''tg-vi9z'' style=''font-family: Agency FB; font-size: 14px; vertical-align: middle; color: black; height: 0.8cm; width: 13.5cm;'' colspan=''7''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Ph:</strong>&nbsp;&nbsp;[Mobile]</td>
										<td class=''tg-vi9z'' style=''font-family: Agency FB; font-size: 14px; vertical-align: middle; color: black; height: 0.8cm; width: 7.6cm;'' colspan=''5''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Refer:</strong>&nbsp;&nbsp;</td>
										</tr>
										<tr class=''trhw'' style=''background-color: transparent; height: 0.8cm; text-align: left;''>
										<td class=''tg-vi9z'' style=''font-family: Agency FB; font-size: 14px; vertical-align: middle; color: black; height: 0.8cm; width: 13.5cm;'' colspan=''7''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Tin:</strong>&nbsp;&nbsp;[CLIENTTINNO]</td>
										<td class=''tg-vi9z'' style=''font-family: Agency FB; font-size: 14px; vertical-align: middle; color: black; height: 0.8cm; width: 7.6cm;'' colspan=''5''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Credit Date:</strong>&nbsp; [CreditDate]</td>
										</tr>
										<tr class=''trhw'' style=''font-family: Agency FB; height: 1.2cm; text-align: left; background-color: transparent;''>
										<td class=''tg-m36b thsrno'' style=''font-size: 14px; padding: 5px; text-align: center; height: 1.2cm;''><span style=''color: #000000;''><strong>Sr.No</strong></span></td>
										<td class=''tg-m36b theqp'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: left;'' colspan=''3''><span style=''color: #000000;''><strong>Particulars</strong></span></td>
										<td class=''tg-ullm thsrno'' style=''font-size: 14px; height: 1.2cm; text-align: center; padding: 5px 2px 5px 2px;'' colspan=''2''><span style=''color: #000000;''><strong>Color | Size</strong></span></td>
										<td class=''tg-ullm thsrno'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: left;''><span style=''color: #000000;''><strong>Frame No</strong></span></td>
										<td class=''tg-ullm thsrno'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>Qty</strong></span></td>
										<td class=''tg-ullm thsrno'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: center;'' colspan=''2''><span style=''color: #000000;''><strong>Rate</strong></span></td>
										<!--td class=''tg-ullm thsrno'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>Discount</strong></span></td--> <!--td class=''tg-ullm thamt'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>CGST%</strong></span></td>
										<td class=''tg-ullm thamt'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>CGST</strong></span></td>
										<td class=''tg-ullm thamt'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>SGST%</strong></span></td-->
										<td class=''tg-ullm thamt'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>Unit</strong></span></td>
										<td class=''tg-ullm thamt'' style=''font-size: 14px; padding: 5px; height: 1.2cm; text-align: center;''><span style=''color: #000000;''><strong>Amount</strong></span></td>
										</tr>
										<tr class=''trhw'' style=''font-family: Agency FB; text-align: left; height: 0.1cm; background-color: transparent; display: [displayNone];''>
										<td class=''tg-m36b thsrno'' style=''font-size: 12px; color: #000000; height: 0.1cm;'' colspan=''12''>[Description]</td>
										</tr>
										<tr style=''height: 0.7cm; text-align: left; background-color: transparent;''>
										<td class=''tg-jtyd'' style=''font-family: Agency FB; font-size: 12px; padding: 5px; height: 0.7cm; text-align: left; border-top: 1px solid black; border-bottom: 1px solid black; text-decoration: uppercase;'' colspan=''4''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
										<td class=''tg-jtyd'' style=''font-family: Agency FB; font-size: 18px; padding: 5px; height: 0.7cm; text-align: left; border-top: 1px solid black; border-bottom: 1px solid black;'' colspan=''3''><span style=''color: #000000;''><strong>&nbsp;&nbsp;&nbsp;[TotalQty]</strong></span></td>
										<td class=''tg-jtyd'' style=''text-align: right; font-size: 14px; padding: 5px; height: 0.7cm; border-top: 1px solid black; border-bottom: 1px solid black;'' colspan=''2''>&nbsp;</td>
										<td class=''tg-jtyd'' style=''font-family: Agency FB; text-align: right; font-size: 18px; padding: 5px; height: 0.7cm; border-top: 1px solid black; border-bottom: 1px solid black;''><strong>&nbsp;</strong></td>
										<td class=''tg-jtyd'' style=''text-align: right; font-family: Agency FB; font-size: 18px; padding: 5px; height: 0.7cm; border-top: 1px solid black; border-bottom: 1px solid black;''><span style=''color: #000000;''><strong>&nbsp;</strong>&nbsp;</span></td>
										<td class=''tg-3gzm'' style=''font-family: Agency FB; font-size: 18px; padding: 5px; color: black; height: 0.7cm; text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;''><strong>&nbsp;</strong></td>
										</tr>
										<tr style=''background-color: transparent; height: 2.95cm; text-align: left;''>
										<td class=''tg-3gzm'' style=''text-align: center; vertical-align: bottom; height: 2.85cm;'' colspan=''12''>&nbsp;</td>
										</tr>
										<tr class=''trhw'' style=''background-color: transparent; height: 0.5cm; text-align: left; font-size: 12px;''>
										<td class=''tg-vi9z'' style=''height: 0.5cm; text-align: left; vertical-align: middle;'' colspan=''6''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=''color: black;''>24221502234 28-03-2008</span><br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=''color: black;''>24221502234 28-03-2008</span></td>
										<td class=''tg-vi9z'' style=''height: 0.5cm; text-align: right; vertical-align: bottom;'' colspan=''2''>&nbsp;</td>
										<td class=''tg-vi9z'' style=''height: 0.5cm; text-align: right; vertical-align: bottom;'' colspan=''4''>&nbsp;</td>
										</tr>
										<tr class=''trhw'' style=''background-color: transparent; height: 0.5cm; text-align: left;''>
										<td class=''tg-vi9z'' style=''height: 0.5cm; text-align: left; vertical-align: middle;'' colspan=''6''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=''color: black;''><strong>[TotalInWord]</strong></span></td>
										<td class=''tg-vi9z'' style=''padding: 5px; height: 0.5cm; text-align: right; vertical-align: bottom;'' colspan=''2''>Total</td>
										<td class=''tg-vi9z'' style=''height: 0.5cm; text-align: right; vertical-align: bottom;'' colspan=''4''><span style=''color: #000000;''><strong style=''font-family: Agency FB; padding: 5px; font-size: 20px;''>&nbsp;&nbsp;&nbsp;[Total]</strong></span></td>
										</tr>
										<tr style=''background-color: transparent; height: 2.2cm; text-align: left;''>
										<td style=''padding: 5px; height: 2.2cm; text-align: left; vertical-align: top; line-height: 0.5; font-size: 12px;'' colspan=''7''>
										<p>Note: 1) Ineterest will be charged 15% per annum if the bill is not paid on presentation or on due date</p>
										<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2) Good sold will not be taken back.</p>
										<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 3) Goods wil be despatched on customer''s risk.</p>
										</td>
										<td style=''padding: 5px; height: 2.2cm; text-align: left; font-size: 16px;'' colspan=''5''>
										<p style=''vertical-align: top;''>&nbsp;&nbsp;&nbsp;&nbsp;<strong>FOR, [Company]</strong></p>
										<p>&nbsp;</p>
										<p style=''text-align: right; vertical-align: bottom;''>Authorised Signatory</p>
										</td>
										</tr>
										</tbody>
										</table>
										</div>";
		return $templateArray;

	}

}
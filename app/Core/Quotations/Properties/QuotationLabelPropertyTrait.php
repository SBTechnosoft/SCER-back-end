<?php
namespace ERP\Core\Quotations\Properties;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait QuotationLabelPropertyTrait
{
	/**
     * @var quotationLabel
     */
    private $quotationLabel;
	/**
	 * @param String $quotationLabel
	 */
	public function setQuotationLabel($quotationLabel)
	{
		$this->quotationLabel = $quotationLabel;
	}
	/**
	 * @return quotationLabel
	 */
	public function getQuotationLabel()
	{
		return $this->quotationLabel;
	}
}
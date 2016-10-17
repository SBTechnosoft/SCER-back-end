<?php
namespace ERP\Core\Invoices\Properties;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait InvoiceTypePropertyTrait
{
	/**
     * @var invoiceType
     */
    private $invoiceType;
	/**
	 * @param String $invoiceType
	 */
	public function setInvoiceType($invoiceType)
	{
		$this->invoiceType = $invoiceType;
	}
	/**
	 * @return invoiceType
	 */
	public function getInvoiceType()
	{
		return $this->invoiceType;
	}
}
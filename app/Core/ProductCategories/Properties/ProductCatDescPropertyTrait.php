<?php
namespace ERP\Core\ProductCategories\Properties;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait ProductCatDescPropertyTrait
{
	/**
     * @var productCatDesc
     */
    private $productCatDesc;
	/**
	 * @param int $productCatDesc
	 */
	public function setProductCatDesc($productCatDesc)
	{
		$this->productCatDesc = $productCatDesc;
	}
	/**
	 * @return productCatDesc
	 */
	public function getProductCatDesc()
	{
		return $this->productCatDesc;
	}
}
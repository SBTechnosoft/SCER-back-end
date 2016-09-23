<?php
namespace ERP\Core\ProductCategories\Properties;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait ProductCatIdPropertyTrait
{
	/**
     * @var productParentCatId
     */
    private $productCatId;
	/**
	 * @param int $productParentCatId
	 */
	public function setProductCatId($productCatId)
	{
		$this->productCatId = $productCatId;
	}
	/**
	 * @return productParentCatId
	 */
	public function getProductCatId()
	{
		return $this->productCatId;
	}
}
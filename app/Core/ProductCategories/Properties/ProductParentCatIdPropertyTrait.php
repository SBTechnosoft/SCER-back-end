<?php
namespace ERP\Core\ProductCategories\Properties;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait ProductParentCatIdPropertyTrait
{
	/**
     * @var productParentCatId
     */
    private $productParentCatId;
	/**
	 * @param int $productParentCatId
	 */
	public function setProductParentCatId($productParentCatId)
	{
		$this->productParentCatId = $productParentCatId;
	}
	/**
	 * @return productParentCatId
	 */
	public function getProductParentCatId()
	{
		return $this->productParentCatId;
	}
}
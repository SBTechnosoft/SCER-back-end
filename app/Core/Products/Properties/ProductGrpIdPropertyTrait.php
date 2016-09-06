<?php
namespace ERP\Core\Products\Properties;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait ProductGrpIdPropertyTrait
{
	/**
     * @var ProductGrp
     */
    private $ProductGrpId;
	/**
	 * @param int $ProductGrp
	 */
	public function setProductGrpId($ProductGrp)
	{
		$this->ProductGrp = $ProductGrp;
	}
	/**
	 * @return ProductGrp
	 */
	public function getProductGrpId()
	{
		return $this->ProductGrp;
	}
}
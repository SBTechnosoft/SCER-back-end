<?php
namespace ERP\Core\Quotations\Properties;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait StartAtPropertyTrait
{
	/**
     * @var startAt
     */
    private $startAt;
	/**
	 * @param int $startAt
	 */
	public function setStartAt($startAt)
	{
		$this->startAt = $startAt;
	}
	/**
	 * @return startAt
	 */
	public function getStartAt()
	{
		return $this->startAt;
	}
}
<?php
namespace ERP\Core\Sample\Properties;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait BranchPropertyTrait
{
    /**
     * @var age
     */
    private $age;
	/**
	 * @param int $age
	 */
	public function setAge($age)
	{
		$this->age = $age;
	}
	/**
	 * @return age
	 */
	public function getAge()
	{
		return $this->age;
	}
}
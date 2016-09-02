<?php
namespace ERP\Core\Sample\Properties;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait BranchPropertyTrait
{
    /**
     * @var age
     * @var imageName
     */
    private $age;
    private $imageName;
	
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
	/**
	 * @param int $age
	 */
	public function setImageName($imageName)
	{
		$this->imageName = $imageName;
	}
	/**
	 * @return age
	 */
	public function getImageName()
	{
		return $this->imageName;
	}
}
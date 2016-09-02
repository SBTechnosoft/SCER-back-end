<?php
namespace ERP\Core\Location\Entities;

use ERP\Core\Location\Properties\StatePropertyTrait;
use ERP\Core\Shared\Properties\IdPropertyTrait;
use ERP\Core\Shared\Properties\TitlePropertyTrait;

/**
 *
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class County
{
    use IdPropertyTrait;
	use TitlePropertyTrait;
    use StatePropertyTrait;

	/**
	 * @var Zip[]
	 */
	private $zips;

	public function __construct()
	{
		$this->zips = new ArrayCollection();
	}

	/**
	 * @return ArrayCollection|Zip[]
	 */
	public function getZips()
	{
		return $this->zips;
	}

	/**
	 * @param Zip $zip
	 */
	public function addZip(Zip $zip)
	{
		$this->zips->add($zip);
	}
}
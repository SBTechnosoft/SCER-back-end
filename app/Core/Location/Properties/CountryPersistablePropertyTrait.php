<?php
namespace ERP\Core\Location\Properties;

/**
 * @author Igor Vorobiov <igor.vorobioff@gmail.com>
 */
trait CountryPersistablePropertyTrait
{
	/**
	 * @var string
	 */
	private $county;

	/**
	 * @param string $county
	 */
	public function setCounty($county)
	{
		$this->county = $county;
	}

	/**
	 * @return string
	 */
	public function getCounty()
	{
		return $this->county;
	}
}
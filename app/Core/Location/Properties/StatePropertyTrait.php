<?php
namespace ValuePad\Core\Location\Properties;

use ValuePad\Core\Location\Entities\State;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait StatePropertyTrait
{
    /**
     * @var State
     */
    private $state;

	/**
	 * @param State $state
	 */
	public function setState(State $state)
	{
		$this->state = $state;
	}

	/**
	 * @return State
	 */
	public function getState()
	{
		return $this->state;
	}
}
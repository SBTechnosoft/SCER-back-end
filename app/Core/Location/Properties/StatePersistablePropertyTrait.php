<?php
namespace ValuePad\Core\Location\Properties;

/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait StatePersistablePropertyTrait
{

    /**
     *
     * @var string
     */
    private $state;

    /**
     *
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }
}
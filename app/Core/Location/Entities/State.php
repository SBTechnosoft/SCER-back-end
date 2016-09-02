<?php
namespace ValuePad\Core\Location\Entities;

use ValuePad\Core\Shared\Properties\NamePropertyTrait;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class State
{
	use NamePropertyTrait;

    /**
     * @var string
     */
    private $code;

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}
<?php
namespace ValuePad\Api\Company\V2_0\Transformers;

use ValuePad\Api\Support\BaseTransformer;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class MemberTransformer extends BaseTransformer
{
    /**
     * @param object $item
     * @return array
     */
    public function transform($item)
    {
        return $this->extract($item);
    }
}
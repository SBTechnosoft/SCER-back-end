<?php
namespace ValuePad\Api\Company\V2_0\Transformers;

use ValuePad\Api\Support\BaseTransformer;
use ValuePad\Core\Company\Entities\Owner;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class OwnerTransformer extends BaseTransformer
{

    /**
     * @param object|Owner $item
     * @return array
     */
    public function transform($item)
    {
        return $this->extract($item);
    }
}
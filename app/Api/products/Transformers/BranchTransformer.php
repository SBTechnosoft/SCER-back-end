<?php
namespace ValuePad\Api\Company\V2_0\Transformers;

use ValuePad\Api\Support\BaseTransformer;
use ValuePad\Core\Company\Entities\Branch;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class BranchTransformer extends BaseTransformer
{
    /**
     * @param Branch $branch
     * @return array
     */
    public function transform($branch)
    {
        return $this->extract($branch);
    }
}
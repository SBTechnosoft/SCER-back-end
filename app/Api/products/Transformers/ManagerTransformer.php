<?php
namespace ValuePad\Api\Company\V2_0\Transformers;

use ValuePad\Api\Support\BaseTransformer;
use ValuePad\Core\Company\Entities\Manager;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ManagerTransformer extends BaseTransformer
{

    /**
     * @param Manager $manager
     * @return array
     */
    public function transform($manager)
    {
        return $this->extract($manager);
    }
}
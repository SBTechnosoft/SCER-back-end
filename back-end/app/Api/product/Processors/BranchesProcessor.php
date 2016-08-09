<?php
namespace ValuePad\Api\Company\V2_0\Processors;

use ValuePad\Core\Company\Persistables\BranchPersistable;
use ValuePad\Api\Support\BaseProcessor;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class BranchesProcessor extends BaseProcessor
{

    /**
     * @return array
     */
    protected function configuration()
    {
        return [
            'name' => 'string'
        ];
    }

    /**
     * @return BranchPersistable
     */
    public function createPersistable()
    {
        return $this->populate(new BranchPersistable());
    }
}
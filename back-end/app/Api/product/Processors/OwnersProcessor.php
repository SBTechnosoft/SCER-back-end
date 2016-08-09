<?php
namespace ValuePad\Api\Company\V2_0\Processors;

use ValuePad\Api\Location\V2_0\Processors\LocationConfigurationProviderTrait;
use ValuePad\Api\User\V2_0\Processors\UserProcessor;
use ValuePad\Core\Company\Persistables\OwnerPersistable;

/**
 *
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class OwnersProcessor extends UserProcessor
{
    use LocationConfigurationProviderTrait;

    /**
     * @return array
     */
    protected function configuration()
    {
        return array_merge(parent::configuration(), [
            'phone' => 'string',
            'fax' => 'string'
        ], $this->getLocationConfiguration());
    }

    /**
     * @return OwnerPersistable
     */
    public function createPersistable()
    {
        return $this->populate(new OwnerPersistable());
    }
}
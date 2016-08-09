<?php
namespace ValuePad\Api\Company\V2_0\Processors;

use ValuePad\Api\Location\V2_0\Processors\LocationConfigurationProviderTrait;
use ValuePad\Api\User\V2_0\Processors\UserProcessor;
use ValuePad\Core\Company\Persistables\ManagerPersistable;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ManagersProcessor extends UserProcessor
{
    use LocationConfigurationProviderTrait;

    protected function configuration()
    {
        return array_merge(parent::configuration(), [
            'phone' => 'string',
            'fax' => 'string'
        ], $this->getLocationConfiguration());
    }

    /**
     * @return ManagerPersistable
     */
    public function createPersistable()
    {
        return $this->populate(new ManagerPersistable());
    }
}
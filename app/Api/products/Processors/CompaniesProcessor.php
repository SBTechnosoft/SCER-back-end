<?php
namespace ValuePad\Api\Company\V2_0\Processors;

use ValuePad\Api\Location\V2_0\Processors\LocationConfigurationProviderTrait;
use ValuePad\Api\Support\BaseProcessor;
use ValuePad\Core\Company\Persistables\CompanyPersistable;

/**
 *
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class CompaniesProcessor extends BaseProcessor
{
    use LocationConfigurationProviderTrait;

    /**
     *
     * @return array
     */
    protected function configuration()
    {
        return array_merge([
            'name' => 'string'
        ], $this->getLocationConfiguration());
    }

    /**
     *
     * @return CompanyPersistable
     */
    public function createPersistable()
    {
        return $this->populate(new CompanyPersistable());
    }
}
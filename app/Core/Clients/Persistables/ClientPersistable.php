<?php
namespace ERP\Core\Clients\Persistables;

use ERP\Core\Shared\Properties\NamePropertyTrait;
use ERP\Core\Clients\Properties\ClientIdPropertyTrait;
use ERP\Core\Clients\Properties\ClientNamePropertyTrait;
use ERP\Core\Clients\Properties\CompanyNamePropertyTrait;
use ERP\Core\Clients\Properties\ContactNoPropertyTrait;
use ERP\Core\Clients\Properties\WorkNoPropertyTrait;
use ERP\Core\Clients\Properties\EmailIdPropertyTrait;
use ERP\Core\Properties\Address1PropertyTrait;
use ERP\Core\Properties\Address2PropertyTrait;
use ERP\Core\Shared\Properties\IsDisplayPropertyTrait;
use ERP\Core\States\Properties\StateAbbPropertyTrait;
use ERP\Core\Shared\Properties\KeyPropertyTrait;
use ERP\Core\Cities\Properties\CityIdPropertyTrait;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ClientPersistable
{
    use NamePropertyTrait;
    use ClientNamePropertyTrait;
    use ClientIdPropertyTrait;
    use Address1PropertyTrait;
	use Address2PropertyTrait;
    use IsDisplayPropertyTrait;
    use StateAbbPropertyTrait;
    use KeyPropertyTrait;
	use CityIdPropertyTrait;
	use CompanyNamePropertyTrait;
	use ContactNoPropertyTrait;
	use WorkNoPropertyTrait;
	use EmailIdPropertyTrait;
}
<?php
namespace ERP\Core\Accounting\Ledgers\Persistables;

use ERP\Core\Shared\Properties\NamePropertyTrait;
use ERP\Core\Shared\Properties\KeyPropertyTrait;
use ERP\Core\Accounting\Ledgers\Properties\LedgerIdPropertyTrait;
use ERP\Core\Accounting\Ledgers\Properties\LedgerNamePropertyTrait;
use ERP\Core\Accounting\Ledgers\Properties\AliasPropertyTrait;
use ERP\Core\Accounting\Ledgers\Properties\InventoryAffectedPropertyTrait;
use ERP\Core\Properties\Address1PropertyTrait;
use ERP\Core\Properties\Address2PropertyTrait;
use ERP\Core\States\Properties\StateAbbPropertyTrait;
use ERP\Core\Shared\Properties\IdPropertyTrait;
use ERP\Core\Companies\Properties\PanPropertyTrait;
use ERP\Core\Companies\Properties\TinPropertyTrait;
use ERP\Core\Accounting\Ledgers\Properties\GstPropertyTrait;
use ERP\Core\Cities\Properties\CityIdPropertyTrait;
use ERP\Core\Accounting\Ledgers\Properties\LedgerGrpIdPropertyTrait;
use ERP\Core\Companies\Properties\CompanyIdPropertyTrait;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class LedgerPersistable
{
    use NamePropertyTrait;
    use LedgerNamePropertyTrait;
    use LedgerIdPropertyTrait;
    use InventoryAffectedPropertyTrait;
    use AliasPropertyTrait;
    use Address1PropertyTrait;
	use Address2PropertyTrait;
    use StateAbbPropertyTrait;
    use IdPropertyTrait;
	use KeyPropertyTrait;
	use CityIdPropertyTrait;
	use PanPropertyTrait;
	use TinPropertyTrait;
	use LedgerGrpIdPropertyTrait;
	use CompanyIdPropertyTrait;
	use GstPropertyTrait;
}
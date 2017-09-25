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
use ERP\Core\Accounting\Ledgers\Properties\CgstPropertyTrait;
use ERP\Core\Accounting\Ledgers\Properties\SgstPropertyTrait;
use ERP\Core\Cities\Properties\CityIdPropertyTrait;
use ERP\Core\Accounting\Ledgers\Properties\LedgerGrpIdPropertyTrait;
use ERP\Core\Companies\Properties\CompanyIdPropertyTrait;
use ERP\Core\Accounting\Ledgers\Properties\ContactNoPropertyTrait;
use ERP\Core\Accounting\Ledgers\Properties\EmailIdPropertyTrait;
use ERP\Core\Accounting\Ledgers\Properties\BalanceFlagPropertyTrait;
use ERP\Core\Accounting\Ledgers\Properties\AmountPropertyTrait;
use ERP\Core\Accounting\Ledgers\Properties\AmountTypePropertyTrait;
use ERP\Core\Accounting\Journals\Properties\FromDatePropertyTrait;
use ERP\Core\Accounting\Journals\Properties\ToDatePropertyTrait;
use ERP\Core\Accounting\Ledgers\Properties\InvoiceNumberPropertyTrait;
use ERP\Core\Accounting\Ledgers\Properties\IsDealerPropertyTrait;
use ERP\Core\Clients\Properties\ClientIdPropertyTrait;
use ERP\Core\Clients\Properties\ClientNamePropertyTrait;
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
	use CgstPropertyTrait;
	use SgstPropertyTrait;
	use ContactNoPropertyTrait;
	use EmailIdPropertyTrait;
	use BalanceFlagPropertyTrait;
	use AmountPropertyTrait;
	use AmountTypePropertyTrait;
	use FromDatePropertyTrait;
	use ToDatePropertyTrait;
	use InvoiceNumberPropertyTrait;
	use IsDealerPropertyTrait;
	use ClientNamePropertyTrait;
    use ClientIdPropertyTrait;
}
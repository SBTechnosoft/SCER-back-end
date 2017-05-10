<?php
namespace ERP\Core\Accounting\Bills\Persistables;

use ERP\Core\Accounting\Bills\Properties\ProductArrayPropertyTrait;
use ERP\Core\Accounting\Bills\Properties\PaymentModePropertyTrait;
use ERP\Core\Accounting\Bills\Properties\BankNamePropertyTrait;
use ERP\Core\Accounting\Bills\Properties\InvoiceNumberPropertyTrait;
use ERP\Core\Accounting\Bills\Properties\JobCardNumberPropertyTrait;
use ERP\Core\Accounting\Bills\Properties\CheckNumberPropertyTrait;
use ERP\Core\Accounting\Bills\Properties\TotalPropertyTrait;
use ERP\Core\Accounting\Bills\Properties\TaxPropertyTrait;
use ERP\Core\Accounting\Bills\Properties\GrandTotalPropertyTrait;
use ERP\Core\Accounting\Bills\Properties\AdvancePropertyTrait;
use ERP\Core\Accounting\Bills\Properties\BalancePropertyTrait;
use ERP\Core\Accounting\Bills\Properties\RemarkPropertyTrait;
use ERP\Core\Accounting\Bills\Properties\EntryDatePropertyTrait;
use ERP\Core\Accounting\Bills\Properties\ClientIdPropertyTrait;
use ERP\Core\Companies\Properties\CompanyIdPropertyTrait;
use ERP\Core\Accounting\Bills\Properties\SalesTypePropertyTrait;
use ERP\Core\Accounting\Bills\Properties\FromDatePropertyTrait;
use ERP\Core\Accounting\Bills\Properties\ToDatePropertyTrait;
use ERP\Core\Accounting\Bills\Properties\JfIdPropertyTrait;
use ERP\Core\Accounting\Bills\Properties\BillArrayPropertyTrait;
use ERP\Core\Accounting\Bills\Properties\KeyArrayPropertyTrait;
use ERP\Core\Accounting\Bills\Properties\NameArrayPropertyTrait;
use ERP\Core\Accounting\Bills\Properties\SaleIdArrayPropertyTrait;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BillPersistable
{
	use ProductArrayPropertyTrait;
    use PaymentModePropertyTrait;
    use BankNamePropertyTrait;
    use InvoiceNumberPropertyTrait;
    use CheckNumberPropertyTrait;
    use TotalPropertyTrait;
	use TaxPropertyTrait;
    use GrandTotalPropertyTrait;
    use AdvancePropertyTrait;
	use BalancePropertyTrait;
	use RemarkPropertyTrait;
	use EntryDatePropertyTrait;
	use CompanyIdPropertyTrait;
	use ClientIdPropertyTrait;
	use SalesTypePropertyTrait;
	use FromDatePropertyTrait;
	use ToDatePropertyTrait;
	use JfIdPropertyTrait;
	use BillArrayPropertyTrait;
	use KeyArrayPropertyTrait;
	use NameArrayPropertyTrait;
	use SaleIdArrayPropertyTrait;
	use JobCardNumberPropertyTrait;
}
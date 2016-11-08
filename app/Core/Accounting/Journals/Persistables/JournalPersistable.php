<?php
namespace ERP\Core\Accounting\Journals\Persistables;

use ERP\Core\Shared\Properties\NamePropertyTrait;
use ERP\Core\Shared\Properties\KeyPropertyTrait;
use ERP\Core\Accounting\Journals\Properties\JournalIdPropertyTrait;
use ERP\Core\Accounting\Journals\Properties\AmountPropertyTrait;
use ERP\Core\Accounting\Journals\Properties\AmountTypePropertyTrait;
use ERP\Core\Accounting\Journals\Properties\EntryDatePropertyTrait;
use ERP\Core\Accounting\Ledgers\Properties\LedgerIdPropertyTrait;
use ERP\Core\Shared\Properties\IdPropertyTrait;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class JournalPersistable
{
	use NamePropertyTrait;
	use JournalIdPropertyTrait
    use AmountPropertyTrait;
    use AmountTypePropertyTrait;
    use LedgerIdPropertyTrait;
    use EntryDatePropertyTrait;
	use IdPropertyTrait;
	use KeyPropertyTrait;
}
<?php
namespace ERP\Core\Invoices\Persistables;

use ERP\Core\Shared\Properties\NamePropertyTrait;
use ERP\Core\Invoices\Properties\InvoiceIdPropertyTrait;
use ERP\Core\Invoices\Properties\InvoiceLabelPropertyTrait;
use ERP\Core\Invoices\Properties\InvoiceTypePropertyTrait;
use ERP\Core\Invoices\Properties\StartAtPropertyTrait;
use ERP\Core\Invoices\Properties\EndAtPropertyTrait;
use ERP\Core\Companies\Properties\CompanyIdPropertyTrait;
use ERP\Core\Shared\Properties\KeyPropertyTrait;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class InvoicePersistable
{
    use NamePropertyTrait;
    use InvoiceIdPropertyTrait;
    use InvoiceLabelPropertyTrait;
    use InvoiceTypePropertyTrait;
    use StartAtPropertyTrait;
    use EndAtPropertyTrait;
    use CompanyIdPropertyTrait;
	use KeyPropertyTrait;
	
}
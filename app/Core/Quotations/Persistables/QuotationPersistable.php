<?php
namespace ERP\Core\Quotations\Persistables;

use ERP\Core\Shared\Properties\NamePropertyTrait;
use ERP\Core\Quotations\Properties\QuotationIdPropertyTrait;
use ERP\Core\Quotations\Properties\QuotationLabelPropertyTrait;
use ERP\Core\Quotations\Properties\QuotationTypePropertyTrait;
use ERP\Core\Quotations\Properties\StartAtPropertyTrait;
use ERP\Core\Quotations\Properties\EndAtPropertyTrait;
use ERP\Core\Companies\Properties\CompanyIdPropertyTrait;
use ERP\Core\Shared\Properties\KeyPropertyTrait;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class QuotationPersistable
{
    use NamePropertyTrait;
    use QuotationIdPropertyTrait;
    use QuotationLabelPropertyTrait;
    use QuotationTypePropertyTrait;
    use StartAtPropertyTrait;
    use EndAtPropertyTrait;
    use CompanyIdPropertyTrait;
	use KeyPropertyTrait;
	
}
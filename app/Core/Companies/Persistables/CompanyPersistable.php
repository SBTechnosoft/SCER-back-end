<?php
namespace ERP\Core\Companies\Persistables;

use ERP\Core\Shared\Properties\NamePropertyTrait;
use ERP\Core\Companies\Properties\CompanyIdPropertyTrait;
use ERP\Core\Companies\Properties\CompanyDispNamePropertyTrait;
use ERP\Core\Companies\Properties\Address1PropertyTrait;
use ERP\Core\Companies\Properties\PincodePropertyTrait;
use ERP\Core\Companies\Properties\Address2PropertyTrait;
use ERP\Core\Companies\Properties\PanPropertyTrait;
use ERP\Core\Companies\Properties\TinPropertyTrait;
use ERP\Core\Companies\Properties\VatNoPropertyTrait;
use ERP\Core\Companies\Properties\ServiceTaxNoPropertyTrait;
use ERP\Core\Companies\Properties\BasicCurrencySymbolPropertyTrait;
use ERP\Core\Companies\Properties\FormalNamePropertyTrait;
use ERP\Core\Companies\Properties\NoOfDecimalPointsPropertyTrait;
use ERP\Core\Companies\Properties\CurrencySymbolPropertyTrait;
use ERP\Core\Companies\Properties\DocumentNamePropertyTrait;
use ERP\Core\Companies\Properties\DocumentUrlPropertyTrait;
use ERP\Core\Companies\Properties\DocumentSizePropertyTrait;
use ERP\Core\Companies\Properties\DocumentFormatPropertyTrait;
use ERP\Core\Shared\Properties\IsDisplayPropertyTrait;
use ERP\Core\Companies\Properties\IsDefaultPropertyTrait;
use ERP\Core\States\Properties\StateAbbPropertyTrait;
use ERP\Core\Shared\Properties\IdPropertyTrait;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CompanyPersistable
{
    use NamePropertyTrait;
    use CompanyIdPropertyTrait;
    use CompanyDispNamePropertyTrait;
    use Address1PropertyTrait;
    use PincodePropertyTrait;
    use Address2PropertyTrait;
    use PanPropertyTrait;
    use TinPropertyTrait;
    use VatNoPropertyTrait;
    use ServiceTaxNoPropertyTrait;
    use BasicCurrencySymbolPropertyTrait;
    use FormalNamePropertyTrait;
    use NoOfDecimalPointsPropertyTrait;
    use CurrencySymbolPropertyTrait;
    use DocumentNamePropertyTrait;
    use DocumentUrlPropertyTrait;
    use DocumentSizePropertyTrait;
    use DocumentFormatPropertyTrait;
    use IsDisplayPropertyTrait;
    use IsDefaultPropertyTrait;
    use StateAbbPropertyTrait;
    use IdPropertyTrait;
}
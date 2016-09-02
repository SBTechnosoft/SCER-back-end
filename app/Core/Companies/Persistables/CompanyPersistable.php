<?php
namespace ERP\Core\Companies\Persistables;

use ERP\Core\Shared\Properties\NamePropertyTrait;
use ERP\Core\Shared\Properties\IdPropertyTrait;
use ERP\Core\Companies\Properties\CompanyPropertyTrait;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CompanyPersistable
{
    use NamePropertyTrait;
    use CompanyPropertyTrait;
    use IdPropertyTrait;
}
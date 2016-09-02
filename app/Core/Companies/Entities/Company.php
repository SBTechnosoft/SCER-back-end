<?php
namespace ERP\Core\Companies\Entities;

use ERP\Core\Location\Properties\Address1PropertyTrait;
use ERP\Core\Location\Properties\Address2PropertyTrait;
use ERP\Core\Location\Properties\CityPropertyTrait;
use ERP\Core\Location\Properties\StatePropertyTrait;
use ERP\Core\Location\Properties\ZipPropertyTrait;
use ERP\Core\Shared\Properties\IdPropertyTrait;
use ERP\Core\Shared\Properties\NamePropertyTrait;

/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class Company
{
    use IdPropertyTrait;
    use Address1PropertyTrait;
    use Address2PropertyTrait;
    use CityPropertyTrait;
    use ZipPropertyTrait;
    use StatePropertyTrait;
    use NamePropertyTrait;
}
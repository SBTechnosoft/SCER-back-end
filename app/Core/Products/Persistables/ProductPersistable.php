<?php
namespace ERP\Core\Products\Persistables;

use ERP\Core\Shared\Properties\NamePropertyTrait;
use ERP\Core\Products\Properties\ProductIdPropertyTrait;
use ERP\Core\Shared\Properties\IsDisplayPropertyTrait;
use ERP\Core\Shared\Properties\IdPropertyTrait;
use ERP\Core\Products\Properties\MeasureUnitPropertyTrait;
use ERP\Core\Companies\Properties\CompanyIdPropertyTrait;
use ERP\Core\Products\Properties\ProductGrpIdPropertyTrait;
use ERP\Core\Branches\Properties\BranchIdPropertyTrait;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class productPersistable
{
    use NamePropertyTrait;
	use IsDisplayPropertyTrait;
	use CompanyIdPropertyTrait;
	use ProductIdPropertyTrait;
	use IdPropertyTrait;
	use ProductGrpIdPropertyTrait;
	use BranchIdPropertyTrait;
	use MeasureUnitPropertyTrait;
}
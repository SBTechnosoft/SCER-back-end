<?php
namespace ERP\Core\Products\Persistables;

use ERP\Core\Shared\Properties\NamePropertyTrait;
use ERP\Core\Products\Properties\ProductIdPropertyTrait;
use ERP\Core\Products\Properties\ProductNamePropertyTrait;
use ERP\Core\Shared\Properties\IsDisplayPropertyTrait;
use ERP\Core\Shared\Properties\IdPropertyTrait;
use ERP\Core\Products\Properties\MeasureUnitPropertyTrait;
use ERP\Core\Companies\Properties\CompanyIdPropertyTrait;
use ERP\Core\Products\Properties\ProductGrpIdPropertyTrait;
use ERP\Core\Branches\Properties\BranchIdPropertyTrait;
use ERP\Core\Shared\Properties\KeyPropertyTrait;
use ERP\Core\ProductCategories\Properties\ProductCatIdPropertyTrait;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class productPersistable
{
    use NamePropertyTrait;
	use IsDisplayPropertyTrait;
	use CompanyIdPropertyTrait;
	use ProductIdPropertyTrait;
	use ProductNamePropertyTrait;
	use IdPropertyTrait;
	use ProductGrpIdPropertyTrait;
	use BranchIdPropertyTrait;
	use MeasureUnitPropertyTrait;
	use KeyPropertyTrait;
	use ProductCatIdPropertyTrait;
}
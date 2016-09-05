<?php
namespace ERP\Core\ProductCategories\Persistables;

use ERP\Core\Shared\Properties\NamePropertyTrait;
use ERP\Core\Shared\Properties\IsDisplayPropertyTrait;
use ERP\Core\Shared\Properties\IdPropertyTrait;
use ERP\Core\ProductCategories\Properties\ProductCatDescPropertyTrait;
use ERP\Core\ProductCategories\Properties\ProductParentCatIdPropertyTrait;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductCategoryPersistable
{
    use NamePropertyTrait;
    use IsDisplayPropertyTrait;
    use IdPropertyTrait;
	use ProductCatDescPropertyTrait;
	use ProductParentCatIdPropertyTrait;
}
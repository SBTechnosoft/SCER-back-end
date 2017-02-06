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
use ERP\Core\Products\Properties\TransactionDatePropertyTrait;	
use ERP\Core\Products\Properties\DiscountPropertyTrait;
use ERP\Core\Products\Properties\DiscountTypePropertyTrait;
use ERP\Core\Products\Properties\PricePropertyTrait;
use ERP\Core\Products\Properties\QtyPropertyTrait;
use ERP\Core\Products\Properties\TransactionTypePropertyTrait;
use ERP\Core\Products\Properties\InvoiceNumberPropertyTrait;
use ERP\Core\Products\Properties\BillNumberPropertyTrait;
use ERP\Core\Products\Properties\TaxPropertyTrait;
use ERP\Core\Products\Properties\JfIdPropertyTrait;
use ERP\Core\Products\Properties\PurchasePricePropertyTrait;
use ERP\Core\Products\Properties\WholeSaleMarginPropertyTrait;
use ERP\Core\Products\Properties\SemiWholeSaleMarginPropertyTrait;
use ERP\Core\Products\Properties\VatPropertyTrait;
use ERP\Core\Products\Properties\MrpPropertyTrait;
use ERP\Core\Products\Properties\MarginPropertyTrait;
use ERP\Core\Products\Properties\DiscountValuePropertyTrait;
use ERP\Core\Products\Properties\FromDatePropertyTrait;
use ERP\Core\Products\Properties\ToDatePropertyTrait;
use ERP\Core\Products\Properties\AdditionalTaxPropertyTrait;
use ERP\Core\Products\Properties\ProductDescriptionPropertyTrait;
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
	use TransactionDatePropertyTrait;
	use DiscountPropertyTrait;
	use DiscountTypePropertyTrait;
	use PricePropertyTrait;
	use QtyPropertyTrait;
	use TransactionTypePropertyTrait;
	use InvoiceNumberPropertyTrait;
	use BillNumberPropertyTrait;
	use TaxPropertyTrait;
	use JfIdPropertyTrait;
	use PurchasePricePropertyTrait;
	use WholeSaleMarginPropertyTrait;
	use SemiWholeSaleMarginPropertyTrait;
	use VatPropertyTrait;
	use MrpPropertyTrait;
	use MarginPropertyTrait;
	use DiscountValuePropertyTrait;
	use FromDatePropertyTrait;
	use ToDatePropertyTrait;
	use AdditionalTaxPropertyTrait;
	use ProductDescriptionPropertyTrait;
}
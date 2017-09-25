<?php
namespace ERP\Core\Settings\Persistables;

use ERP\Core\Shared\Properties\NamePropertyTrait;
use ERP\Core\Shared\Properties\KeyPropertyTrait;
use ERP\Core\Settings\Properties\BarcodeWidthPropertyTrait;
use ERP\Core\Settings\Properties\BarcodeHeightPropertyTrait;
use ERP\Core\Settings\Properties\ChequenoStatusTrait;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class SettingPersistable
{
    use NamePropertyTrait;
	use KeyPropertyTrait;
    use BarcodeWidthPropertyTrait;
    use BarcodeHeightPropertyTrait;
    use ChequenoStatusTrait;
}
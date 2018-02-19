<?php
namespace ERP\Core\Settings\Persistables;

use ERP\Core\Shared\Properties\NamePropertyTrait;
use ERP\Core\Shared\Properties\KeyPropertyTrait;
use ERP\Core\Settings\Properties\BarcodeWidthPropertyTrait;
use ERP\Core\Settings\Properties\BarcodeHeightPropertyTrait;
use ERP\Core\Settings\Properties\ChequenoStatusTrait;
use ERP\Core\Settings\Properties\ServiceDateNoOfDaysTrait;
use ERP\Core\Settings\Properties\PaymentdateNoOfDaysTrait;
use ERP\Core\Settings\Properties\PaymentdateStatusTrait;

use ERP\Core\Settings\Properties\BirthreminderTypeTrait;
use ERP\Core\Settings\Properties\BirthreminderTimeTrait;
use ERP\Core\Settings\Properties\BirthreminderNotifyByTrait;
use ERP\Core\Settings\Properties\BirthreminderStatusTrait;

use ERP\Core\Settings\Properties\AnnireminderTypeTrait;
use ERP\Core\Settings\Properties\AnnireminderTimeTrait;
use ERP\Core\Settings\Properties\AnnireminderNotifyByTrait;
use ERP\Core\Settings\Properties\AnnireminderStatusTrait;
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
    use ServiceDateNoOfDaysTrait;
    use PaymentdateNoOfDaysTrait;
    use PaymentdateStatusTrait;

    use BirthreminderTypeTrait;
    use BirthreminderTimeTrait;
    use BirthreminderNotifyByTrait;
    use BirthreminderStatusTrait;

    use AnnireminderTypeTrait;
    use AnnireminderTimeTrait;
    use AnnireminderNotifyByTrait;
    use AnnireminderStatusTrait;
}
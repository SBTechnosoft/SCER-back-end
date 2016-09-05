<?php
namespace ERP\Core\States\Persistables;

use ERP\Core\Shared\Properties\NamePropertyTrait;
use ERP\Core\Shared\Properties\IsDisplayPropertyTrait;
use ERP\Core\States\Properties\StateAbbPropertyTrait;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class StatePersistable
{
    use NamePropertyTrait;
    use IsDisplayPropertyTrait;
    use StateAbbPropertyTrait;
}
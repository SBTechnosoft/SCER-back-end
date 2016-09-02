<?php
namespace ERP\Core\Sample\Persistables;

use ERP\Core\Shared\Properties\NamePropertyTrait;
use ERP\Core\Shared\Properties\IdPropertyTrait;
use ERP\Core\Sample\Properties\BranchPropertyTrait;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BranchPersistable
{
    use NamePropertyTrait;
    use BranchPropertyTrait;
    use IdPropertyTrait;
}
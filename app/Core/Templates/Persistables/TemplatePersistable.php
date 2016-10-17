<?php
namespace ERP\Core\Templates\Persistables;

use ERP\Core\Shared\Properties\NamePropertyTrait;
use ERP\Core\Templates\Properties\TemplateIdPropertyTrait;
use ERP\Core\Templates\Properties\TemplateNamePropertyTrait;
use ERP\Core\Templates\Properties\TemplateTypePropertyTrait;
use ERP\Core\Templates\Properties\TemplateBodyPropertyTrait;
use ERP\Core\Shared\Properties\KeyPropertyTrait;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class TemplatePersistable
{
    use NamePropertyTrait;
    use TemplateNamePropertyTrait;
    use TemplateIdPropertyTrait;
    use TemplateTypePropertyTrait;
    use TemplateBodyPropertyTrait;
    use KeyPropertyTrait;
}
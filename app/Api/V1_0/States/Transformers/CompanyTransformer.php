<?php
namespace ERP\Api\V1_0\Companies\Transformers;

use ERP\Api\V1_0\Support\BaseTransformer;
use ERP\Core\Company\Entities\Company;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CompanyTransformer extends BaseTransformer
{
    /**
     * @param Company $company
     * @return array
     */
    public function transform($company)
    {
		echo "hello11";
        // return $this->extract($company);
    }
}
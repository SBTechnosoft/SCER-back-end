<?php
namespace ERP\Api\V1_0\Sample\Transformers;

use ERP\Api\V1_0\Support\BaseTransformer;
use ValuePad\Core\Sample\Entities\Branch;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BranchTransformer extends BaseTransformer
{
    /**
     * @param Branch $branch
     * @return array
     */
    public function transform($branch,$decodedJson)
    {
		echo "hi";
		print_r($decodedJson[0]['created_at']);
        // return $this->extract($sample);
    }
}
<?php
namespace ValuePad\Api\Company\V2_0\Controllers;

use Illuminate\Http\Response;
use ValuePad\Api\Company\V2_0\Transformers\MemberTransformer;
use ValuePad\Api\Support\BaseController;
use ValuePad\Core\Company\Services\BranchService;
use ValuePad\Core\Company\Services\CompanyService;

/**
 *
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class MembersController extends BaseController
{

    /**
     * @var BranchService
     */
    private $branchService;

    /**
     * @param BranchService $branchService
     */
    public function initialize(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    /**
     * @param int $companyId
     * @param int $branchId
     * @return Response
     */
    public function index($companyId, $branchId)
    {
        return $this->resource->makeAll(
			$this->branchService->getAllMembers($branchId),
			$this->transformer(MemberTransformer::class)
		);
    }

	/**
	 * @param CompanyService $companyService
	 * @param int $companyId
	 * @param int $branchId
	 * @return bool
	 */
    public static function verifyAction(CompanyService $companyService, $companyId, $branchId)
    {
        if (! $companyService->exists($companyId)) {
            return false;
        }

        return $companyService->hasBranch($companyId, $branchId);
    }
}
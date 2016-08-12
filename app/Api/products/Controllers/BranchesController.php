<?php
namespace ValuePad\Api\Company\V2_0\Controllers;

use ValuePad\Api\Company\V2_0\Processors\BranchesProcessor;
use ValuePad\Api\Company\V2_0\Transformers\BranchTransformer;
use ValuePad\Core\Company\Services\BranchService;
use Illuminate\Http\Response;
use ValuePad\Api\Support\BaseController;
use ValuePad\Core\Company\Services\CompanyService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class BranchesController extends BaseController
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
     * @param BranchesProcessor $processor
     * @return Response
     */
    public function store($companyId, BranchesProcessor $processor)
    {
        return $this->resource->make(
			$this->branchService->create($companyId, $processor->createPersistable()),
			$this->transformer(BranchTransformer::class)
		);
    }

    /**
     * @param int $companyId
     * @param int $branchId
     * @param BranchesProcessor $processor
     * @return Response
     */
    public function update($companyId, $branchId, BranchesProcessor $processor)
    {
        $this->branchService->update(
			$branchId, $processor->createPersistable(),
			$processor->schedulePropertiesToClear()
		);

        return $this->resource->blank();
    }

    /**
     * @param int $companyId
     * @param int $branchId
     * @return Response
     */
    public function destroy($companyId, $branchId)
    {
        $this->branchService->delete($branchId);
        return $this->resource->blank();
    }

    /**
     * @param int $companyId
     * @param int $branchId
     * @return Response
     */
    public function show($companyId, $branchId)
    {
        return $this->resource->make(
			$this->branchService->get($branchId),
			$this->transformer(BranchTransformer::class)
		);
    }

    /**
     * @param int $companyId
     * @return Response
     */
    public function index($companyId)
    {
        return $this->resource->makeAll(
			$this->branchService->getAllInCompany($companyId),
			$this->transformer(BranchTransformer::class)
		);
    }

    /**
     * @param CompanyService $companyService
     * @param int $companyId
     * @param int $branchId
     * @return bool
     */
    public static function verifyAction(CompanyService $companyService, $companyId, $branchId = null)
    {
        if (! $companyService->exists($companyId)) {
            return false;
        }

        if ($branchId === null) {
            return true;
        }

        return $companyService->hasBranch($companyId, $branchId);
    }
}
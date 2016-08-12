<?php
namespace ValuePad\Api\Company\V2_0\Controllers;

use Illuminate\Http\Response;
use ValuePad\Api\Company\V2_0\Processors\ManagersProcessor;
use ValuePad\Api\Company\V2_0\Transformers\ManagerTransformer;
use ValuePad\Api\Support\BaseController;
use ValuePad\Core\Company\Services\BranchService;
use ValuePad\Core\Company\Services\CompanyService;
use ValuePad\Core\Company\Services\ManagerService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ManagersController extends BaseController
{

    /**
     * @var ManagerService
     */
    private $managerService;

    /**
     * @param ManagerService $managerService
     */
    public function initialize(ManagerService $managerService)
    {
        $this->managerService = $managerService;
    }

    /**
     * @param int $companyId
     * @param int $branchId
     * @param ManagersProcessor $processor
     * @return Response
     */
    public function store($companyId, $branchId, ManagersProcessor $processor)
    {
        return $this->resource->make(
			$this->managerService->create($branchId, $processor->createPersistable()),
			$this->transformer(ManagerTransformer::class)
		);
    }

    /**
     * @param int $companyId
     * @param int $branchId
     * @param int $managerId
     * @param ManagersProcessor $processor
     * @return Response
     */
    public function update($companyId, $branchId, $managerId, ManagersProcessor $processor)
    {
        $this->managerService->update(
			$managerId,
			$processor->createPersistable(),
			$processor->schedulePropertiesToClear()
		);

        return $this->resource->blank();
    }

    /**
     * @param int $companyId
     * @param int $branchId
     * @param int $managerId
     * @return Response
     */
    public function show($companyId, $branchId, $managerId)
    {
        return $this->resource->make(
			$this->managerService->get($managerId),
			$this->transformer(ManagerTransformer::class)
		);
    }

    /**
     * @param int $companyId
     * @param int $branchId
     * @param int $managerId
     * @return Response
     */
    public function destroy($companyId, $branchId, $managerId)
    {
        $this->managerService->delete($managerId);
        return $this->resource->blank();
    }

	/**
	 * @param CompanyService $companyService
	 * @param BranchService $branchService
	 * @param int $companyId
	 * @param int $branchId
	 * @param int $managerId
	 * @return bool
	 */
    public static function verifyAction(
		CompanyService $companyService,
		BranchService $branchService,
		$companyId,
		$branchId,
		$managerId = null
	)
    {
        if (! $companyService->exists($companyId)) {
            return false;
        }

        if (! $companyService->hasBranch($companyId, $branchId)) {
            return false;
        }

        if ($managerId) {
            return $branchService->hasMember($branchId, $managerId);
        }

        return true;
    }
}
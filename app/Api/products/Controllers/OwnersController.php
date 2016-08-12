<?php
namespace ValuePad\Api\Company\V2_0\Controllers;

use Illuminate\Http\Response;
use ValuePad\Api\Company\V2_0\Processors\OwnersProcessor;
use ValuePad\Api\Company\V2_0\Transformers\OwnerTransformer;
use ValuePad\Api\Support\BaseController;
use ValuePad\Core\Company\Services\CompanyService;
use ValuePad\Core\Company\Services\OwnerService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class OwnersController extends BaseController
{
    /**
     * @var OwnerService
     */
    private $ownerService;

    /**
     * @param OwnerService $ownerService
     */
    public function initialize(OwnerService $ownerService)
    {
        $this->ownerService = $ownerService;
    }

    /**
     * @param int $companyId
     * @param OwnersProcessor $processor
     * @return Response
     */
    public function store($companyId, OwnersProcessor $processor)
    {
        return $this->resource->make(
			$this->ownerService->create($companyId, $processor->createPersistable()),
			$this->transformer(OwnerTransformer::class)
		);
    }

    /**
	 * @param int $companyId
     * @param int $ownerId
     * @return Response
     */
    public function show($companyId, $ownerId)
    {
        return $this->resource->make(
			$this->ownerService->get($ownerId),
			$this->transformer(OwnerTransformer::class)
		);
    }

    /**
     * @param int $companyId
     * @param int $ownerId
     * @param OwnersProcessor $processor
     * @return Response
     */
    public function update($companyId, $ownerId, OwnersProcessor $processor)
    {
        $this->ownerService->update(
			$ownerId,
			$processor->createPersistable(),
			$processor->schedulePropertiesToClear()
		);
        return $this->resource->blank();
    }

	/**
	 * @param int $companyId
	 * @param int $ownerId
	 * @return Response
	 */
    public function destroy($companyId, $ownerId)
    {
        $this->ownerService->delete($ownerId);
        return $this->resource->blank();
    }

    /**
     * @param CompanyService $companyService
     * @param int $companyId
     * @param int $ownerId
     * @return bool
     */
    public static function verifyAction(CompanyService $companyService, $companyId, $ownerId = null)
    {
        if (! $companyService->exists($companyId)) {
            return false;
        }

        if ($ownerId === null) {
            return true;
        }

        return $companyService->hasOwner($companyId, $ownerId);
    }
}
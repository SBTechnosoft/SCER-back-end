<?php
namespace ValuePad\Api\Company\V2_0\Controllers;

use Illuminate\Http\Response;
use ValuePad\Api\Company\V2_0\Processors\CompaniesProcessor;
use ValuePad\Api\Company\V2_0\Transformers\CompanyTransformer;
use ValuePad\Api\Support\BaseController;
use ValuePad\Core\Company\Services\CompanyService;

/**
 *
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class CompaniesController extends BaseController
{
    /**
     * @var CompanyService
     */
    private $companyService;

    /**
     * @param CompanyService $companyService
     */
    public function initialize(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * @param CompaniesProcessor $processor
     * @return Response
     */
    public function store(CompaniesProcessor $processor)
    {
        return $this->resource->make(
			$this->companyService->create($processor->createPersistable()),
			$this->transformer(CompanyTransformer::class)
		);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return $this->resource->make($this->companyService->get($id), $this->transformer(CompanyTransformer::class));
    }

    /**
     * @param int $id
     * @param CompaniesProcessor $processor
     * @return Response
     */
    public function update($id, CompaniesProcessor $processor)
    {
        $this->companyService->update($id, $processor->createPersistable(), $processor->schedulePropertiesToClear());

        return $this->resource->blank();
    }

	/**
	 * @param $id
	 * @return Response
	 */
    public function destroy($id)
    {
        $this->companyService->delete($id);

        return $this->resource->blank();
    }

    /**
     * @param int $id
     * @param CompanyService $companyService
     * @return bool
     */
    public static function verifyAction($id, CompanyService $companyService)
    {
        return $companyService->exists($id);
    }
}
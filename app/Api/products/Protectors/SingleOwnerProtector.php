<?php
namespace ValuePad\Api\Company\V2_0\Protectors;

use Ascope\Libraries\Permissions\ProtectorInterface;
use Illuminate\Http\Request;
use ValuePad\Core\Company\Services\CompanyService;

/**
 * The protector checks if the company has no owners yet then allows anyone to perform an action.
 * However, if the company got an owner already then no one can perform the action but the owner.
 *
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class SingleOwnerProtector implements ProtectorInterface
{

    /**
     * @var CompanyService
     */
    private $companyService;

    /**
     * @var Request
     */
    private $request;

    /**
     * @param CompanyService $companyService
     * @param Request $request
     */
    public function __construct(CompanyService $companyService, Request $request)
    {
        $this->companyService = $companyService;
        $this->request = $request;
    }

    /**
     * @return bool
     */
    public function grants()
    {
        $id = array_values($this->request->route()->parameters())[0];

        return $this->companyService->hasAnyOwners($id) === false;
    }
}
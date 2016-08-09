<?php
namespace ValuePad\Api\Company\V2_0\Protectors;

use Illuminate\Http\Request;
use ValuePad\Api\Shared\Protectors\AuthProtector;
use ValuePad\Core\Company\Services\CompanyService;
use ValuePad\Core\Session\Services\SessionService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class CompanyOwnerProtector extends AuthProtector
{
    /**
     * @var CompanyService
     */
    private $companyService;

    public function __construct(SessionService $sessionService, Request $request, CompanyService $companyService)
    {
        parent::__construct($sessionService, $request);
        $this->companyService = $companyService;
    }

    public function grants()
    {
        $granted = parent::grants();

        if (! $granted) {
            return false;
        }

        $companyId = current($this->request->route()->parameters());
        $ownerId = $this->getSession()
            ->getUser()
            ->getId();

        return $this->companyService->hasOwner($companyId, $ownerId);
    }
}
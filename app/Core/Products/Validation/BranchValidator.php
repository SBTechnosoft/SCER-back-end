<?php
namespace ERP\Core\Sample\Validation;

// use Ascope\Libraries\Validation\AbstractThrowableValidator;
// use Ascope\Libraries\Validation\Binder;
// use Ascope\Libraries\Validation\Property;
// use Ascope\Libraries\Validation\Rules\Blank;
// use Ascope\Libraries\Validation\Rules\Obligate;
use ERP\Core\Sample\Validation\Rules\BranchNameTakenInCompany;
use ERP\Core\Sample\Services\BranchService;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BranchValidator
{
    /**
     * @var BranchService
     */
    private $branchService;

    /**
     * @var int
     */
    private $branchId;

    /**
     * @var string
     */
    private $currentName;

    /**
     * @param BranchService $branchService
     * @param int $branchId
     * @param string $currentName
     */
    public function __construct(BranchService $branchService, $branchId, $currentName = null)
    {
        $this->branchService = $branchService;
        $this->branchId = $branchId;
        $this->currentName = $currentName;
    }

    /**
     * @param Binder $binder
     * @return void
     */
    protected function define(Binder $binder)
    {
        $binder->bind('name', function (Property $property) {
            $property
				->addRule(new Obligate())
                ->addRule(new Blank())
                ->addRule(new BranchNameTakenInCompany($this->branchService, $this->branchId, $this->currentName));
        });
    }
}
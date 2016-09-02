<?php
namespace ValuePad\Debug\Support;

use Ascope\Libraries\Permissions\PermissionsIgnorantInterface;
use Illuminate\Routing\Controller;

/**
 * @author Igor Vorobiov <igor.vorobioff@gmail.com>
 */
class BaseController extends Controller implements PermissionsIgnorantInterface
{

}
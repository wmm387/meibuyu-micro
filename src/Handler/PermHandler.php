<?php
/**
 * Created by PhpStorm.
 * User: Zero
 * Date: 2020/4/8
 * Time: 16:12
 */

namespace Meibuyu\Micro\Handler;

use Hyperf\Di\Annotation\Inject;
use Meibuyu\Micro\Model\Auth;
use Meibuyu\Micro\Service\Interfaces\UserServiceInterface;

class PermHandler
{

    /**
     * @Inject()
     * @var UserServiceInterface
     */
    protected $userServer;

    public function check($perm)
    {
        return $this->userServer->checkPerm(Auth::id(), $perm);
    }
}
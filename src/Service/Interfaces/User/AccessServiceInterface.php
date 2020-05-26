<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 2020/5/26
 * Time: 15:17
 */

namespace Meibuyu\Micro\Service\Interfaces\User;

interface AccessServiceInterface
{
    public function getTeams($userId, $noBusiness = true);
}

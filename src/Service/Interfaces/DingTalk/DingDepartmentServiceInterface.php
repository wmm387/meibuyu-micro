<?php
/**
 * Created by PhpStorm.
 * User: 姜克保
 * Date: 2020/5/20
 * Time: 15:48
 */

namespace Meibuyu\Micro\Service\Interfaces\DingTalk;

interface DingDepartmentServiceInterface
{
    public function getDepartmentList($id = null, $isFetchChild = false, $lang = null): array;
}

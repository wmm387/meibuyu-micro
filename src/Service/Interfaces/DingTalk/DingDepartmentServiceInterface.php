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
    /**
     * 获取部门列表
     * @param null $id 部门id
     * @param bool $isFetchChild 是否获取子部门
     * @param null $lang
     * @return array
     */
    public function getDepartmentList($id = null, $isFetchChild = false, $lang = null): array;
}

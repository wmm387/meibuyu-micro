<?php
/**
 * Created by PhpStorm.
 * User: 姜克保
 * Date: 2020/5/20
 * Time: 15:48
 */

namespace Meibuyu\Micro\Service\Interfaces;

interface DingUserServiceInterface
{
    /**
     * 通过用户id获取单个用户信息
     * @param int $id
     * @return array
     */
    public function getById(int $id): array;

    /**
     * 通过部门id获取部门用户列表
     * @param int $departmentId
     * @param int $offset
     * @param int $size
     * @param string|null $order
     * @param string|null $lang
     * @return array
     */
    public function getDepartmentUsers(int $departmentId, int $offset, int $size, string $order = null, string $lang = null): array;

    /**
     * 通过Unionid获取用户id
     * @param int $unionid
     * @return int
     */
    public function getUseridByUnionid(int $unionid): int;
}

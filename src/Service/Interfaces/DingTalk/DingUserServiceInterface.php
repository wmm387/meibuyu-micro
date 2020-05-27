<?php
/**
 * Created by PhpStorm.
 * User: 姜克保
 * Date: 2020/5/20
 * Time: 15:48
 */

namespace Meibuyu\Micro\Service\Interfaces\DingTalk;

interface DingUserServiceInterface
{
    /** 通过用户id获取单个用户信息
     * @param string $ding_user_id 钉钉用户id
     * @return array 钉钉用户信息
     */
    public function getByDingUserId($ding_user_id): array;

    /**
     * 通过部门id获取部门分页用户列表
     * @param string $departmentId 部门id
     * @param int $offset
     * @param int $size
     * @param string|null $order
     * @param string|null $lang
     * @return array
     */
    public function getDepartmentUsers(string $departmentId, int $offset, int $size, string $order = null, string $lang = null): array;

    /**
     * 通过临时授权码获取用户信息
     * @param string $code 临时授权码
     * @return array
     */
    public function getDingUserByTempCode($code): array;

}

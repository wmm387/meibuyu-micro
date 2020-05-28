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

    /**
     * 获取鉴权的团队
     * @param $userId
     * @param bool $tree 是否返回树状结构
     * @param bool $noBusiness
     * @return array 已进行keyBy('id')处理,返回树状结构时,keyBy无效
     */
    public function getTeams($userId, $tree = false, $noBusiness = true);

    /**
     * 获取鉴权的团队带用户
     * @param $userId
     * @param bool $tree 是否返回树状结构
     * @param bool $noBusiness
     * @return array 已进行keyBy('id')处理,返回树状结构时,keyBy无效
     */
    public function getTeamsWithUsers($userId, $tree = false, $noBusiness = true);

    /**
     * 获取鉴权的部门
     * @param $userId
     * @param bool $tree 是否返回树状结构
     * @param bool $withUser 是否带用户数据
     * @return array 已进行keyBy('id')处理,返回树状结构时,keyBy无效
     */
    public function getDepartments($userId, $tree = false, $withUser = false);

}

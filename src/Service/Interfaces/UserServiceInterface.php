<?php
/**
 * Created by PhpStorm.
 * User: 王源
 * Date: 2020/1/9
 * Time: 15:07
 */

namespace Meibuyu\Micro\Service\Interfaces;

interface UserServiceInterface
{

    /**
     * 获取单个数据
     * @param $id
     * @return mixed
     */
    public function get($id);

    /**
     * 通过id列表获取用户数组
     * @param array $idList
     * @param array $columns
     * @return mixed
     */
    public function getByIdList(array $idList, array $columns = ['*'], array $relations = []);

    /**
     * 鉴权
     * @param int $userId
     * @param string $perm
     * @return bool
     */
    public function checkPerm(int $userId, string $perm): bool;

    /**
     * 获取用户拥有某个应用的所有权限
     * @param int $userId
     * @param string $appName 为空获取全部权限
     * @return array
     */
    public function getPerms(int $userId, string $appName = null): array;

}

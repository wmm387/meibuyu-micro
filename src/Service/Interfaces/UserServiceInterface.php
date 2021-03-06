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
     * 通过用户名称模糊获取用户
     * @param string $name
     * @param array $columns
     * @return array
     */
    public function getByName(string $name, array $columns = ['id', 'name']): array;

    /**
     * 获取单个数据
     * @param int $id
     * @param array $columns
     * @param array $relations 可传入['teams', 'departments', 'position'],分别是团队,部门和岗位
     * @return mixed
     */
    public function get(int $id, array $columns = ['*'], array $relations = []);

    /**
     * 获取全部数据
     * @param array $columns
     * @param array $relations 可传入['teams', 'departments', 'position'],分别是团队,部门和岗位
     * @return array
     */
    public function all(array $columns = ['*'], array $relations = []): array;

    /**
     * 通过id列表获取用户数组
     * @param array $idList
     * @param array $columns
     * @param array $relations 可传入['teams', 'departments', 'position'],分别是团队,部门和岗位
     * @return mixed
     */
    public function getByIdList(array $idList, array $columns = ['*'], array $relations = []);

    /**
     * 通过部门id列表获取用户数组(包括子部门用户)
     * @param array $deptIds
     * @param array $columns 用户字段,默认['id', 'name', 'department_id']
     * @return array
     */
    public function getListByDeptIds(array $deptIds, $columns = []);

    /**
     * 判断是否是超级管理员
     * @param int $userId
     * @return bool
     */
    public function isSuperAdmin(int $userId): bool;

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
     * @param string $appName 当前系统名称,为空获取全部权限
     * @return array
     */
    public function getPerms(int $userId, string $appName = null): array;

    /**
     * 获取带领导字符串数组的列表
     * [
     *     ['id' => -1, 'name' => '总经理'],
     *     ['id' => -2, 'name' => '直属领导'],
     *     ['id' => -3, 'name' => '部门领导'],
     *     [...]
     * ]
     * @return array
     */
    public function allWithLeader(): array;

    /**
     * 获取带有领导真实信息的用户列表
     * @param int $userId
     * @param array $idList 可包含[-1,-2,-3]
     * @param array $columns
     * @return array
     */
    public function getListWithLeader(int $userId, array $idList = [], array $columns = ['id', 'name']): array;

}

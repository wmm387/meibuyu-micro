<?php
/**
 * Created by PhpStorm.
 * User: 王源
 * Date: 2020/1/9
 * Time: 15:07
 */

namespace Meibuyu\Micro\Service\Interfaces;

interface BaseInfoServiceInterface
{

    /**
     * 通过单个id获取岗位信息
     * @param int $id 职位id
     * @param array $relations 职位的关联信息 支持["position_level","users","parent"
     * ,"children","perms"] 分别是 岗位职级，岗位用户，岗位父级，岗位子集，岗位对应的权限
     * @param array $columns 默认显示所有字段
     * @return array
     */
    public function getPositionById(int $id, array $relations = [], array $columns = ['*']): array;

    /**
     * 通过单个id获取岗位数组
     * @param int $id
     * @param array $columns
     * @param array $relations
     * @return array
     */
    public function getPositionListById(int $id, array $columns = ['*'], array $relations = []): array;


    /**
     * 通过id数组获取国家数组
     * @param array $idList
     * @param array $columns
     * @return array
     */
    public function getCountryListByIdList(array $idList, array $columns = ['*']): array;

    /**
     * 通过id数组获取团队数组
     * @param array $idList
     * @param array $columns
     * @return array
     */
    public function getTeamListByIdList(array $idList, array $columns = ['id', 'name']): array;

    /**
     * 通过id数组获取站点数组
     * @param array $idList
     * @param array $columns
     * @return array
     */
    public function getSiteListByIdList(array $idList, array $columns = ['id', 'name']): array;

    /**
     * 通过id数组获取货币数组
     * @param array $idList
     * @param array $columns
     * @return array
     */
    public function getCurrencyListByIdList(array $idList, array $columns = ['id', 'name']): array;

}

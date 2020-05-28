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
     * @return array|null
     */
    public function getPositionById(int $id, array $relations = [], array $columns = ['*']);

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

    /**
     * 根据id获取单个数据
     * @param int $id 货币id
     * @param array $columns 要显示的字段 默认全部 ['id', 'name', 'code', 'symbol']
     * @return array
     */
    public function getCurrencyById($id, array $columns = ['id', 'name', 'code', 'symbol']): array;

    /**
     * 根据id获取单个数据
     * @param int $id
     * @param array $columns 要显示的字段 默认全部
     * @return array
     */
    public function getCountryById($id, array $columns = ['*']): array;

    /**
     * 获取单个团队数据
     * @param int $id
     * @param array $relations 支持的关联关系 ['leader', 'sites', "users", "parent", "children"] 分别代表 负责人、团队下的站点、团队成员、父级团队，再级团队
     * @param array $columns 要显示的字段 默认['id', 'pid', "name", "leader_user_id", "leader_user_id", "department_id"]
     * @return array
     */
    public function getTeamById($id, array $relations = [], array $columns = ['id', 'pid', "name", "leader_user_id", "leader_user_id", "department_id"]): array;

    /**
     * 获取单个站点数据
     * @param int $id
     * @param array $relations $relations 支持的关联关系 ['team', 'country'] 分别代表 团队、国家、
     * @param array $columns 要显示的字段 默认['id', "name", "url", "country_id", "team_id"]
     * @return array
     */
    public function getSiteById($id, array $relations = [], array $columns = ['id', "name", "url", "country_id", "team_id"]): array;

    /**
     * 根据团队id获取对应的站点列表
     * @param int $teamId 团队id
     * @param array $relations $relations 支持的关联关系 ['team', 'country'] 分别代表 团队、国家、
     * @param array $columns 要显示的字段 默认['id', "name", "url", "country_id", "team_id"]
     * @return array
     */
    public function getSiteListByTeamId($teamId, array $relations = [], array $columns = ['id', "name", "url", "country_id", "team_id"]): array;

    /**
     * 返回所有货币数据
     * @param array $columns 显示的字段名称 默认 ['id', 'name', 'code', 'symbol']
     * @return array
     */
    public function currencies(array $columns = ['id', 'name', 'code', 'symbol']): array;

    /**
     * 获取所有国家数据
     * @param array $columns 要显示的字段
     * $columns = ['id', 'name', 'iso_code2', 'iso_code3'];
     * @return array 默认已keyBy('id')
     */
    public function countries(array $columns = ['id', 'name']): array;

    /**
     * 获取所有团队数据
     * @param array $relations 支持的关联关系 ['leader', 'sites', "users", "parent", "children"] 分别代表 负责人、团队下的站点、团队成员、父级团队，再级团队
     * @param array $columns 要显示的字段 默认['id', 'pid', "name", "leader_user_id", "leader_user_id", "department_id"]
     * @return array
     */
    public function teams(array $relations = [], array $columns = ['id', 'pid', "name", "leader_user_id", "leader_user_id", "department_id"]): array;

    /**
     * 获取所有站点的数据
     * @param array $relations 支持的关联关系 ['team', 'country'] 分别代表 团队、国家
     * @param array $columns 要显示的字段
     * $columns = ['id', "name", "url", "country_id", "team_id"];
     * @return array 默认已keyBy('id')
     */
    public function sites(array $relations = [], array $columns = ['id', "name"]): array;


}

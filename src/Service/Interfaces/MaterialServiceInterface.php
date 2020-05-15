<?php
/**
 * Created by PhpStorm.
 * User: 梁俊杰
 * Date: 2020/5/15
 * Time: 15:07
 */

namespace Meibuyu\Micro\Service\Interfaces;

interface MaterialServiceInterface
{

    /**
     * 获取单个数据
     * @param int $id 原料id
     * @param array $relations 关联关系，支持：["material_name","color"]
     * @return array
     */
    public function get($id, array $relations = []): array;

    /**
     * 通过id列表获取原料数组
     * @param array $idList 原料id的列表
     * @param array $relations 原料的关联关系，支持["material_name","color"]
     * @param array $columns 原料表的字段，默认显示全部
     * @return array
     */
    public function getByIdList(array $idList, array $relations = [], array $columns = ['*']): array;

    /**
     * 通过原料品名id列表获取原料数组
     * @param int $materialNameId 原料品名id
     * @param array $relations 原料的关联关系，支持["material_name","color"]
     * @param array $columns 原料表的字段，默认显示全部
     * @return array
     */
    public function getByMaterialNameId(array $materialNameId, array $relations = [], array $columns = ['*']): array;

    /**
     * 通过原料列表
     * @param int $page 第几页数据，默认：1
     * @param array $relations 原料的关联关系，支持["material_name","color"]
     * @param int $pageSize 每页条数默认：15，最大支持100
     * @param array $columns 原料表的字段，默认显示全部
     * @return array
     */
    public function list($page = 1, array $relations = [], $pageSize = 15, array $columns = ['*']): array;


    /**
     * 获取某个原料品名
     * @param int $materialNameId 原料品名编号
     * @param array $relations 原料的关联关系，支持["materials","material_name_category"]
     * @return array
     */
    public function getMaterialName($materialNameId, array $relations = []): array;

    /**
     * 通过id列表获取原料品名数组
     * @param array $idList 原料品名id的列表
     * @param array $relations 原料品名的关联关系，支持["materials","material_name_category"]
     * @param array $columns 原料品名表的字段，默认显示全部
     * @return array
     */
    public function getMaterialNameByIdList(array $idList, array $relations = [], array $columns = ['*']): array;

    /**
     * 通过原料类型id列表获取原料品名数组
     * @param int $materialNameCategoryId 原料类型id
     * @param array $relations 原料的关联关系，支持["material_name_category", "materials"]
     * @param array $columns 原料品名表的字段，默认显示全部
     * @return array
     */
    public function getMaterialNameByMaterialNameCategoryId(array $materialNameCategoryId, array $relations = [], array $columns = ['*']): array;

    /**
     * 通过原料品名列表
     * @param int $page 第几页数据，默认：1
     * @param array $relations原料品名的关联关系，支持 ["materials","material_name_category"]
     * @param int $pageSize 每页条数默认：15，最大支持100
     * @param array $columns 原料品名表的字段，默认显示全部
     * @return array
     */
    public function getMaterialNamelist($page = 1, array $relations = [], $pageSize = 15, array $columns = ['*']): array;


    /** 根据id获取原料类型
     * @param int $materialNameCategoryId 原料类型编号
     * @param array $relations原料类型的关联关系，支持 ["material_name"]
     * @return array
     */
    public function getMaterialNameCategoryById($materialNameCategoryId, array $relations = []): array;

    /**
     * 获取全部原料类型
     * @param array $relations原料类型的关联关系，支持 ["material_name"]
     * @return array
     */
    public function materialNameCategories(array $relations = []): array;
}

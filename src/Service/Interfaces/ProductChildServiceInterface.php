<?php
/**
 * Created by PhpStorm.
 * User: 王源
 * Date: 2020/1/9
 * Time: 15:07
 */

namespace Meibuyu\Micro\Service\Interfaces;

interface ProductChildServiceInterface
{

    /**
     * 获取单个数据
     * @param int $id 子SKU id
     * @param array $columns
     * @param array $relations 子SKU的关联关系
     * @return array
     */
    public function get($id, array $columns = ['*'], array $relations = []): array;

    /**
     * 通过id列表获取产品数组
     * @param array $idList 子SKUid的列表
     * @param array $columns 子SKU表的字段，默认显示全部
     * @param array $relations 子SKU的关联关系
     * @return array
     */
    public function getByIdList(array $idList, array $columns = ['*'], array $relations = []): array;

}

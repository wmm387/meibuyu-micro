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
     * @param array $columns 原料表的字段，默认显示全部
     * @return array|null
     */
    public function get($id, array $columns = ['*']);

    /**
     * 通过id列表获取原料数组
     * @param array $idList 原料id的列表
     * @param array $columns 原料表的字段，默认显示全部
     * @return array
     */
    public function getByIdList(array $idList, array $columns = ['*']): array;

    /**
     * 通过内部code列表获取原料列表
     * @param array $codeList
     * @param array $columns
     * @return array
     */
    public function getListByCodeList(array $codeList, array $columns = ['id']);

}

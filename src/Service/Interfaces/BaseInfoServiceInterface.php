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

}

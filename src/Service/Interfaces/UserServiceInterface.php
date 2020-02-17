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
     * 获取列表数据
     * @param array $params
     * $params = [
     *     'keyword' => '',
     *     'page' => 1, 当前第几页
     *     'page_size' => 10, 每页展示数,默认10
     * ]
     * @return mixed
     */
    public function search($params);

    /**
     * 获取单个数据
     * @param $id
     * @return mixed
     */
    public function get($id);

    /**
     * 更新数据
     * @param $id
     * @param array $params
     * @return mixed
     */
    public function update($id, $params);

}

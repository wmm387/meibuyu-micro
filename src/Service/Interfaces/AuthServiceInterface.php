<?php
/**
 * Created by PhpStorm.
 * User: 王源
 * Date: 2020/1/9
 * Time: 15:07
 */

namespace Meibuyu\Micro\Service\Interfaces;

interface AuthServiceInterface
{

    /**
     * 登录
     * @param array $params
     * @return mixed
     */
    public function login(array $params);

    /**
     * 登出
     * @return mixed
     */
    public function logout();

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

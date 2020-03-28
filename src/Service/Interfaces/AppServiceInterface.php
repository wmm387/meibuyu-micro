<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 2020/3/27
 * Time: 15:03
 */

namespace Meibuyu\Micro\Service\Interfaces;

interface AppServiceInterface
{

    /**
     * 获取当前用户可访问的应用数组
     * @param $user
     * @return mixed
     */
    public function getAccessApps($user);
}

<?php
/**
 * Created by PhpStorm.
 * User: 姜克保
 * Date: 2020/5/20
 * Time: 15:48
 */

namespace Meibuyu\Micro\Service\Interfaces\DingTalk;

interface DingAuthServiceInterface
{
    /**
     * 通过临时授权码获取用户信息
     * @param string $code 临时授权码
     * @return array
     */
    public function getDingUserByTempCode($code): array;
}

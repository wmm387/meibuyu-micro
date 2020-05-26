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
     * @param $code 临时授权码
     * @param $state 用于防止重放攻击，开发者可以根据此信息来判断redirect_uri只能执行一次来避免重放攻击
     * @return array
     */
    public function getDingUserByTempCode($code, $state): array;
}

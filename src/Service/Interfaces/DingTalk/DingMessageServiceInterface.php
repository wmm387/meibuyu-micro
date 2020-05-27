<?php
/**
 * Created by PhpStorm.
 * User: 姜克保
 * Date: 2020/5/20
 * Time: 15:48
 */

namespace Meibuyu\Micro\Service\Interfaces\DingTalk;

use phpDocumentor\Reflection\Types\Mixed_;

interface DingMessageServiceInterface
{

    /**
     * 发送普通消息
     *
     * @param string $sender 消息发送者 userId
     * @param string $cid 群会话或者个人会话的id，通过JSAPI接口唤起联系人界面选择会话获取会话cid；小程序参考获取会话信息，H5微应用参考获取会话信息
     * @param array  $message 消息内容，消息类型和样例可参考“消息类型与数据格式”文档。最长不超过2048个字节
     *
     * @return mixed
     */
    public function sendGeneralMessage($sender, $cid, $message);


    /**
     * 发送工作通知消息
     *
     * @param array $params
     *
     * @return mixed
     */
    public function sendCorporationMessage($params);

    /**
     * @param int $taskId
     *
     * @return mixed
     */
    public function corporationMessage($taskId);
}

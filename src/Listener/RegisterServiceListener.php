<?php
/**
 * Created by PhpStorm.
 * User: Zero
 * Date: 2020/5/6
 * Time: 8:54
 */

namespace Meibuyu\Micro\Listener;

use Hyperf\ServiceGovernance\Listener\RegisterServiceListener as BaseRegisterServiceListener;

class RegisterServiceListener extends BaseRegisterServiceListener
{

    protected function getServers(): array
    {
        $result = [];
        $servers = $this->config->get('server.servers', []);
        foreach ($servers as $server) {
            if (!isset($server['name'], $server['host'], $server['port'])) {
                continue;
            }
            if (!$server['name']) {
                throw new \InvalidArgumentException('Invalid server name');
            }
            /**
             * 若在docker中运行,会获取到docker环境中的ip
             * 这里对配置文件中local_ip判断,如果有,直接使用
             */
            $host = isset($server['local_ip']) ? $server['local_ip'] : $server['host'];
            if (in_array($host, ['0.0.0.0', 'localhost'])) {
                $host = $this->getInternalIp();
            }
            if (!filter_var($host, FILTER_VALIDATE_IP)) {
                throw new \InvalidArgumentException(sprintf('Invalid host %s', $host));
            }
            $port = $server['port'];
            if (!is_numeric($port) || ($port < 0 || $port > 65535)) {
                throw new \InvalidArgumentException(sprintf('Invalid port %s', $port));
            }
            $port = (int)$port;
            $result[$server['name']] = [$host, $port];
        }
        return $result;
    }

}
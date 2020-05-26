<?php
/**
 * Created by PhpStorm.
 * User: Zero
 * Date: 2020/4/15
 * Time: 9:19
 */

namespace Meibuyu\Micro\Message;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Guzzle\CoroutineHandler;
use Hyperf\Guzzle\HandlerStackFactory;

class MessageCenter
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var HandlerStackFactory
     */
    protected $stackFactory;

    public function __construct(ConfigInterface $config, HandlerStackFactory $stackFactory)
    {
        $this->config = $config;
        $this->stackFactory = $stackFactory;
        $this->initClient();
    }

    public function initClient()
    {
        $options = [
            'base_uri' => $this->config->get('message.center.domain') . '/api/',
            'handler' => HandlerStack::create(new CoroutineHandler()),
            'timeout' => 60,
        ];
        $proxy = $this->config->get('message.center.proxy');
        if ($proxy) {
            $options = array_merge($options, [
                'proxy' => $proxy,
                'swoole' => [
                    'http_proxy_port' => $this->config->get('message.center.proxy_port'),
                ]
            ]);
        }
        $this->client = make(Client::class, ['config' => $options]);
    }

    public function request($type, $uri, $data = [])
    {
        return $this->client->request($type, $uri, [
            'body' => json_encode($data),
            'headers' => ['content-type' => 'application/json']
        ]);
    }

    public function syncDingUser()
    {
        $response = $this->client->request('GET', 'synchronizationDingUser');
        return json_decode($response->getBody()->getContents(), true);
    }

    public function sendDing($userId, $message)
    {
        $response = $this->client->request('POST', 'sendDing', [
            'body' => json_encode(['userId' => $userId, 'message' => $message]),
            'headers' => ['content-type' => 'application/json']
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

}
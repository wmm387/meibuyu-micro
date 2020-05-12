<?php

/**
 * Created by PhpStorm.
 * User: zero
 * Date: 2020/2/11
 * Time: 11:49
 */

namespace Meibuyu\Micro;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'exceptions' => [
                'handler' => [
                    'http' => [
                        \Meibuyu\Micro\Exceptions\Handler\MicroExceptionHandler::class,
                        \Meibuyu\Micro\Exceptions\Handler\QueryExceptionHandler::class,
                    ],
                ],
            ],
            'dependencies' => [
                \Hyperf\ServiceGovernance\Listener\RegisterServiceListener::class => \Meibuyu\Micro\Listener\RegisterServiceListener::class,
            ],
            'commands' => [
                \Meibuyu\Micro\Command\RepositoryCommand::class,
                \Meibuyu\Micro\Command\ValidatorCommand::class,
                \Meibuyu\Micro\Command\MakeModelCommand::class,
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'message',
                    'description' => 'message',
                    'source' => __DIR__ . '/../publish/message.php',
                    'destination' => BASE_PATH . '/config/autoload/message.php',
                ],
            ],
        ];
    }
}

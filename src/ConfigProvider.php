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
                    ],
                ],
            ],
            'commands' => [
                \Meibuyu\Micro\Command\RepositoryCommand::class,
                \Meibuyu\Micro\Command\ValidatorCommand::class,
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
        ];
    }

}
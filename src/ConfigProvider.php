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
            'commands' => [
                \Meibuyu\Micro\Command\RepositoryCommand::class,
                \Meibuyu\Micro\Command\ValidatorCommand::class,
            ],
        ];
    }

}
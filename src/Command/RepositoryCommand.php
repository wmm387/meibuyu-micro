<?php

/**
 * Created by PhpStorm.
 * User: zero
 * Date: 2020/2/11
 * Time: 11:59
 */

namespace Meibuyu\Micro\Command;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Meibuyu\Micro\Generator\FileAlreadyExistsException;
use Meibuyu\Micro\Generator\RepositoryEloquentGenerator;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @Command()
 */
class RepositoryCommand extends HyperfCommand
{

    protected $name = 'gen:repository';

    /**
     * Handle the current command.
     */
    public function handle()
    {
        try {
            $name = $this->input->getArgument('name');
            $createValidator = $this->output->confirm('同时创建验证器?', true);
            (new RepositoryEloquentGenerator([
                'name' => $name,
                'validator' => $createValidator,
            ]))->run();
            $this->info("Repository created successfully.");
        } catch (FileAlreadyExistsException $e) {
            $this->error($e->getMessage() . ' already exists!');
        }
    }

    public function getArguments()
    {
        return [['name', InputArgument::REQUIRED, '名称']];
    }
}
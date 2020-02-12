<?php

/**
 * Created by PhpStorm.
 * User: wangy
 * Date: 2020/2/12
 * Time: 10:49
 */

namespace Meibuyu\Micro\Command;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Meibuyu\Micro\Generator\FileAlreadyExistsException;
use Meibuyu\Micro\Generator\ValidatorGenerator;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @Command()
 */
class ValidatorCommand extends HyperfCommand
{

    protected $name = 'gen:validator';

    /**
     * Handle the current command.
     */
    public function handle()
    {
        try {
            $name = $this->input->getArgument('name');
            (new ValidatorGenerator(['name' => $name]))->run();
            $this->info("Validator created successfully.");
        } catch (FileAlreadyExistsException $e) {
            $this->error($e->getMessage() . ' already exists!');
        }
    }

    public function getArguments()
    {
        return [['name', InputArgument::REQUIRED, '名称']];
    }

}
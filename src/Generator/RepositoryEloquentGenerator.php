<?php

/**
 * Created by PhpStorm.
 * User: wangy
 * Date: 2020/2/12
 * Time: 9:15
 */

namespace Meibuyu\Micro\Generator;


class RepositoryEloquentGenerator extends Generator
{

    protected $stub = 'repository/eloquent';

    protected $classPath = 'Repository/Eloquent';

    protected $extension = 'RepositoryEloquent';

    /**
     * @return array
     * @throws FileAlreadyExistsException
     */
    public function getReplacements()
    {
        return array_merge(parent::getReplacements(), [
            'interface' => $this->getInterface(),
            'validator' => $this->getValidator(),
            'validator_method' => $this->getValidatorMethod(),
        ]);
    }

    /**
     * @return string
     * @throws FileAlreadyExistsException
     */
    public function getInterface()
    {
        $interfaceGenerator = new RepositoryInterfaceGenerator(['name' => $this->name]);
        $interfaceNamespace = $interfaceGenerator->_namespace . $interfaceGenerator->_name;
        $interfaceGenerator->run();
        return str_replace(["\\", '/'], '\\', $interfaceNamespace) . $interfaceGenerator->getExtension();
    }

    /**
     * @return string
     * @throws FileAlreadyExistsException
     */
    public function getValidator()
    {
        if ($this->validator) {
            $validatorGenerator = new ValidatorGenerator(['name' => $this->name]);
            $validatorNamespace = $validatorGenerator->_namespace . $validatorGenerator->_name;
            $validatorGenerator->run();
            return 'use ' . str_replace(["\\", '/'], '\\', $validatorNamespace) . $validatorGenerator->getExtension() . ';';
        } else {
            return '';
        }

    }

    public function getValidatorMethod()
    {
        if ($this->validator) {
            $class = $this->getClass();
            return 'public function validator()' . PHP_EOL . '    {' . PHP_EOL . '        return ' . $class . 'Validator::class;' . PHP_EOL . '    }' . PHP_EOL;
        } else {
            return '';
        }
    }

}
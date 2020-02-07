<?php

/**
 * Created by PhpStorm.
 * User: zero
 * Date: 2020/2/7
 * Time: 13:47
 */

namespace Meibuyu\Micro\Repository\Eloquent;

use Hyperf\DbConnection\Model\Model;
use Meibuyu\Micro\Exceptions\HttpResponseException;
use Meibuyu\Micro\Exceptions\RepositoryException;
use Meibuyu\Micro\Repository\Contracts\RepositoryInterface;
use Meibuyu\Micro\Validator\Contracts\ValidatorInterface;
use Psr\Container\ContainerInterface;

abstract class BaseRepository implements RepositoryInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * BaseRepository constructor.
     * @param ContainerInterface $container
     * @throws RepositoryException
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->makeModel();
        $this->makeValidator();
    }

    /**
     * Specify Model class name
     * @return mixed
     */
    abstract public function model();

    /**
     * Specify Validator class name
     * @return null|mixed
     */
    public function validator()
    {
        return null;
    }

    /**
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = $this->container->make($this->model());
        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Hyperf\\DbConnection\\Model\\Model");
        }
        return $this->model = $model;
    }

    /**
     * @param null $validator
     *
     * @return null|ValidatorInterface
     * @throws RepositoryException
     */
    public function makeValidator($validator = null)
    {
        $validator = !is_null($validator) ? $validator : $this->validator();

        if (!is_null($validator)) {
            $this->validator = $this->container->make($validator);

            if (!$this->validator instanceof ValidatorInterface) {
                throw new RepositoryException("Class {$validator} must be an instance of Meibuyu\\Micro\\Validator\\Contracts\\ValidatorInterface");
            }

            return $this->validator;
        }

        return null;
    }


    /**
     * @param $id
     * @param array $columns
     * @return mixed
     * @throws HttpResponseException
     */
    public function find($id, $columns = ['*'])
    {
        $model = $this->model->find($id, $columns);
        if (!$model) {
            throw new HttpResponseException('数据不存在');
        }
        return $model;
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        return $this->model->get($columns);
    }

    public function paginate($perPage = 10, $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }

    public function create(array $attributes)
    {
        $model = $this->model->newInstance($attributes);
        $model->save();
        return $model;
    }

    public function update(array $attributes, $id)
    {
        $model = $this->model->findOrFail($id);
        $model->fill($attributes);
        $model->save();
        return $model;
    }

    /**
     * @param $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete($id)
    {
        $model = $this->find($id);
        return $model->delete();
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        return $this->model->where($field, '=', $value)->first($columns);
    }


}
<?php

/**
 * Created by PhpStorm.
 * User: zero
 * Date: 2020/2/7
 * Time: 13:42
 */

namespace Meibuyu\Micro\Repository\Contracts;

interface RepositoryInterface
{
    public function all($columns = array('*'));

    public function paginate($perPage = 10, $columns = array('*'));

    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);

    public function find($id, $columns = array('*'));

    public function findBy($field, $value, $columns = array('*'));

    /**
     * Load relations
     *
     * @param $relations
     *
     * @return $this
     */
    public function with($relations);

    /**
     * Order collection by a given column
     *
     * @param string $column
     * @param string $direction
     *
     * @return $this
     */
    public function orderBy($column, $direction = 'asc');
}
<?php
/**
 * Created by PhpStorm.
 * User: 王源
 * Date: 2020/1/9
 * Time: 15:08
 */

namespace Meibuyu\Micro\Service;

use Exception;
use Hyperf\DbConnection\Model\Model;
use Meibuyu\Micro\Helper;

class BaseService
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * 查找一个数据
     * @param $id
     * @return Model | array
     */
    protected function find($id)
    {
        $model = $this->model->find($id);
        return $model;
    }

    public function all(array $columns = ['*'], array $relations = []): array
    {
        return $this->model->with($relations)->get($columns)->toArray();
    }

    /**
     * 获取一条数据
     * @param int $id
     * @param array $columns
     * @param array $relations
     * @return mixed
     */
    public function get(int $id, array $columns = ['*'], array $relations = [])
    {
        return $this->model->with($relations)->find($id, $columns);
    }

    /**
     * 插入一条数据
     * @param array $params
     * @return array
     */
    public function insert($params)
    {
        try {
            $res = $this->model->insert($params);
            return Helper::success($res);
        } catch (Exception $e) {
            return Helper::fail('', $e->getMessage());
        }
    }

    /**
     * 新增一条数据
     * @param array $params
     * @return array
     */
    public function create($params)
    {
        try {
            $model = $this->model->newInstance($params);
            $model->save();
            return Helper::success($model);
        } catch (Exception $e) {
            return Helper::fail('', $e->getMessage());
        }
    }

    /**
     * 更新数据
     * @param $id
     * @param array $params
     * @return array
     */
    public function update($id, $params)
    {
        try {
            $model = $this->find($id);
            $model->fill($params);
            $model->save();
            return Helper::success($model);
        } catch (Exception $e) {
            return Helper::fail('', $e->getMessage());
        }
    }

    /**
     * 删除数据
     * @param $id
     * @return array
     */
    public function delete($id)
    {
        try {
            $model = $this->find($id);
            $res = $model->delete();
            if ($res) {
                return Helper::success($res, '删除成功');
            } else {
                return Helper::fail($res, '删除失败');
            }
        } catch (Exception $e) {
            return Helper::fail('', $e->getMessage());
        }
    }
}

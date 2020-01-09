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
    public function find($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return Helper::fail('', '数据不存在', 404);
        }
        return $model;
    }

    /**
     * 获取一条数据
     * @param $id
     * @return array
     */
    public function get($id)
    {
        $model = $this->find($id);
        return Helper::success($model);
    }

    /**
     * 插入一条数据
     * @param array $data
     * @return array
     */
    public function insert(array $data)
    {
        try {
            $res = $this->model->insert($data);
            return Helper::success($res);
        } catch (Exception $e) {
            return Helper::fail('', $e->getMessage());
        }
    }

    /**
     * 新增一条数据
     * @param array $data
     * @return array
     */
    public function create(array $data)
    {
        try {
            $model = $this->model->newInstance($data);
            $model->save();
            return Helper::success($model);
        } catch (Exception $e) {
            return Helper::fail('', $e->getMessage());
        }
    }

    /**
     * 更新数据
     * @param $id
     * @param array $data
     * @return array
     */
    public function update($id, array $data)
    {
        try {
            $model = $this->find($id);
            $model->fill($data);
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

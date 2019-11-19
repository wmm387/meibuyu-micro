<?php
/**
 * Created by PhpStorm.
 * UserInterface: 梁俊杰
 * Date: 2019/11/18
 * Time: 17:15
 * Description:
 */
namespace  Meibuyu\Micro;
interface CategoryInterface
{
    /**
     * 新增品类
     * @param array $attribute
     * @return bool
     */
    public function add($attribute);

    /**
     * 更新品类
     * @param array $attribute
     * @param int $id
     * @return bool
     */
    public function update($attribute, $id);


    public function delete($id);
}

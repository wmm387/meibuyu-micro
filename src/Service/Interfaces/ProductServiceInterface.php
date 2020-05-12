<?php
/**
 * Created by PhpStorm.
 * User: 王源
 * Date: 2020/1/9
 * Time: 15:07
 */

namespace Meibuyu\Micro\Service\Interfaces;

interface ProductServiceInterface
{

    /**
     * 获取单个数据
     * @param int $id 产品id
     * @param array $relations 产品的关联关系，支持：["brand","category","ingredient","product_name","status","type","images","price_info","product_children"]
     * @return array
     */
    public function get($id, array $relations = []): array;

    /**
     * 通过id列表获取产品数组
     * @param array $idList 产品id的列表
     * @param array $relations 产品的关联关系，支持["brand","category","ingredient","product_name","status","type","images","price_info","product_children"]
     * @param array $columns 产品表的字段，默认显示全部
     * @return array
     */
    public function getByIdList(array $idList, array $relations = [], array $columns = ['*']): array;


    /**
     * 通过产品列表
     * @param int $page 第几页数据，默认：1
     * @param array $relations 产品的关联关系，支持["brand","category","ingredient","product_name","status","type","images","price_info","product_children"]
     * @param int $pageSize 每页产品数默认：15，最大支持100
     * @param array $columns 产品表的字段，默认显示全部
     * @return array
     */
    public function list($page = 1, array $relations = [], $pageSize = 15, array $columns = ['*']): array;


    /**
     * 获取某个产品的子产品，包含颜色和尺码
     * @param int $productId 产品编号
     * @return array
     */
    public function getProductChildren($productId): array;

    /** 获取某个产品的平台产品，包含颜色和尺码
     * @param int $productId 产品编号
     * @param int $site_id 站点id，可选，默认为空
     * @return array
     */
    public function getPlatformProduct($productId, $site_id = null): array;

    /**
     * 获取平台产品的子产品
     * @param int $platformProductId 平台产品id
     * @return array
     */
    public function getPlatformProductChildren($platformProductId): array;

    /**
     * 获取某个站点的所有平台产品
     * @param int $site_id 站点id
     * @param int $page 第几页数据，默认：1
     * @param array $relations 平台产品的关联关系，支持["product","amazon_warehouse","platform_product_images","platform_product_children"]
     * @param int $pageSize 每页列表数默认：15，最大支持100
     * @param array $columns 平台产品表的字段，默认显示全部
     * @return array
     */
    public function getPlatformProductListBySite($site_id, $page = 1, array $relations = [], $pageSize = 15, array $columns = ['*']): array;

    /**
     * 获取全部尺码列表
     * @return array
     */
    public function sizes(): array;

    /**
     * 获取全部颜色列表
     * @return array
     */
    public function colors(): array;

    /**
     * 获取全部品类列表
     * @return array
     */
    public function categories(): array;

    /**
     * 获取全部品牌列表
     * @return array
     */
    public function brands(): array;

    /**
     * 获取全部报关品名列表
     * @return array
     */
    public function productNames(): array;

    /**
     * 获取全部成分列表
     * @return array
     */
    public function ingredients(): array;

    /**
     * 获取全部产品状态列表
     * @return array
     */
    public function productStatus(): array;

    /**
     * 获取全部平台产品状态列表
     * @return array
     */
    public function platformProductStatus(): array;
}

<?php
/**
 * 店铺费用模型
 *
 *
 *
 * * @网店运维 (c) 2015-2018 ShopWWI Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.c om
 * @link       交流群号：111731672
 * @since      网店运维提供技术支持 授权请购买shopnc授权
 */
defined('ByShopWWI') or exit('Access Invalid!');
class store_costModel extends Model{

    public function __construct(){
        parent::__construct('store_cost');
    }

    /**
     * 读取列表
     * @param array $condition
     *
     */
    public function getStoreCostList($condition, $page='', $order='', $field='*') {
        $result = $this->field($field)->where($condition)->page($page)->order($order)->select();
        return $result;
    }

    /**
     * 读取单条记录
     * @param array $condition
     *
     */
    public function getStoreCostInfo($condition, $fields = '*') {
        $result = $this->where($condition)->field($fields)->find();
        return $result;
    }

    /*
     * 增加
     * @param array $param
     * @return bool
     */
    public function addStoreCost($param){
        return $this->insert($param);
    }

    /*
     * 删除
     * @param array $condition
     * @return bool
     */
    public function delStoreCost($condition){
        return $this->where($condition)->delete();
    }

    /**
     * 更新
     * @param array $data
     * @param array $condition
     */
    public function editStoreCost($data,$condition) {
        return $this->where($condition)->update($data);
    }

}

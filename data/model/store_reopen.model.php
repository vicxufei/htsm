<?php
/**
 * 店铺续签
 * * @网店运维 (c) 2015-2018 ShopWWI Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.c om
 * @link       交流群号：111731672
 * @since      网店运维提供技术支持 授权请购买shopnc授权
 */
defined('ByShopWWI') or exit('Access Invalid!');

class store_reopenModel extends Model {
    public function __construct() {
        parent::__construct('store_reopen');
    }

    /**
     * 取得列表
     * @param unknown $condition
     * @param string $pagesize
     * @param string $order
     */
    public function getStoreReopenList($condition = array(), $pagesize = '', $order = 're_id desc') {
        return $this->where($condition)->order($order)->page($pagesize)->select();
    }

    /**
     * 增加新记录
     * @param unknown $data
     * @return
     */
    public function addStoreReopen($data) {
        return $this->insert($data);
    }

    /**
     * 取单条信息
     * @param unknown $condition
     */
    public function getStoreReopenInfo($condition) {
        return $this->where($condition)->find();
    }

    /**
     * 更新记录
     * @param unknown $condition
     * @param unknown $data
     */
    public function editStoreReopen($data,$condition) {
        return $this->where($condition)->update($data);
    }

    /**
     * 取得数量
     * @param unknown $condition
     */
    public function getStoreReopenCount($condition) {
        return $this->where($condition)->count();
    }

    /**
     * 删除记录
     * @param unknown $condition
     */
    public function delStoreReopen($condition) {
        return $this->where($condition)->delete();
    }
}

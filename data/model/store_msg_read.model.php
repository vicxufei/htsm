<?php
/**
 * 店铺消息阅读模板模型
 * * @网店运维 (c) 2015-2018 ShopWWI Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.c om
 * @link       交流群号：111731672
 * @since      网店运维提供技术支持 授权请购买shopnc授权
 */
defined('ByShopWWI') or exit('Access Invalid!');
class store_msg_readModel extends Model{
    public function __construct() {
        parent::__construct('store_msg_read');
    }
    /**
     * 新增店铺纤细阅读
     * @param unknown $insert
     */
    public function addStoreMsgRead($insert) {
        $insert['read_time'] = TIMESTAMP;
        return $this->insert($insert);
    }

    /**
     * 查看店铺消息阅读详细
     * @param unknown $condition
     * @param string $field
     */
    public function getStoreMsgReadInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 店铺消息阅读列表
     * @param unknown $condition
     * @param string $field
     * @param string $order
     */
    public function getStoreMsgReadList($condition, $field = '*', $order = 'read_time desc') {
        return $this->field($field)->where($condition)->order($order)->select();
    }

    /**
     * 删除店铺消息阅读记录
     * @param unknown $condition
     */
    public function delStoreMsgRead($condition) {
        $this->where($condition)->delete();
    }
}

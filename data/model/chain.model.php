<?php
/**
 * 店铺门店模型管理
 *
 *
 *
 * * @网店运维 (c) 2015-2018 ShopWWI Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.c om
 * @link       交流群号：111731672
 * @since      网店运维提供技术支持 授权请购买shopnc授权
 */
defined('ByShopWWI') or exit('Access Invalid!');
class chainModel extends Model {
    public function __construct(){
        parent::__construct('chain');
    }

    /**
     * 门店列表
     * @param array $condition
     * @param string $field
     * @param int $page
     * @return array
     */
    public function getChainList($condition, $field = '*', $page = 0) {
        return $this->field($field)->where($condition)->page($page)->select();
    }

    /**
     * 门店详细信息
     * @param array $condition
     * @return array
     */
    public function getChainInfo($condition,$field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 添加门店
     * @param unknown $insert
     * @return boolean
     */
    public function addChain($insert) {
        return $this->insert($insert);
    }

    /**
     * 更新门店
     * @param array $update
     * @param array $condition
     * @return boolean
     */
    public function editChain($update, $condition) {
        return $this->where($condition)->update($update);
    }

    /**
     * 删除门店
     * @param array $condition
     * @return boolean
     */
    public function delChain($condition) {
        $chain_list = $this->getChainInfo($condition);
        if (empty($chain_list)) {
            return true;
        }
        foreach ($chain_list as $val) {
            @unlink(BASE_UPLOAD_PATH.DS.ATTACH_CHAIN.DS.$val['store_id'].DS.$val['chain_img']);
        }
        return $this->where($condition)->delete();
    }
}

<?php
/**
 * 新用户专享活动模型
 *
 */
defined('ByShopWWI') or exit('Access Invalid!');
class p_new_memberModel extends Model{

    public function __construct(){
        parent::__construct();

    }


    /**
     * 商品列表
     *
     * @param array $condition
     * @param string $field
     * @param int $page
     * @param int $limit
     * @param string $order
     * @return array
     */

    public function getNewMemberGoodsList($field = '*', $page = 0, $limit = 0, $order = 'sole_goods_id asc') {
        //return $this->table('p_new_member')->field($field)->where($condition)->limit($limit)->order($order)->page($page)->select();
        return $this->table('p_new_member')->field($field)->limit($limit)->select();
    }

    /**
     * 获取新会员专享商品详细信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getNewMemberGoodsInfo($condition, $field = '*') {
        return $this->table('p_new_member')->field($field)->where($condition)->find();
    }

    /**
     * 删除新会员专享商品
     * @param unknown $condition
     * @return boolean
     */
    public function delSoleGoods($condition) {
        return $this->table('p_new_member')->where($condition)->delete();
    }

    /**
     * 取得商品详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $goods_id
     * @return array
     */
    public function getNewMemberInfoOpenByGoodsID($goods_id) {
        //$goods_info = $this->_rGoodsNewMemberCache($goods_id);
        if (empty($goods_info)) {
            $goods_info = $this->getNewMemberGoodsInfo(array('goods_id'=>$goods_id),'new_member_price,upper_limit');
            $this->_wGoodsNewMemberCache($goods_id, $goods_info);
        }
        return $goods_info;
    }

    /**
     * 保存手机专享商品信息
     * @param array $insert
     * @return boolean
     */
    public function addSoleGoods($insert) {
        return $this->table('p_new_member')->insert($insert);
    }

    /**
     * 更新手机专享商品信息
     */
    public function editSoleGoods($update, $condition) {
        $result = $this->table('p_new_member')->where($condition)->update($update);
        if ($result) {
            $this->_dGoodsNewMemberCache($condition['goods_id']);
        }
        return $result;
    }


    /**
     * 读取商品新用户专享缓存
     * @param int $goods_id
     * @return array/bool
     */
    private function _rGoodsNewMemberCache($goods_id) {
        return rcache($goods_id, 'goods_new_member');
    }

    /**
     * 写入商品新用户专享缓存
     * @param int $goods_id
     * @param array $info
     * @return boolean
     */
    private function _wGoodsNewMemberCache($goods_id, $info) {
        return wcache($goods_id, $info, 'goods_new_member');
    }

    /**
     * 删除商品新用户专享缓存
     * @param int $goods_id
     * @return boolean
     */
    private function _dGoodsNewMemberCache($goods_id) {
        return dcache($goods_id, 'goods_new_member');
    }

}

<?php
/**
 * 标签会员
 *
 * @网店运维提供技术支持 授权请购买shopnc授权
 * @license    http://www.shopwwi.com
 * @link       交流群号：111731672
 */
defined('ByShopWWI') or exit('Access Invalid!');
class sns_mtagmemberModel extends Model {

    public function __construct(){
        parent::__construct('sns_mtagmember');
    }

    /**
     * 标签会员列表
     * @param array $condition
     * @param int $page
     * @param string $order
     */
    public function getSnsMTagMemberList($condition, $page, $order) {
        return $this->where($condition)->order($order)->page($page)->select();
    }
    
    /**
     * 更新标签会员
     * @param unknown $where
     * @param unknown $update
     */
    public function editSnsMTagMember($where, $update) {
        return $this->where($where)->update($update);
    }
    
    /**
     * 删除标签会员
     * @param unknown $where
     */
    public function delSnsMTagMember($where) {
        return $this->where($where)->delete();
    }
}

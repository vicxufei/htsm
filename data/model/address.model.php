<?php
/**
 * 我的地址
 *
 *
 *
 * * @网店运维 (c) 2015-2018 ShopWWI Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.c om
 * @link       交流群号：111731672
 * @since      网店运维提供技术支持 授权请购买shopnc授权
 */
defined('ByShopWWI') or exit('Access Invalid!');
class addressModel extends Model {

    public function __construct() {
        parent::__construct('address');
    }

    /**
     * 设置为非默认收货地址
     *
     * @param array $condition
     */
    public function unsetDefaultAddress($member_id) {
        return $this->editAddress(array('is_default' => 0), array('member_id' => $member_id,'is_default' => '1'));
    }

    /**
     * 设置为默认收货地址
     *
     * @param array $condition
     */
    public function setDefaultAddress($address_id,$member_id) {
        return $this->editAddress(array('is_default' => 1), array('address_id' => $address_id,'member_id' => $member_id));
    }

    /**
     * 取得买家默认收货地址
     *
     * @param array $condition
     */
    public function getDefaultAddressInfo($condition = array(), $order = 'is_default desc,address_id desc') {
        return $this->getAddressInfo($condition, $order);
    }

    /**
     * 取得单条地址信息
     * @param array $condition
     * @param string $order
     */
    public function getAddressInfo($condition, $order = '') {
        $addr_info = $this->where($condition)->order($order)->find();
        if (C('delivery_isuse') && $addr_info['dlyp_id']) {
            $model_delivery = Model('delivery_point');
            $dlyp_info = $model_delivery->getDeliveryPointOpenInfo(array('dlyp_id' => $addr_info['dlyp_id']));
            if (!empty($dlyp_info)) {
                $addr_info['dlyp_mobile'] = $dlyp_info['dlyp_mobile'];
                $addr_info['dlyp_telephony'] = $dlyp_info['dlyp_telephony'];
                $addr_info['dlyp_address_name'] = $dlyp_info['dlyp_address_name'];
                $addr_info['dlyp_area_info'] = $dlyp_info['dlyp_area_info'];
                $addr_info['dlyp_address'] = $dlyp_info['dlyp_address'];
                $addr_info['dlyp_mobile'] = $dlyp_info['dlyp_mobile'];
                $addr_info['area_id'] = $dlyp_info['dlyp_area_3'];
                $addr_info['area_info'] = $dlyp_info['dlyp_area_info'];
                $addr_info['address'] = '（'.$dlyp_info['dlyp_address_name'].') '.$dlyp_info['dlyp_address']
                . '，电话：'.trim($dlyp_info['dlyp_mobile'].'，'.$dlyp_info['dlyp_telephony'],'，');
            }
        }

        $addr_info['fpic']= $addr_info['idcard_front'];
        $addr_info['bpic']= $addr_info['idcard_back'];

        if($addr_info['idcard_front']){
            $addr_info['idcard_front']= 'http://img2.htths.com/shop/idcards/'.$addr_info['idcard_front'];
        }
        if($addr_info['idcard_back']){
            $addr_info['idcard_back']= 'http://img2.htths.com/shop/idcards/'.$addr_info['idcard_back'];
        }

        return $addr_info;
    }

    /**
     * 读取地址列表
     *
     * @param
     * @return array 数组格式的返回结果
     */
    public function getAddressList($condition, $order = 'address_id desc'){
        $address_list = $this->where($condition)->order($order)->select();
        if (empty($address_list)) return array();
        if (C('delivery_isuse')) {
            $dlyp_ids = array();$dlyp_new_list = array();
            foreach ($address_list as $k => $v) {
                if($v['idcard_front']){
                    $address_list[$k]['idcard_front']= 'http://img2.htths.com/shop/idcards/'.$v['idcard_front'];
                }
                if($v['idcard_back']){
                    $address_list[$k]['idcard_back']= 'http://img2.htths.com/shop/idcards/'.$v['idcard_back'];
                }
                if ($v['dlyp_id']) {
                    $dlyp_ids[] = $v['dlyp_id'];
                }
            }
            if (!empty($dlyp_ids)) {
                $model_delivery = Model('delivery_point');
                $condition = array();
                $condition['dlyp_id'] = array('in',$dlyp_ids);
                $dlyp_list = $model_delivery->getDeliveryPointOpenList($condition);
                foreach ($dlyp_list as $k => $v) {
                    $dlyp_new_list[$v['dlyp_id']]= $v;
                }
            }
            if (!empty($dlyp_new_list)) {
                foreach ($address_list as $k => $v) {
                    if (!$v['dlyp_id']) continue;
                    $dlyp_info = $dlyp_new_list[$v['dlyp_id']];
                    $address_list[$k]['area_info'] = $dlyp_info['dlyp_area_info'];
                    $address_list[$k]['address'] = $dlyp_info['dlyp_address_name'].'（'.$dlyp_info['dlyp_address'].'）'
                        . '，电话：'.trim($dlyp_info['dlyp_mobile'].'，'.$dlyp_info['dlyp_telephony'],'，');
                    $address_list[$k]['type'] = '[自提服务站]';
                }
            }
        }
        return $address_list;
    }

    /**
     * 取数量
     * @param unknown $condition
     */
    public function getAddressCount($condition = array()) {
        return $this->where($condition)->count();
    }

    /**
     * 构造检索条件
     *
     * @param array $condition 检索条件
     * @return string 数组形式的返回结果
     */
    private function _condition($condition){
        $condition_str = '';

        if ($condition['member_id'] != ''){
            $condition_str .= " member_id = '". intval($condition['member_id']) ."'";
        }

        return $condition_str;
    }

    /**
     * 新增地址
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function addAddress($param){
        return $this->insert($param);
    }

    /**
     * 取单个地址
     *
     * @param int $area_id 地址ID
     * @return array 数组类型的返回结果
     */
    public function getOneAddress($id){
        if (intval($id) > 0){
            $param = array();
            $param['table'] = 'address';
            $param['field'] = 'address_id';
            $param['value'] = intval($id);
            $result = Db::getRow($param);
            return $result;
        }else {
            return false;
        }
    }

    /**
     * 更新地址信息
     *
     * @param array $param 更新数据
     * @return bool 布尔类型的返回结果
     */
    public function editAddress($update, $condition){
        return $this->where($condition)->update($update);
    }
    /**
     * 验证地址是否属于当前用户
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function checkAddress($member_id,$address_id) {
        /**
         * 验证地址是否属于当前用户
         */
        $check_array = self::getOneAddress($address_id);
        if ($check_array['member_id'] == $member_id){
            unset($check_array);
            return true;
        }
        unset($check_array);
        return false;
    }
    /**
     * 删除地址
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     */
    public function delAddress($condition){
        return $this->where($condition)->delete();
    }
}
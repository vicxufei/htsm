<?php
/**
 * 我的购物车
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2015 ShopNC Inc. (http://www.shopnc.net)
 * @license    http://www.shopnc.net
 * @link       http://www.shopnc.net
 * @since      File available since Release v1.1
 */



defined('ByShopWWI') or exit('Access Invalid!');

class member_cartControl extends mobileMemberControl {

    public function __construct() {
        parent::__construct();
    }


    /**
     * 设置配送区域
     */
    public function set_freight_areaOp() {
        $member_id = $this->member_info['member_id'];
        $model_member = Model('member');
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$_POST["area_id"],      "require"=>"true",      "message"=>'请选择完整的配送区域'),
            array("input"=>$_POST["city_id"],      "require"=>"true",      "message"=>'请选择完整的配送区域'),
            array("input"=>$_POST["province_id"],      "require"=>"true",      "message"=>'请选择完整的配送区域'),
            array("input"=>$_POST["area_info"],      "require"=>"true",      "message"=>'请选择完整的配送区域'),
            array("input"=>$_POST["transport_id"],      "require"=>"true",      "message"=>'商品的运费模板为空'),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            output_error($error);
        }
        $member_array   = array();
        $member_array['member_areaid']      = $_POST['area_id'];
        $member_array['member_cityid']      = $_POST['city_id'];
        $member_array['member_provinceid']  = $_POST['province_id'];
        $member_array['member_areainfo']    = $_POST['area_info'];

        $update = $model_member->editMember(array('member_id'=>$member_id),$member_array);
        if($update){
            if (!is_numeric($_POST['area_id']) || !is_numeric($_POST['transport_id']))  output_error('参数错误!');
            $area_freight = Model('transport')->area_freight(intval($_POST['transport_id']),intval($_POST['area_id']));
            output_data($area_freight);
        }else{
            output_error('添加配送区域失败!');
        }

    }

    /**
     * 购物车列表
     */
    public function cart_listOp() {
        $model_cart = Model('cart');

        $condition = array('buyer_id' => $this->member_info['member_id']);
        $cart_list  = $model_cart->listCart('db', $condition);
        $member_areaid = $this->member_info['member_areaid'];
        $member_id = $this->member_info['member_id'];
        //$this->_get_cart_info($cart_list,$member_areaid);
        $logic_cart = Logic('cart');
        $store_cart_list = $logic_cart->_get_cart_info($cart_list,$member_areaid,$member_id);
        output_data($store_cart_list);
    }


    /**
     * 购物车添加
     */
    public function cart_addOp() {
        $goods_id = intval($_POST['goods_id']);
        $quantity = intval($_POST['quantity']);

        if($goods_id <= 0 || $quantity <= 0) {
            output_error('参数错误!');
        }

        $model_goods = Model('goods');
        $model_cart = Model('cart');
        $logic_buy_1 = Logic('buy_1');

        $goods_info = $model_goods->getGoodsOnlineInfoAndPromotionById($goods_id);

        //验证是否可以购买
        if(empty($goods_info)) {
            output_error('商品已下架或不存在');
        }

        if(!empty($goods_info['new_member_goods'])){
            $model_order = Model('order');
            $count = $model_order->getOrderStateCancelCount(array('buyer_id'=>$this->member_info['member_id']));
            if(intval($count) > 0 ){
                output_error('限新用户首单购买!');
            }
        }

        //限时折扣
        $logic_buy_1->getXianshiInfo($goods_info,$quantity);

//        if(!empty($goods_info['xianshi_info']) && $quantity > $goods_info['xianshi_info']['upper_limit']){
//            output_error('本商品限购'.$goods_info['xianshi_info']['upper_limit'].'件');
//        }

        if(!empty($goods_info['xianshi_info'])){
            if($quantity > $goods_info['xianshi_info']['upper_limit']){
                output_error('本商品限购'.$goods_info['xianshi_info']['upper_limit'].'件');
            }
            if($goods_info['xianshi_info']['start_time'] > time()) {
                output_error('活动暂未开始');
            }
            if(empty($this->member_info['device_id'])){
                output_error('促销活动仅限最新版App(V2.0.15以上版本)',array('no_deviceid' => 'true'));
            }
        }

        if ($goods_info['store_id'] == $this->member_info['store_id']) {
            output_error('不能购买自己发布的商品');
        }
        if(intval($goods_info['goods_storage']) < 1 || intval($goods_info['goods_storage']) < $quantity) {
            output_error('库存不足');
        }

        if ($goods_info['xianshi_info']) {
            $model_order= Model('order');
            $condi = array();
            $condi['order_goods.buyer_id'] = $this->member_info['member_id'];
            $condi['order_goods.goods_id'] = $goods_info['goods_id'];
            $condi['orders.order_state'] = array('gt', 0);
            $condi['orders.add_time'] = array('time',array($goods_info['xianshi_info']['start_time'],$goods_info['xianshi_info']['end_time']));
            $goods_num_sum = $model_order->getOrderGoodsSum($condi,'order_goods.goods_num');
            if(!empty($goods_num_sum) && $goods_num_sum + $quantity >$goods_info['xianshi_info']['upper_limit']){
                output_error('本商品限购'.$goods_info['xianshi_info']['upper_limit'].'件,活动期间您已购买过'.$goods_num_sum.'件!');
            }

            //App专享
            $condi2 = array();
            $condi2['order_goods.buyer_deviceid'] = $this->member_info['device_id'];
            $condi2['order_goods.goods_id'] = $goods_info['goods_id'];
            $condi2['orders.order_state'] = array('gt', 0);
            $condi2['orders.add_time'] = array('time',array($goods_info['xianshi_info']['start_time'],$goods_info['xianshi_info']['end_time']));
            $goods_num_sum2 = $model_order->getOrderGoodsSum($condi,'order_goods.goods_num');
            if(!empty($goods_num_sum2) && $goods_num_sum2 + $quantity >$goods_info['xianshi_info']['upper_limit']){
                output_error('本商品限购'.$goods_info['xianshi_info']['upper_limit'].'件,活动期间您已购买过'.$goods_num_sum2.'件!');
            }
        }

        $param = array();
        $param['buyer_id']  = $this->member_info['member_id'];
        $param['transport_id']  = $goods_info['transport_id'];
        $param['goods_id']  = $goods_info['goods_id'];
        $param['goods_name'] = $goods_info['goods_name'];
        $param['goods_price'] = $goods_info['goods_price'];
        $param['goods_image'] = $goods_info['goods_image'];

        $result = $model_cart->addCart($param, 'db', $quantity);
        if($result) {
            $condition = array('buyer_id' => $this->member_info['member_id']);
            $cart_list  = $model_cart->listCart('db', $condition);
            output_data(array('cart_count'=>count($cart_list)));
           // output_data('1');
        } else {
            output_error('加入购物车失败');
        }
    }

    /**
     * 购物车添加
     */
    public function cart_add2Op() {
        $goods_id = intval($_POST['goods_id']);
        $quantity = intval($_POST['quantity']);

        if($goods_id <= 0 || $quantity <= 0) {
            output_error('参数错误!');
        }

        $model_goods = Model('goods');
        $model_cart = Model('cart');
        $logic_buy_1 = Logic('buy_1');

        $goods_info = $model_goods->getGoodsOnlineInfoAndPromotionById($goods_id);


        //验证是否可以购买
        if(empty($goods_info)) {
            output_error('商品已下架或不存在');
        }

        if(!empty($goods_info['new_member_goods'])){
            $model_order = Model('order');
            $count = $model_order->getOrderStateCancelCount(array('buyer_id'=>$this->member_info['member_id']));
            if(intval($count) > 0 ){
                output_error('限新用户首单购买!');
            }
        }

        //限时折扣
        $logic_buy_1->getXianshiInfo($goods_info,$quantity);
        if(!empty($goods_info['xianshi_info'])){
            if($quantity > $goods_info['xianshi_info']['upper_limit']){
                output_error('本商品限购'.$goods_info['xianshi_info']['upper_limit'].'件');
            }
            if($goods_info['xianshi_info']['start_time'] > time()) {
                output_error('活动暂未开始');
            }
            if(empty($this->member_info['device_id'])){
                output_error('促销活动仅限最新版App');
            }
        }


        if ($goods_info['store_id'] == $this->member_info['store_id']) {
            output_error('不能购买自己发布的商品');
        }
        if(intval($goods_info['goods_storage']) < 1 || intval($goods_info['goods_storage']) < $quantity) {
            output_error('库存不足');
        }

        if ($goods_info['xianshi_info']) {
            $model_order= Model('order');
            $condi = array();
            $condi['order_goods.buyer_id'] = $this->member_info['member_id'];
            $condi['order_goods.goods_id'] = $goods_info['goods_id'];
            $condi['orders.order_state'] = array('gt', 0);
            $condi['orders.add_time'] = array('time',array($goods_info['xianshi_info']['start_time'],$goods_info['xianshi_info']['end_time']));
            $goods_num_sum = $model_order->getOrderGoodsSum($condi,'order_goods.goods_num');
            if(!empty($goods_num_sum) && $goods_num_sum + $quantity >$goods_info['xianshi_info']['upper_limit']){
                output_error('本商品限购'.$goods_info['xianshi_info']['upper_limit'].'件,活动期间您已购买过'.$goods_num_sum.'件!');
            }
            //App专享
            $condi2 = array();
            $condi2['order_goods.buyer_deviceid'] = $this->member_info['device_id'];
            $condi2['order_goods.goods_id'] = $goods_info['goods_id'];
            $condi2['orders.order_state'] = array('gt', 0);
            $condi2['orders.add_time'] = array('time',array($goods_info['xianshi_info']['start_time'],$goods_info['xianshi_info']['end_time']));
            $goods_num_sum2 = $model_order->getOrderGoodsSum($condi,'order_goods.goods_num');
            if(!empty($goods_num_sum2) && $goods_num_sum2 + $quantity >$goods_info['xianshi_info']['upper_limit']){
                output_error('本商品限购'.$goods_info['xianshi_info']['upper_limit'].'件,活动期间您已购买过'.$goods_num_sum2.'件!');
            }


        }

        $param = array();
        $param['buyer_id']  = $this->member_info['member_id'];
        $param['transport_id']  = $goods_info['transport_id'];
        $param['goods_id']  = $goods_info['goods_id'];
        $param['goods_name'] = $goods_info['goods_name'];
        $param['goods_price'] = $goods_info['goods_price'];
        $param['goods_image'] = $goods_info['goods_image'];

        $result = $model_cart->addCart($param, 'db', $quantity);
        if($result) {
            $condition = array('buyer_id' => $this->member_info['member_id']);
            $cart_list  = $model_cart->listCart('db', $condition);
            output_data(array('cart_count'=>count($cart_list)));
            // output_data('1');
        } else {
            output_error('加入购物车失败');
        }
    }

    /**
     * 购物车删除
     */
    public function cart_delOp() {
        $cart_id = explode(',', $_POST['cart_id']);
        $model_cart = Model('cart');

        if($cart_id > 0) {
            $condition = array();
            $condition['buyer_id'] = $this->member_info['member_id'];
            $condition['cart_id'] = array('in',$cart_id);
            $result=$model_cart->delCart('db', $condition);

            if(!$result){
                output_error('购物车删除失败,请重试!');
            }
        }

        $condition = array('buyer_id' => $this->member_info['member_id']);
        $cart_list  = $model_cart->listCart('db', $condition);
        //$this->_get_cart_info($cart_list);
        $logic_cart = Logic('cart');
        $store_cart_list = $logic_cart->_get_cart_info($cart_list,$this->member_info['member_areaid'],$this->member_info['member_id']);
        output_data($store_cart_list);
    }

    /**
     * 更新购物车购买数量
     */
    public function cart_edit_quantityOp() {
        $cart_id = intval(abs($_POST['cart_id']));
        $quantity = intval(abs($_POST['quantity']));

        if(empty($cart_id) || empty($quantity)) {
            output_error('参数错误');
        }

        $model_cart = Model('cart');

        $cart_info = $model_cart->getCartInfo(array('cart_id'=>$cart_id, 'buyer_id' => $this->member_info['member_id']));

        //检查是否为本人购物车
        if($cart_info['buyer_id'] != $this->member_info['member_id']) {
            output_error('购物车中已无该商品');
        }
        
        //检查库存是否充足
        if(!$this->_check_goods_storage($cart_info, $quantity, $this->member_info['member_id'],$this->member_info['device_id'])) {
            output_error('超出限购数或库存不足');
        }

        $data = array();
        $data['goods_num'] = $quantity;
        $data['cart_checked'] = '1';
        $update = $model_cart->editCart($data, array('cart_id'=>$cart_id));
        if ($update) {
            $condition = array('buyer_id' => $this->member_info['member_id']);
            $cart_list  = $model_cart->listCart('db', $condition);
            //$this->_get_cart_info($cart_list);
            $logic_cart = Logic('cart');
            $store_cart_list = $logic_cart->_get_cart_info($cart_list,$this->member_info['member_areaid'],$this->member_info['member_id']);
            output_data($store_cart_list);
        } else {
            output_error('修改失败');
        }
    }


    /**
     * 更改购物车勾选状态
     */
    public function cart_checkedOp() {
        $cart_id = explode(',', $_POST['cart_id']);
        $cart_checked = intval(abs($_POST['cart_checked']));

        if(empty($cart_id) ) {
            output_error('参数错误');
        }

        $model_cart = Model('cart');
        //$condition = array('buyer_id' => $this->member_info['member_id']);
        //$cart_list  = $model_cart->listCart('db', $condition);
        ////检查购物车中是否含有保税仓发货的商品
        //$bonded_checked = 0;
        //$non_bonded_checked = [];
        //foreach($cart_list as $cart_info){
        //    if($cart_info['transport_id']>5 && $cart_info['cart_checked']== 1){
        //        $bonded_checked =1;
        //    }elseif($cart_info['cart_checked']== 1){
        //        $non_bonded_checked[]= $cart_info['cart_id'];
        //    }
        //}
        //if(!empty($non_bonded_checked) && is_array($non_bonded_checked) && $bonded_checked){
        //    output_error('勾选失败');
        //}

        $data = array();
        $data['cart_checked'] = $cart_checked;
        $update = $model_cart->editCart($data, array('cart_id'=>array('in',array_values($cart_id))));

        if ($update) {
            $condition = array('buyer_id' => $this->member_info['member_id']);
            $cart_list  = $model_cart->listCart('db', $condition);
        //    $this->_get_cart_info($cart_list);
            $logic_cart = Logic('cart');
            $store_cart_list = $logic_cart->_get_cart_info($cart_list,$this->member_info['member_areaid'],$this->member_info['member_id']);
            output_data($store_cart_list);
        } else {
            output_error('修改失败');
        }
    }

    /**
     * 检查库存是否充足
     */
    private function _check_goods_storage(& $cart_info, $quantity, $member_id, $device_id) {
        $model_goods= Model('goods');
        $logic_buy_1 = Logic('buy_1');

        //普通商品
        $goods_info = $model_goods->getGoodsOnlineInfoAndPromotionById($cart_info['goods_id']);

        //超出了新用户首单中设置的上限
        if(!empty($goods_info['new_member_goods']) && $quantity > $goods_info['new_member_goods']['upper_limit']){
            output_error('超出了限购数量!');
        }

        //限时折扣
        $logic_buy_1->getXianshiInfo($goods_info,$quantity);
        if ($goods_info['xianshi_info']) {
            if ($goods_info['xianshi_info']['upper_limit'] && $quantity > $goods_info['xianshi_info']['upper_limit']) {
                return false;
            }
            $model_order= Model('order');
            //$condition['buyer_id'] = array('in', array(ORDER_STATE_PAY, ORDER_STATE_SEND, ORDER_STATE_SUCCESS));
            $condition = array();
            $condition['order_goods.buyer_id'] = $member_id;
            $condition['order_goods.goods_id'] = $goods_info['goods_id'];
            $condition['orders.order_state'] = array('gt', 0);
            $condition['orders.add_time'] = array('time',array($goods_info['xianshi_info']['start_time'],$goods_info['xianshi_info']['end_time']));
            $goods_num_sum = $model_order->getOrderGoodsSum($condition,'order_goods.goods_num');
            if(!empty($goods_num_sum) && $goods_num_sum + $quantity >$goods_info['xianshi_info']['upper_limit']){
                return false;
            }
            //App专享
            $condition2 = array();
            $condition2['order_goods.buyer_deviceid'] = $device_id;
            $condition2['order_goods.goods_id'] = $goods_info['goods_id'];
            $condition2['orders.order_state'] = array('gt', 0);
            $condition2['orders.add_time'] = array('time',array($goods_info['xianshi_info']['start_time'],$goods_info['xianshi_info']['end_time']));
            $goods_num_sum2 = $model_order->getOrderGoodsSum($condition2,'order_goods.goods_num');
            if(!empty($goods_num_sum2) && $goods_num_sum2 + $quantity >$goods_info['xianshi_info']['upper_limit']){
                return false;
            }
        }


        if(intval($goods_info['goods_storage']) < $quantity) {
            return false;
        }
        $goods_info['cart_id'] = $cart_info['cart_id'];
        $cart_info = $goods_info;

        return true;
    }
	

	/**
     * 检查购物车数量
     */
	//public function cart_countOp() {
	//	$model_cart = Model('cart');
	//	$count = $model_cart->countCartByMemberId($this->member_info['member_id']);
	//	$data['cart_count'] = $count;
	//	output_data($data);
	//}

    /**
     * 查询购物车中各个商品,统计购物车中商品数量和总金额
     */
    private function _get_cart_info($cart_list) {
        // 购物车列表 [得到最新商品属性及促销信息]
        $cart_list = logic('buy_1')->getGoodsCartList($cart_list);

        $sum = 0;
        $count=0;
        $cart4post='';
        $cart_a = array();
        $need_idcard_count = 0;
        foreach ($cart_list as $key => $val) {
            $val['store_id']=0;
            //$cart_a[$val['store_id']]['store_id'] = $val['store_id'];
            //$cart_a[$val['store_id']]['store_name'] = $val['store_name'];

            $cart_a[$key] = $val;
            $cart_a[$key]['goods_image'] = cthumb($val['goods_image'], $val['store_id']);
            //if($goods_data['goods_spec']=='N;'){
            //    $cart_a[$val['store_id']]['goods'][$key]['goods_spec'] = '';
            //}
            //if($goods_data['goods_promotion_type']){
            //    $cart_a[$val['store_id']]['goods'][$key]['goods_price'] = $goods_data['goods_promotion_price'];
            //}
            $cart_list[$key]['goods_sum'] = ncPriceFormat($val['good_final_price'] * $val['goods_num']);
            if($val['cart_checked'] == 1){
                ++$count;
                $sum += $cart_list[$key]['goods_sum'];
                $cart4post .=$val['cart_id'].'|'.$val['goods_num'].',';
                if($val['transport_id'] > 5){
                    $need_idcard_count ++;
                }

            }
        }
        $need_idcard_count > 0 ? $need_idcard = 1 : $need_idcard = 0;
        output_data(array('cart_list' => $cart_a, 'sum' => ncPriceFormat($sum),'cart_count'=>count($cart_list),'cart_checked'=>$count,'cart4post'=>$cart4post,'need_idcard'=>$need_idcard));

    }



}

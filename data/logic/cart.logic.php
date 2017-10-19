<?php
/**
 * 购物车逻辑
 *
 */
defined('ByShopWWI') or exit('Access Invalid!');
class cartLogic {

    public function __construct() {
    }

    /**
     * 查询购物车中各个商品,统计购物车中商品数量和总金额
     */
    public function _get_cart_info($cart_list,$member_areaid = '',$member_id = '') {
        //检查购物车中是否含有保税仓发货的商品
        $bonded_checked = 0;
        $non_bonded_checked = [];
        foreach($cart_list as $cart_info){
            if($cart_info['transport_id']>5 && $cart_info['cart_checked']== 1){
                $bonded_checked =1;
            }elseif($cart_info['cart_checked']== 1){
                $non_bonded_checked[]= $cart_info['cart_id'];
            }
        }
        //若包含保税仓商品被勾选,将非保税仓商品变为非勾选
        $tip = '';
        if(!empty($non_bonded_checked) && is_array($non_bonded_checked) && $bonded_checked){
            $tip = '不能同时勾选保税仓和非保税仓的商品';
            $data['cart_checked'] = '0';
            $model_cart = Model('cart');
            $update = $model_cart->editCart($data, array('cart_id'=>array('in',array_values($non_bonded_checked))));
            if($update){
                // $cart_list  = $model_cart->listCart('db',array('buyer_id'=>$_SESSION['member_id']));
                foreach($cart_list as &$cart_info){
                    foreach($non_bonded_checked as $xianguo_cart_id){
                        if($cart_info['cart_id']== $xianguo_cart_id){
                            $cart_info['cart_checked'] = 0;
                        }
                    }
                }
            }
        }
// 购物车列表 [得到最新商品属性及促销信息]
        $logic_buy_1 = logic('buy_1');
        $cart_goods_list = $logic_buy_1->getGoodsCartList($cart_list);

        //购物车商品以店铺ID分组显示,并计算商品小计,店铺小计与总价由JS计算得出
        $cart_total = 0;
        $cart_weight = 0;
        $cart_count = 0;     //勾选的购物车商品数量
        $cart_num = 0;     //购物车商品数量
        $bonded_count = 0;   //保税仓商品的数量(勾选及未勾选)
        $non_bonded_count = 0;   //非保税仓商品的数量(勾选及未勾选)
        $cart_all_item = '';
        $cart_checked_item = '';
        $no_chain_items = '';
        $cart_all_checked = 1;
        $bonded_checked = 0;  //勾选了保税仓商品:  0否 1是
        $non_bonded_checked = 0;  //勾选了非保税仓商品:   0否 1是
        $has_chain_checked = 0;
        $transport_ids = array();
        $store_cart_list = array();
        foreach ($cart_goods_list as &$cart) {
            $cart['goods_image']  = thumb($cart,60);
            $cart['is_bonded']  = $cart['transport_id'] > 5 ? 1:0;
            $cart['goods_url'] = urlShop('goods', 'index', array('goods_id' => $cart['goods_id']));
            $cart['goods_tprice'] = ncPriceFormat($cart['good_final_price'] * $cart['goods_num']);
            if($cart['new_member_price']){
                $model_order = Model('order');
                $count = $model_order->getOrderStateCancelCount(array('buyer_id'=>$member_id));
                if(intval($count) == 0 ){
                    $cart['good_final_price'] = ncPriceFormat($cart['new_member_price']);
                    $cart['goods_tprice'] = ncPriceFormat($cart['new_member_price'] * $cart['goods_num']);
                }
            }
            
            $cart['goods_tweight'] = ncPriceFormat($cart['goods_weight'] * $cart['goods_num'] /1000);
            $store_cart_list['cart_list'][] = $cart;
            $cart_num ++;
            //所有勾选及非勾选的项用逗号分隔
            $cart_all_item .=$cart['cart_id'].',';

            if($cart['transport_id'] > 5){
                $bonded_count++;
            }else{
                $non_bonded_count++;
            }
            if($cart['cart_checked']){
                $cart_checked_item .= $cart['cart_id'].'|'.$cart['goods_num'].',';
                //所有勾选项的模板ID组成的array
                $transport_ids[] = $cart['transport_id'];
                if($cart['is_chain'] == 1){
                    $has_chain_checked = 1;
                    $non_bonded_checked =1;
                }else{
                    $no_chain_items .=$cart['cart_id'].',';
                    //购物车中含有保税仓的商品
                    if($cart['transport_id'] > 5){
                        $bonded_checked =1;
                    }else{
                        $non_bonded_checked =1;
                    }
                }
                $cart_total += $cart['goods_tprice'];
                $cart_weight += $cart['goods_tweight'];
                $cart_count++;
            }else{
                $cart_all_checked = 0;
            }
        }
        $store_cart_list['cart_total'] = ncPriceFormat($cart_total);
        $store_cart_list['cart_weight'] = $cart_weight;
        $store_cart_list['cart_count'] = $cart_count;
        $store_cart_list['cart_num'] = $cart_num;
        $store_cart_list['no_chain_items'] = $no_chain_items;
        $store_cart_list['both_bonded_none'] =  ($bonded_count > 0 && $non_bonded_count > 0) ? 1:0 ;
        $store_cart_list['cart_all_item'] = $cart_all_item;
        $store_cart_list['cart_checked_item'] = $cart_checked_item;
        $store_cart_list['cart_all_checked'] = $cart_all_checked;
        if($bonded_checked && $non_bonded_checked){
            $store_cart_list['allow_submit'] = 0;
            $store_cart_list['alarm'] = '保税仓商品需独立下单';
        }else{
            $store_cart_list['allow_submit'] = 1;
            $store_cart_list['alarm'] = '';
        }
        //计算运费

        if(!empty($member_areaid) && intval($member_areaid) > 0){
            $area_id = $member_areaid;
        }else{
            $dregion = $_COOKIE['dregion'];
            $deliver_region = explode('|', $dregion);
            $deliver_region_ids = explode(' ', $deliver_region[0]);
            $area_id = $deliver_region_ids[2];
        }
        $store_cart_list['tip'] = $tip;
        $freight_total = Model('transport')->weight_area_freight($transport_ids,$area_id,$cart_weight,$cart_total);
        $store_cart_list['freight_total'] = ncPriceFormat($freight_total);
        $store_cart_list['need2pay'] = ncPriceFormat($cart_total + $freight_total);
        $store_cart_list['bonded_checked'] =  $bonded_checked;
        $store_cart_list['non_bonded_checked'] =  $non_bonded_checked;
        $store_cart_list['has_chain_checked'] =  $has_chain_checked;


        return $store_cart_list;
    }

}

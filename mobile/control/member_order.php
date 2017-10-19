<?php
/**
 * 我的订单
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

class member_orderControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 订单列表
     */
    public function order_listOp() {
        $model_order = Model('order');
        $condition = array();        
//		$condition = $this->order_type_no($_POST["state_type"]);
		$condition['delete_state'] = 0;
//        if($_POST["order_state"] != ''){
        if(!empty($_POST["order_state"])){
            $condition['order_state'] = intval($_POST["order_state"]);
        }
        //$condition['order_type'] = intval($_POST["order_type"]);
        //isset($_POST["order_type"]) ? $condition['order_type'] = intval($_POST["order_type"]): $condition['order_type'] = 1;
		$condition['buyer_id'] = $this->member_info['member_id'];
        //$order_list_array = $model_order->getNormalOrderList($condition, $this->page, '*', 'order_id desc','', array('order_goods'));
        $pagesize=intval($_POST['page']);
        if($pagesize == 0){
            $pagesize = 5;
        }
        if (is_numeric($_POST['curpage']) && $_POST['curpage'] > 0) {
            $curpage = intval($_POST['curpage']);
            $start =  ($curpage-1) * $pagesize;
        } else {
            $start = 0;
        }
		$order_list_array = $model_order->getOrderList($condition, $pagesize, 'order_id,order_sn,pay_sn,add_time,payment_code,payment_time,finnshed_time,goods_amount,order_amount,shipping_fee,evaluation_state,evaluation_again_state,order_state,refund_state,lock_state,delete_state,shipping_code,order_type,api_pay_time,chain_id,chain_code,trade_no', 'order_id desc',"$start,$pagesize", array('order_common','order_goods'));

        $order_group_list = $order_pay_sn_array = array();
        foreach ($order_list_array as $value) {

            //显示取消订单
            $value['if_cancel'] = $model_order->getOrderOperateState('buyer_cancel',$value);
            //显示收货
            $value['if_receive'] = $model_order->getOrderOperateState('receive',$value);
            //显示锁定中
            //$value['if_lock'] = $model_order->getOrderOperateState('lock',$value);
            //显示物流跟踪
            $value['if_deliver'] = $model_order->getOrderOperateState('deliver',$value);
			//显示评论
			$value['if_evaluation'] = $model_order->getOrderOperateState('evaluation',$value);
            //显示删除
			$value['if_delete'] = $model_order->getOrderOperateState('delete',$value);
			//$value['ownshop'] = true;

			//商品图
            foreach ($value['extend_order_goods'] as $k => $goods_info) {
				 if ($goods_info['goods_type'] == 5) {
					unset($value['extend_order_goods'][$k]);  
				} else {
					$value['extend_order_goods'][$k] = $goods_info;
					$value['extend_order_goods'][$k]['goods_image_url'] = cthumb($goods_info['goods_image'], 240, $value['store_id']);			
				}
            }

            $order_group_list[$value['pay_sn']]['order_list'][] = $value;

            //如果有在线支付且未付款的订单则显示合并付款链接
            if ($value['order_state'] == ORDER_STATE_NEW) {
                $order_group_list[$value['pay_sn']]['pay_amount'] += $value['order_amount'] - $value['rcb_amount'] - $value['pd_amount'];
            }
            $order_group_list[$value['pay_sn']]['add_time'] = $value['add_time'];
            $order_group_list[$value['pay_sn']]['formated_time'] = date("Y-m-d H:i:s",$value['add_time']);

            //记录一下pay_sn，后面需要查询支付单表
            $order_pay_sn_array[] = $value['pay_sn'];
			
        }

        $new_order_group_list = array();
        foreach ($order_group_list as $key => $value) {
            $value['pay_sn'] = strval($key);
            $new_order_group_list[] = $value;
        }

        $page_count = $model_order->gettotalpage();

        output_data(array('order_group_list' => $new_order_group_list), mobile_page($page_count));
    }

    /**
     * 订单列表V2.0.6
     */
    public function order_list206Op() {
        $model_order = Model('order');
        $condition = array();
        $condition['delete_state'] = 0;
        if(!empty($_POST["order_state"])){
            $condition['order_state'] = intval($_POST["order_state"]);
        }
        $condition['buyer_id'] = $this->member_info['member_id'];
        //$order_list_array = $model_order->getNormalOrderList($condition, $this->page, '*', 'order_id desc','', array('order_goods'));
        $pagesize=intval($_POST['page']);
        if($pagesize == 0){
            $pagesize = 5;
        }
        if (is_numeric($_POST['curpage']) && $_POST['curpage'] > 0) {
            $curpage = intval($_POST['curpage']);
            $start =  ($curpage-1) * $pagesize;
        } else {
            $start = 0;
        }
        $order_list_array = $model_order->getOrderList($condition, $pagesize, 'order_id,order_sn,pay_sn,add_time,payment_code,payment_time,finnshed_time,goods_amount,order_amount,shipping_fee,evaluation_state,evaluation_again_state,order_state,refund_state,lock_state,delete_state,shipping_code,order_type,api_pay_time,chain_id,chain_code,trade_no', 'order_id desc',"$start,$pagesize", array('order_common','order_goods'));

        $order_group_list = array();
        foreach ($order_list_array as $i=>$value) {
            $order_group_list[$i]['add_time'] = date("Y-m-d H:i:s",$value['add_time']);
            $order_group_list[$i]['if_chain'] = $value['chain_id'] > 0 ? 1:0;
            $order_group_list[$i]['order_amount'] = $value['order_amount'];
            $order_group_list[$i]['order_sn'] = $value['order_sn'];
            $order_group_list[$i]['order_id'] = $value['order_id'];
            $order_group_list[$i]['pay_sn'] = $value['pay_sn'];

            //显示取消订单
            $order_group_list[$i]['if_cancel'] = $model_order->getOrderOperateState('buyer_cancel',$value);
            //显示收货
            $order_group_list[$i]['if_receive'] = $model_order->getOrderOperateState('receive',$value);
            //显示锁定中
            //$value['if_lock'] = $model_order->getOrderOperateState('lock',$value);
            //显示物流跟踪
            $order_group_list[$i]['if_deliver'] = $model_order->getOrderOperateState('deliver',$value);
            //显示评论
            $order_group_list[$i]['if_evaluation'] = $model_order->getOrderOperateState('evaluation',$value);
            //显示删除
            $order_group_list[$i]['if_delete'] = $model_order->getOrderOperateState('delete',$value);

            $extend_order_goods = array();
            //商品图
            foreach ($value['extend_order_goods'] as $k => $goods_info) {
                if ($goods_info['goods_type'] == 5) {
                    unset($value['extend_order_goods'][$k]);
                } else {
                    $extend_order_goods[$k]['goods_id'] = $goods_info['goods_id'];
                    $extend_order_goods[$k]['goods_image'] = cthumb($goods_info['goods_image'], 240, $value['store_id']);
                    $extend_order_goods[$k]['goods_name'] = $goods_info['goods_name'];
                    $extend_order_goods[$k]['goods_num'] = $goods_info['goods_num'];
                    $extend_order_goods[$k]['goods_price'] = $goods_info['goods_price'];
                }
            }
            $order_group_list[$i]['extend_order_goods'] = $extend_order_goods;
        }

        $page_count = $model_order->gettotalpage();
        output_data(array('order_list' => $order_group_list), mobile_page($page_count));
    }

    /**
     * 取消订单
     */
    public function order_cancelOp() {
        $model_order = Model('order');
        $logic_order = Logic('order');
        $order_id = intval($_POST['order_id']);

        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        //$condition['order_type'] = 1;
        $order_info = $model_order->getOrderInfo($condition);
        $if_allow = $model_order->getOrderOperateState('buyer_cancel',$order_info);
        if (!$if_allow) {
            output_error('无权操作');
        }
        //if (TIMESTAMP - 86400 < $order_info['api_pay_time']) {
        //    $_hour = ceil(($order_info['api_pay_time']+86400-TIMESTAMP)/3600);
        //    output_error('该订单曾尝试使用第三方支付平台支付，须在'.$_hour.'小时以后才可取消');
        //}
        $result = $logic_order->changeOrderStateCancel($order_info,'buyer', $this->member_info['member_name'], '其它原因');
        if(!$result['state']) {
            output_error($result['msg']);
        } else {
            output_data('1');
        }
    }

    /**
     * 订单确认收货
     */
    public function order_receiveOp() {
        $model_order = Model('order');
        $logic_order = Logic('order');
        $order_id = intval($_POST['order_id']);

        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $condition['order_type'] = 1;
        $order_info = $model_order->getOrderInfo($condition);
        $if_allow = $model_order->getOrderOperateState('receive',$order_info);
        if (!$if_allow) {
            output_error('无权操作');
        }

        $result = $logic_order->changeOrderStateReceive($order_info,'buyer', $this->member_info['member_name'],'签收了货物');
        if(!$result['state']) {
            output_error($result['msg']);
        } else {
            output_data('1');
        }
    }

    /**
     * 订单删除
     */
    public function order_delOp() {
        $model_order = Model('order');
        $logic_order = Logic('order');
        $order_id = intval($_POST['order_id']);

        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        //$condition['order_type'] = 1;
        $order_info = $model_order->getOrderInfo($condition);
        $if_allow = $model_order->getOrderOperateState('delete',$order_info);
        if (!$if_allow) {
            output_error('无权操作');
        }

        $result = $logic_order->changeOrderStateRecycle($order_info,'buyer','delete');
        if(!$result['state']) {
            output_error($result['msg']);
        } else {
            output_data('1');
        }
    }

    /**
     * 买家订单状态操作
     *
     */
    public function change_stateOp() {
        $state_type = $_POST['state_type'];
        $order_id   = intval($_POST['order_id']);

        $model_order = Model('order');

        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info = $model_order->getOrderInfo($condition);

        //取得其它订单类型的信息
        $model_order->getOrderExtendInfo($order_info);

        if($_GET['state_type'] == 'order_cancel') {
            $result = $this->_order_cancel($order_info, $_POST);
        } else if ($_GET['state_type'] == 'order_receive') {
            $result = $this->_order_receive($order_info, $_POST);
        } else if (in_array($_GET['state_type'],array('order_delete','order_drop','order_restore'))){
            $result = $this->_order_recycle($order_info, $_GET);
        } else {
            exit();
        }

        if(!$result['state']) {
            output_error($result['msg']);
            //showDialog($result['msg'],'','error','',5);
        } else {
            output_data($result['msg']);
            //showDialog($result['msg'],'reload','js');
        }
    }

    /**
     * 物流跟踪
     */
    public function search_deliverOp(){
        $order_sn   = intval($_POST['order_sn']);
        if ($order_sn <= 0) {
            output_error('订单不存在');
        }

        $model_order    = Model('order');
        $condition['order_sn'] = $order_sn;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
        if (empty($order_info) || !in_array($order_info['order_state'],array(ORDER_STATE_SEND,ORDER_STATE_SUCCESS))) {
            output_error('订单不存在');
        }

        $express = rkcache('express',true);
        $e_code = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
        $e_name = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];

        $deliver_info = $this->_get_express($e_code, $order_info['shipping_code']);
        output_data(array('express_name' => $e_name, 'shipping_code' => $order_info['shipping_code'], 'deliver_info' => $deliver_info));
    }

    public function waimaiOp(){
        $order_sn   = intval($_POST['order_sn']);
        $sent_time = date('Y-m-d H:i:s',$order_sn);
        $deliver_info = array(
            1 => '主人，您的包裹在'.$sent_time.'起一个小时内送达。 联系电话：53228886'
        );
        if(time() - $order_sn > 24 * 60 * 60){
            $deliver_info[2] = '用户已签收';
        }

        output_data(array( 'deliver_info' => $deliver_info));
    }

	/**
     * 订单详情
     */
    public function order_info206Op(){
		$order_id = intval($_POST['order_id']);
        if ($order_id <= 0) {
			output_error('订单不存在');
        }
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_goods','order_common'),'order_id,order_sn,pay_sn,add_time,payment_code,payment_time,finnshed_time,goods_amount,order_amount,shipping_fee,evaluation_state,evaluation_again_state,order_state,shipping_code,order_type,chain_id,chain_code,trade_no');

        if (empty($order_info) || $order_info['delete_state'] == ORDER_DEL_STATE_DROP) {
            output_error('订单不存在');
        }

        $order_info['payment_time'] = $order_info['payment_time'] ? date('Y-m-d H:i:s',$order_info['payment_time']) : '';
        $order_info['finnshed_time'] = $order_info['finnshed_time'] ? date('Y-m-d H:i:s',$order_info['finnshed_time']) : '';
        $order_info['add_time'] = $order_info['add_time'] ? date('Y-m-d H:i:s',$order_info['add_time']) : '';

        //显示取消订单
        $order_info['if_buyer_cancel'] = $model_order->getOrderOperateState('buyer_cancel',$order_info);

        //显示收货
        $order_info['if_receive'] = $model_order->getOrderOperateState('receive',$order_info);

        //显示物流跟踪
        $order_info['if_deliver'] = $model_order->getOrderOperateState('deliver',$order_info);

        //显示评价
        $order_info['if_evaluation'] = $model_order->getOrderOperateState('evaluation',$order_info);

        //显示分享
        $order_info['if_share'] = $model_order->getOrderOperateState('share',$order_info);

        //显示系统自动取消订单日期
        if ($order_info['order_state'] == ORDER_STATE_NEW) {
            $order_info['order_cancel_day'] = $order_info['add_time'] + ORDER_AUTO_CANCEL_TIME * 3600;
        }
        //$order_info['if_deliver'] = false;
        ////显示快递信息
        //if ($order_info['shipping_code'] != '') {
			//$order_info['if_deliver'] = true;
        //    $express = rkcache('express',true);
        //    $order_info['express_info']['e_code'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
        //    $order_info['express_info']['e_name'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];
        //    $order_info['express_info']['e_url'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_url'];
        //}

        //显示系统自动收获时间
        if ($order_info['order_state'] == ORDER_STATE_SEND) {
            $order_info['order_confirm_day'] = $order_info['delay_time'] + ORDER_AUTO_RECEIVE_DAY * 24 * 3600;
        }

        //如果订单已取消，取得取消原因、时间，操作人
        if ($order_info['order_state'] == ORDER_STATE_CANCEL) {
            $close_info = $model_order->getOrderLogInfo(array('order_id'=>$order_info['order_id']),'log_id desc');
			$order_info['close_info'] = $close_info;
			$order_info['state_desc'] = $close_info['log_orderstate'];
			//$order_info['order_tips'] = $close_info['log_msg'];
        }

        foreach ($order_info['extend_order_goods'] as $key=>$value) {
            $order_info['extend_order_goods'][$key]['image_url'] = yf_cthumb2($value['goods_image']);
            unset($order_info['extend_order_goods'][$key]['goods_image']);
            $order_info['extend_order_goods'][$key]['goods_type_cn'] = orderGoodsType($value['goods_type']);
            unset($order_info['extend_order_goods'][$key]['goods_type']);
        }
        if(!$order_info['extend_order_common']['order_message']){
            $order_info['extend_order_common']['order_message'] = '';
        }

		output_data(array('order_info'=>$order_info));
	}

    /**
     * 订单详情
     */
    public function order_infoOp(){
        $order_id = intval($_GET['order_id']);
        if ($order_id <= 0) {
            output_error('订单不存在');
        }
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_goods','order_common'),'order_id,order_sn,pay_sn,add_time,payment_code,payment_time,finnshed_time,goods_amount,order_amount,shipping_fee,evaluation_state,evaluation_again_state,order_state,shipping_code,order_type,chain_id,chain_code,trade_no');

        if (empty($order_info) || $order_info['delete_state'] == ORDER_DEL_STATE_DROP) {
            output_error('订单不存在');
        }

        $order_info['payment_time'] = $order_info['payment_time'] ? date('Y-m-d H:i:s',$order_info['payment_time']) : '';
        $order_info['finnshed_time'] = $order_info['finnshed_time'] ? date('Y-m-d H:i:s',$order_info['finnshed_time']) : '';
        $order_info['add_time'] = $order_info['add_time'] ? date('Y-m-d H:i:s',$order_info['add_time']) : '';

        //显示取消订单
        $order_info['if_buyer_cancel'] = $model_order->getOrderOperateState('buyer_cancel',$order_info);

        //显示收货
        $order_info['if_receive'] = $model_order->getOrderOperateState('receive',$order_info);

        //显示物流跟踪
        $order_info['if_deliver'] = $model_order->getOrderOperateState('deliver',$order_info);

        //显示评价
        $order_info['if_evaluation'] = $model_order->getOrderOperateState('evaluation',$order_info);

        //显示分享
        $order_info['if_share'] = $model_order->getOrderOperateState('share',$order_info);

        //显示系统自动取消订单日期
        if ($order_info['order_state'] == ORDER_STATE_NEW) {
            $order_info['order_cancel_day'] = $order_info['add_time'] + ORDER_AUTO_CANCEL_TIME * 3600;
        }
        //$order_info['if_deliver'] = false;
        ////显示快递信息
        //if ($order_info['shipping_code'] != '') {
        //$order_info['if_deliver'] = true;
        //    $express = rkcache('express',true);
        //    $order_info['express_info']['e_code'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
        //    $order_info['express_info']['e_name'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];
        //    $order_info['express_info']['e_url'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_url'];
        //}

        //显示系统自动收获时间
        if ($order_info['order_state'] == ORDER_STATE_SEND) {
            $order_info['order_confirm_day'] = $order_info['delay_time'] + ORDER_AUTO_RECEIVE_DAY * 24 * 3600;
        }

        //如果订单已取消，取得取消原因、时间，操作人
        if ($order_info['order_state'] == ORDER_STATE_CANCEL) {
            $close_info = $model_order->getOrderLogInfo(array('order_id'=>$order_info['order_id']),'log_id desc');
            $order_info['close_info'] = $close_info;
            $order_info['state_desc'] = $close_info['log_orderstate'];
            //$order_info['order_tips'] = $close_info['log_msg'];
        }

        foreach ($order_info['extend_order_goods'] as $key=>$value) {
            $order_info['extend_order_goods'][$key]['image_url'] = yf_cthumb2($value['goods_image']);
            unset($order_info['extend_order_goods'][$key]['goods_image']);
            $order_info['extend_order_goods'][$key]['goods_type_cn'] = orderGoodsType($value['goods_type']);
            unset($order_info['extend_order_goods'][$key]['goods_type']);
        }
        if(!$order_info['extend_order_common']['order_message']){
            $order_info['extend_order_common']['order_message'] = '';
        }

        output_data(array('order_info'=>$order_info));
    }
	
		
/**
     * 订单详情
     */
    public function get_current_deliverOp(){
		$order_id   = intval($_POST['order_id']);
        if ($order_id <= 0) {
            output_error('订单不存在');
        }



        $model_order    = Model('order');
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
        if (empty($order_info) || !in_array($order_info['order_state'],array(ORDER_STATE_SEND,ORDER_STATE_SUCCESS))) {
            output_error('订单不存在');
        }

        $express = rkcache('express',true);
        $e_code = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
        $e_name = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];

        $deliver_info = $this->_get_express($e_code, $order_info['shipping_code']);


		$data = array();
		$data['deliver_info']['context'] = $e_name;
		$data['deliver_info']['time'] = $deliver_info['0'];
		output_data($data);
	}
    /**
     * 从第三方取快递信息
     *
     */
    public function _get_express($e_code, $shipping_code){
        $content = Model('express')->get_express($e_code, $shipping_code);        
        if (empty($content)) {
            output_error('物流信息查询失败');
        }
        $output = array();
        foreach ($content as $k=>$v) {
            if ($v['time'] == '') continue;
            $output[]= $v['time'].'&nbsp;&nbsp;'.$v['context'];
        }

        return $output;
    }

}

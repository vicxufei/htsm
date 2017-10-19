<?php
/**
 * 购买
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

class member_buyControl extends mobileMemberControl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 购物车、直接购买第一步:选择收获地址和配置方式
     */
    public function buy_step1Op() {
        $address_list = Model('address')->getAddressList(array('member_id'=>$this->member_info['member_id']));
        //$selected_address_id = intval($_POST['selected_address_id']);
        $selected_address_id = intval($_POST['address_id']);
        foreach($address_list as $address_item){
            $conditon = $selected_address_id > 0 ? $address_item['address_id'] == $selected_address_id : $address_item['is_default'] == '1';
            if($conditon){
                $selected_area_id = $address_item['area_id'];
                $selected_address_id = $address_item['address_id'];
                $selected_address_info = $address_item;
                break;
            }
        }


        ////得到会员等级
        //$model_member = Model('member');
        //$member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        //
        //if ($member_info){
        //    $member_gradeinfo = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));
        //    $member_discount = $member_gradeinfo['orderdiscount'];
        //    $member_level = $member_gradeinfo['level'];
        //} else {
            $member_discount = $member_level = 0;
        //}
        $cart_id = explode(',', $_POST['cart_id']);
        //得到购买数据
        $logic_buy = logic('buy');
        $result = $logic_buy->buyStep1($selected_area_id,$cart_id, $_POST['ifcart'], $this->member_info['member_id'],$member_discount,$member_level,$_POST['ifchain']);

		//print_R($result);
		if(!$result['state']) {
            output_error($result['msg']);
        } else {
            $result = $result['data'];
        }

        //if(!$result['allow_submit']){
        //    $selected_address_id = 0;
        //}
        if($selected_address_id > 0 && $result['need_idcard'] > 0){
            if(!$selected_address_info["idcard_no"]){
                $result['allow_submit'] = 0;
                $result['error_message'] = '请填写身份证号码!';
            }
        }

        if(empty($selected_address_info)){
            $selected_address_info = $address_list[0];
            $selected_address_id = $address_list[0]['address_id'];
            // $selected_area_id = $address_list[0]['area_id'];
        }

        //整理数据
        $buy_list = array();
        $buy_list['address_list'] = $address_list;
        $buy_list['selected_address_id'] = $selected_address_id;
        $buy_list['selected_address_info'] = $selected_address_info;

        $buy_list['ifcart'] = $_POST['ifcart'];
        $buy_list['ifchain'] = $_POST['ifchain'];

        $buy_list['store_cart_list'] = $result['store_cart_list'];
        $buy_list['store_cart_weight'] = $result['store_cart_weight'];
        $buy_list['store_cart_total'] = $result['store_cart_total'];
        $buy_list['freight_total'] = $result['freight_total'];
        $buy_list['need2pay'] = $result['need2pay'];
        $buy_list['need_idcard'] = $result['need_idcard'];
        if($result['need_idcard'] > 0){
            if($result['need_idcard'] == 1){
                $idcard_info = '您的订单中含有保税区商品,请补全身份证号,用于海关申报。';
            }elseif ($result['need_idcard'] == 2){
                $idcard_info = '您的订单中含有海外直邮商品,请补全身份证号及正反面照片,用于海关申报。';
            }
            if($result['need_idcard'] == 1 && !empty($selected_address_info['idcard_no'])){
                $idcard_info = $selected_address_info['idcard_no'];
            }
            if($result['need_idcard'] == 2 && !empty($selected_address_info['idcard_no']) && !empty($selected_address_info['idcard_front']) && !empty($selected_address_info['idcard_back'])){
                $idcard_info = $selected_address_info['idcard_no'];
            }
        }else{
            $idcard_info = '';
        }
        $buy_list['idcard_info'] = $idcard_info;
        $buy_list['allow_submit'] = $result['allow_submit'];
        $buy_list['error_message'] = $result['error_message'];
        //$buy_list['freight_hash'] = $result['freight_list'];
        //$buy_list['address_info'] = $result['address_info'];
        //$buy_list['ifshow_offpay'] = $result['ifshow_offpay'];
        //$buy_list['vat_hash'] = $result['vat_hash'];
        //$buy_list['inv_info'] = $result['inv_info'];
        //$buy_list['available_predeposit'] = $result['available_predeposit'];
        //$buy_list['available_rc_balance'] = $result['available_rc_balance'];
        //if (is_array($result['rpt_list']) && !empty($result['rpt_list'])) {
        //    foreach ($result['rpt_list'] as $k => $v) {
        //        unset($result['rpt_list'][$k]['rpacket_id']);
        //        unset($result['rpt_list'][$k]['rpacket_end_date']);
        //        unset($result['rpt_list'][$k]['rpacket_owner_id']);
        //        unset($result['rpt_list'][$k]['rpacket_code']);
        //    }
        //}
        //$buy_list['rpt_list'] = $result['rpt_list'] ? $result['rpt_list'] : array();
        //$buy_list['zk_list'] = $result['zk_list'];
		//$buy_list['order_amount'] = $sum;
		//$buy_list['rpt_info'] = '';
		//$buy_list['address_api'] = $logic_buy->changeAddr($result['freight_list'], '1', '1', $this->member_info['member_id']);

		//$buy_list['store_final_total_list'] = array('1'=>ncPriceFormat($sum));

        output_data($buy_list);
    }

    /**
     * 购物车、直接购买第二步:保存订单入库，产生订单号，开始选择支付方式
     *
     */
    public function buy_step2Op() {

        // yefeng20170219将推荐人的会员id写入购物会员的信息表中
        $ref_id = intval($_POST['ref_id']);
        if($ref_id > 0){
            $model_member = Model('member');
            $update_info    = array(
                'ref_member_id'=> $ref_id,
            );
            $res = $model_member->editMember(array('member_id'=>$this->member_info['member_id']),$update_info);
            if(!$res) {
                output_error('操作失败,请重试!');
            }
        }

//password 用户支付密码，启动预存款支付时需要提交
//fcode F码购买时需提交
        $param = array();
        $param['ifcart'] = $_POST['ifcart'];  //ifcart 购物车购买标志 1
        $param['cart_id'] = explode(',', $_POST['cart_id']);  //cart_id 购买参数
        $param['address_id'] = $_POST['address_id']; //address_id 收货地址编号
        $param['chain_id'] = $_POST['chain_id']; //门店编号
        $param['vat_hash'] = $_POST['vat_hash'];  //vat_hash 发票信息hash，第一步接口提供
        $param['offpay_hash'] = $_POST['offpay_hash'];  //offpay_hash 是否支持货到付款hash，通过更换收货地址接口获得
        $param['offpay_hash_batch'] = $_POST['offpay_hash_batch'];  //offpay_hash_batch 店铺是否支持货到付款hash
        $param['pay_name'] = $_POST['pay_name'];  //pay_name 付款方式，可选值 online(线上付款) offline(货到付款)
        $param['invoice_id'] = $_POST['invoice_id'];  //invoice_id 发票信息编号
        $param['rpt'] = $_POST['rpt'];  //?gyf

        //处理代金券
        $voucher = array();  //voucher 代金券，内容以竖线分割 voucher_t_id|store_id|voucher_price，多个店铺用逗号分割，例：10|2|10,1|3|10
        $post_voucher = explode(',', $_POST['voucher']);
        if(!empty($post_voucher)) {
            foreach ($post_voucher as $value) {
                list($voucher_t_id, $store_id, $voucher_price) = explode('|', $value);
                $voucher[$store_id] = $value;
            }
        }
        $param['voucher'] = $voucher;

        //手机端暂时不做支付留言，页面内容太多了
        //$param['pay_message'] = json_decode($_POST['pay_message']);
        $param['pay_message'] = $_POST['pay_message'];
        $param['pd_pay'] = $_POST['pd_pay'];  //pd_pay 是否使用预存款支付 1-使用 0-不使用
        $param['rcb_pay'] = $_POST['rcb_pay'];
        $param['password'] = $_POST['password'];
        $param['order_from'] = 2;
        $logic_buy = logic('buy');

        //得到会员等级
        //$model_member = Model('member');
        //$member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        //if ($member_info){
        //    $member_gradeinfo = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));
        //    $member_discount = $member_gradeinfo['orderdiscount'];
        //    $member_level = $member_gradeinfo['level'];
        //} else {
            $member_discount = $member_level = 0;
        //}
        $result = $logic_buy->buyStep2($param, $this->member_info['member_id'], $this->member_info['member_name'], $this->member_info['member_email'],$this->member_info['member_mobile'],$member_discount,$member_level,$this->member_info['device_id'],$this->member_info['ref_member_id']);
        if(!$result['state']) {
            output_error($result['msg']);
        }


        output_data(array('pay_sn' => $result['data']['pay_sn'],'order_total' => $result['data']['store_final_order_total']));
    }

    /**
     * 验证密码
     */
    public function check_passwordOp() {
        if(empty($_POST['password'])) {
            output_error('参数错误');
        }

        $model_member = Model('member');

        $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if($member_info['member_paypwd'] == md5($_POST['password'])) {
            output_data('1');
        } else {
            output_error('密码错误');
        }
    }

    /**
     * 更换收货地址
     */
    public function change_addressOp() {
        $area_id = intval($_POST['area_id']);
        $store_transport_ids = explode(',', $_POST['store_transport_ids']);
        $store_cart_weight = $_POST['store_cart_weight'];
        $store_cart_total = $_POST['store_cart_total'];

        $freight_total = Model('transport')->weight_area_freight($store_transport_ids,$area_id,$store_cart_weight,$store_cart_total);

        if($freight_total) {
            output_data($freight_total);
        } else {
            output_error('地址修改失败');
        }
    }
	

	/**
     * 支付方式
     */
    public function payOp() {
		$pay_sn = $_POST['pay_sn'];
		$condition = array();
		$condition['pay_sn'] = $pay_sn;
		$order_info = Model('order')->getOrderInfo($condition);
        $client_type= $this->member_info['client_type'];
        if(in_array($client_type, array('iOS', 'Android', 'win', 'wp'), true)){
            $client_type = 'app';
        }
        $payment_list = Model('mb_payment')->getMbPaymentList(array('payment_state' => '1','client_type' => $client_type),'payment_code,payment_name,payment_state');
		foreach($payment_list as $key=>$payment_item){
            if($payment_item['payment_code'] == 'wxpay3'){
                $payment_list[$key]['payment_img'] = 'http://static.htths.com/img/wxpay.png';
                $payment_list[$key]['text'] = '亿万用户的选择';
            }
        }
		$pay_info['pay_amount'] = $order_info['order_amount'];
		$pay_info['order_sn'] = $order_info['order_sn'];
		//$pay_info['member_available_pd'] = $this->member_info['available_predeposit'];
		//$pay_info['member_available_rcb'] = $this->member_info['available_rc_balance'];

		//$pay_info['member_paypwd'] = true;
		//if(empty($this->member_info['member_paypwd'])){
		//	$pay_info['member_paypwd'] = false;
		//}
		
		$pay_info['pay_sn'] = $order_info['pay_sn'];
		//$pay_info['payed_amount'] = $order_info['pd_amount'];
		//if($pay_info['payed_amount']>'0.00'){
		//	$pay_info['pay_amount'] = $pay_info['pay_amount']-$pay_info['payed_amount'];
		//}

		$pay_in["pay_info"]=$pay_info;
		$pay_in["pay_info"]["payment_list"]=$payment_list;
		output_data($pay_in);
	}

	/**
     * 支付密码确认
     */
    public function check_pd_pwdOp() {
		if($this->member_info['member_paypwd'] != md5($_POST['password'])){
			output_error('支付密码错误');
		}else{
			output_data('OK');
		}
	}
	
}

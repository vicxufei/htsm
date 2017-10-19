<?php
/**
 * 支付
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

class member_paymentControl extends mobileMemberControl {

    private $payment_code;
    private $payment_config;

    public function __construct() {
        parent::__construct();

        if($_GET['op'] != 'payment_list') {
            $payment_code = 'alipay';

            if(in_array($_GET['op'], array('wx_app_pay', 'wx_app_pay3', 'wx_app_vr_pay', 'wx_app_vr_pay3'), true)) {
                $payment_code = 'wxpay3';
            } elseif (isset($_GET['payment_code'])) {
                //$payment_code = $_GET['payment_code'];
                $payment_code = $_GET['state'] == 'hello-world' ? 'wxpay_jsapi' : $_GET['payment_code'];
            }
            switch ($_GET['op']){
                case 'ccb_yl':
                    $payment_code = 'ccb_yl';
                    break;
                case 'unionpay':
                    $payment_code = 'unionpay';
                    break;
                case 'wxjspay':
                    $payment_code = 'wxjspay';
                    break;
                case 'alipay_mb':
                    $payment_code = 'alipay_mb';
                    break;
            }
            //if($_GET['op'] == 'ccb_yl'){
            //    $payment_code = 'ccb_yl';
            //}
            //if($_GET['op'] == 'unionpay'){
            //    $payment_code = 'unionpay';
            //}
            //if($_GET['op'] == 'wxjspay'){
            //    $payment_code = 'wxjspay';
            //}

            $model_mb_payment = Model('mb_payment');
            $condition = array();
            $condition['payment_code'] = $payment_code;
            $mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);
            if(!$mb_payment_info) {
                output_error('支付方式未开启');
            }

            $this->payment_code = $payment_code;
            $this->payment_config = $mb_payment_info['payment_config'];
        }
    }

    /**
     * 实物订单支付
     */
    public function pay_newOp() {
        $pay_sn = $_POST['pay_sn'];
        if(empty($pay_sn)){
            $pay_sn = $_GET['pay_sn'];
        }

		if(!preg_match('/^\d{18}$/',$pay_sn)){
            output_error('参数错误');
        }
        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }
        $payment_code = $_GET['payment_code'];

			
        //站内余额支付
        $order_list = $this->_pd_pay($pay_info['data']['order_list'],$_GET);

        //计算本次需要在线支付（分别是含站内支付、纯第三方支付接口支付）的订单总金额
        $pay_amount = 0;
        $api_pay_amount = 0;
        $pay_order_id_list = array();
        if (!empty($order_list)) {
            foreach ($order_list as $order_info) {
                if ($order_info['order_state'] == ORDER_STATE_NEW) {
                    $api_pay_amount += $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
                    $pay_order_id_list[] = $order_info['order_id'];
                }
                $pay_amount += $order_info['order_amount'];
            }
        }
        if (empty($api_pay_amount)) {
            redirect(WAP_SITE_URL.'/tmpl/member/order_list.html');
        }

        $result = Model('order')->editOrder(array('api_pay_time'=>TIMESTAMP),array('order_id'=>array('in',$pay_order_id_list)));
        if(!$result) {
            output_error('更新订单信息发生错误，请重新支付');
        }



        $payment_info = $result['data'];
        //$pay_info['data']['api_pay_amount'] = ncPriceFormat($api_pay_amount);

        //如果是开始支付尾款，则把支付单表重置了未支付状态，因为支付接口通知时需要判断这个状态
        if ($pay_info['data']['if_buyer_repay']) {
            $update = Model('order')->editOrderPay(array('api_pay_state'=>0),array('pay_id'=>$pay_info['data']['pay_id']));
            if (!$update) {
                output_error('订单支付失败');
            }
            $pay_info['data']['api_pay_state'] = 0;
        }

        //第三方API支付
        $this->_api_pay($pay_info['data']);
    }

    /**
     * 虚拟订单支付
     */
    public function vr_payOp() {
        $pay_sn = $_GET['pay_sn'];

        $pay_info = $this->_get_vr_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        //第三方API支付
        $this->_api_pay($pay_info['data']);
    }


	/**
     * 站内余额支付(充值卡、预存款支付) 实物订单
     *
     */
    private function _pd_pay($order_list, $post) {
        if (empty($post['password'])) {
            return $order_list;
        }
        $model_member = Model('member');
        $buyer_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if ($buyer_info['member_paypwd'] == '' || $buyer_info['member_paypwd'] != md5($post['password'])) {
            return $order_list;
        }

        if ($buyer_info['available_rc_balance'] == 0) {
            $post['rcb_pay'] = null;
        }
        if ($buyer_info['available_predeposit'] == 0) {
            $post['pd_pay'] = null;
        }
        if (floatval($order_list[0]['rcb_amount']) > 0 || floatval($order_list[0]['pd_amount']) > 0) {
            return $order_list;
        }

        try {
            $model_member->beginTransaction();
            $logic_buy_1 = Logic('buy_1');
            //使用充值卡支付
            if (!empty($post['rcb_pay'])) {
                $order_list = $logic_buy_1->rcbPay($order_list, $post, $buyer_info);
            }

            //使用预存款支付
            if (!empty($post['pd_pay'])) {
                $order_list = $logic_buy_1->pdPay($order_list, $post, $buyer_info);
            }

            //特殊订单站内支付处理
            $logic_buy_1->extendInPay($order_list);

            $model_member->commit();
        } catch (Exception $e) {
            $model_member->rollback();
            output_error($e->getMessage());
        }

        return $order_list;
    }

    /**
     * 站内余额支付(充值卡、预存款支付) 虚拟订单
     *
     */
    private function _pd_vr_pay($order_info, $post) {
        if (empty($post['password'])) {
            return $order_info;
        }
        $model_member = Model('member');
        $buyer_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if ($buyer_info['member_paypwd'] == '' || $buyer_info['member_paypwd'] != md5($post['password'])) {
            return $order_info;
        }

        if ($buyer_info['available_rc_balance'] == 0) {
            $post['rcb_pay'] = null;
        }
        if ($buyer_info['available_predeposit'] == 0) {
            $post['pd_pay'] = null;
        }
        if (floatval($order_info['rcb_amount']) > 0 || floatval($order_info['pd_amount']) > 0) {
            return $order_info;
        }

        try {
            $model_member->beginTransaction();
            $logic_buy = Logic('buy_virtual');
            //使用充值卡支付
            if (!empty($post['rcb_pay'])) {
                $order_info = $logic_buy->rcbPay($order_info, $post, $buyer_info);
            }

            //使用预存款支付
            if (!empty($post['pd_pay'])) {
                $order_info = $logic_buy->pdPay($order_info, $post, $buyer_info);
            }

            $model_member->commit();
        } catch (Exception $e) {
            $model_member->rollback();
            showMessage($e->getMessage(), '', 'html', 'error');
        }

        return $order_info;
    }
    /**
     * 第三方在线支付接口
     *
     */
    private function _api_pay($order_pay_info) {
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';
        if(!is_file($inc_file)){
            output_error('支付接口不存在');
        }
        require($inc_file);
        $param = $this->payment_config;

        // wxpay_jsapi
        if ($this->payment_code == 'wxpay_jsapi') {
            $param['orderSn'] = $order_pay_info['pay_sn'];
            $param['orderFee'] = (int) (100 * $order_pay_info['api_pay_amount']);
            $param['orderInfo'] = C('site_name') . '商品订单' . $order_pay_info['pay_sn'];
            $param['orderAttach'] = ($order_pay_info['order_type'] == 'real_order' ? 'r' : 'v');
            $api = new wxpay_jsapi();
            $api->setConfigs($param);
            try {
				//exit('222');
                echo $api->paymentHtml($this);
            } catch (Exception $ex) {
                if (C('debug')) {
                    header('Content-type: text/plain; charset=utf-8');
                    echo $ex, PHP_EOL;
                } else {
                    Tpl::output('msg', $ex->getMessage());
                    Tpl::showpage('payment_result');
                }
            }
            exit;
        }

        $param['order_sn'] = $order_pay_info['pay_sn'];
        $param['order_amount'] = $order_pay_info['api_pay_amount'];
        $param['order_type'] = ($order_pay_info['order_type'] == 'real_order' ? 'r' : 'v');
        $payment_api = new $this->payment_code();
        $return = $payment_api->submit($param);
        echo $return;
        exit;
    }

    /**
     * 获取订单支付信息
     */
    private function _get_real_order_info($pay_sn) {
        $logic_payment = Logic('payment');

        //取订单信息
        $result = $logic_payment->getRealOrderInfo($pay_sn, $this->member_info['member_id']);
        if(!$result['state']) {
            return array('error' => $result['msg']);
        }

        //计算本次需要在线支付的订单总金额
        $pay_amount = 0;
        $pay_order_id_list = array();
        if (!empty($result['data']['order_list'])) {
            foreach ($result['data']['order_list'] as $order_info) {
                if ($order_info['order_state'] == ORDER_STATE_NEW) {
                    $pay_amount += $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
                    $pay_order_id_list[] = $order_info['order_id'];
                }
            }
        }
        $result['data']['api_pay_amount'] = ncPriceFormat($pay_amount);

        $update = Model('order')->editOrder(array('api_pay_time'=>TIMESTAMP),array('order_id'=>array('in',$pay_order_id_list)));
        if(!$update) {
            return array('error' => '更新订单信息发生错误，请重新支付');
        }

        //如果是开始支付尾款，则把支付单表重置了未支付状态，因为支付接口通知时需要判断这个状态
        if ($result['data']['if_buyer_repay']) {
            $update = Model('order')->editOrderPay(array('api_pay_state'=>0),array('pay_id'=>$result['data']['pay_id']));
            if (!$update) {
                return array('error' => '订单支付失败');
            }
            $result['data']['api_pay_state'] = 0;
        }

        return $result;
    }

    /**
     * 获取虚拟订单支付信息
     */
    private function _get_vr_order_info($pay_sn) {
        $logic_payment = Logic('payment');

        //取得订单信息
        $order_info = $logic_payment->getVrOrderInfo($pay_sn, $this->member_info['member_id']);
        if(!$order_info['state']) {
            output_error($order_info['msg']);
        }

        //计算本次需要在线支付的订单总金额
        $pay_amount = $order_info['data']['order_amount'] - $order_info['data']['pd_amount'] - $order_info['data']['rcb_amount'];
        $order_info['data']['api_pay_amount'] = ncPriceFormat($pay_amount);

        return $order_info;
    }

    /**
     * 可用支付参数列表
     */
    public function payment_listOp() {
        //$model_mb_payment = Model('mb_payment');
        //
        //$payment_list = $model_mb_payment->getMbPaymentOpenList();
        //
        //$payment_array = array();
        //if(!empty($payment_list)) {
        //    foreach ($payment_list as $value) {
        //        $payment_array[] = $value['payment_code'];
        //    }
        //}
        //
        //output_data(array('payment_list' => $payment_array));
    }

    /**
     * 微信APP订单支付
     */
    public function wx_app_payOp() {
        $pay_sn = $_POST['pay_sn'];

        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $param = array();
        $param['pay_sn'] = $pay_sn;
        $param['subject'] = $pay_info['data']['subject'];
        $param['amount'] = $pay_info['data']['api_pay_amount'] * 100;

        $data = $this->_get_wx_pay_info($param);
        if(isset($data['error'])) {
            output_error($data['error']);
        }
        output_data($data);
    }

    /**
     * 微信APP虚拟订单支付
     */
    public function wx_app_vr_payOp() {
        $pay_sn = $_POST['pay_sn'];

        $pay_info = $this->_get_vr_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $param = array();
        $param['pay_sn'] = $pay_sn;
        $param['subject'] = $result['data']['subject'];
        $param['amount'] = $result['data']['api_pay_amount'];

        $data = $this->_get_wx_pay_info($param);
        if(isset($data['error'])) {
            output_error($data['error']);
        }
        output_data($data);
   }

    /**
     * 获取支付参数
     */
    private function _get_wx_pay_info($pay_param) {
        $access_token = $this->_get_wx_access_token();
        if(empty($access_token)) {
            return array('error' => '支付失败code:1001');
        }

        $package = $this->_get_wx_package($pay_param);

        $noncestr = md5($package + TIMESTAMP);
        $timestamp = TIMESTAMP;
        $traceid = $this->member_info['member_id'];

        // 获取预支付app_signature
        $param = array();
        $param['appid'] = $this->payment_config['wxpay_appid'];
        $param['noncestr'] = $noncestr;
        $param['package'] = $package;
        $param['timestamp'] = $timestamp;
        $param['traceid'] = $traceid;
        $app_signature = $this->_get_wx_signature($param);

        // 获取预支付编号
        $param['sign_method'] = 'sha1';
        $param['app_signature'] = $app_signature;
        $post_data = json_encode($param);
        $prepay_result = http_postdata('https://api.weixin.qq.com/pay/genprepay?access_token=' . $access_token, $post_data);
        $prepay_result = json_decode($prepay_result, true);
        if($prepay_result['errcode']) {
            return array('error' => '支付失败code:1002');
        }
        $prepayid = $prepay_result['prepayid'];

        // 生成正式支付参数
        $data = array();
        $data['appid'] = $this->payment_config['wxpay_appid'];
        $data['noncestr'] = $noncestr;
        $data['package'] = 'Sign=WXPay';
        $data['partnerid'] = $this->payment_config['wxpay_partnerid'];
        $data['prepayid'] = $prepayid;
        $data['timestamp'] = $timestamp;
        $sign = $this->_get_wx_signature($data);
        $data['sign'] = $sign;
        return $data;
    }

    /**
     * 获取微信access_token
     */
    private function _get_wx_access_token() {
        // 尝试读取缓存的access_token
        $access_token = rkcache('wx_access_token');
        if($access_token) {
            $access_token = unserialize($access_token);
            // 如果access_token未过期直接返回缓存的access_token
            if($access_token['time'] > TIMESTAMP) {
                return $access_token['token'];
            }
        }

        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';
        $url = sprintf($url, $this->payment_config['wxpay_appid'], $this->payment_config['wxpay_appsecret']);
        $re = http_get($url);
        $result = json_decode($re, true);
        if($result['errcode']) {
            return '';
        }

        // 缓存获取的access_token
        $access_token = array();
        $access_token['token'] = $result['access_token'];
        $access_token['time'] = TIMESTAMP + $result['expires_in'];
        wkcache('wx_access_token', serialize($access_token));

        return $result['access_token'];
    }

    /**
     * 获取package
     */
    private function _get_wx_package($param) {
        $array = array();
        $array['bank_type'] = 'WX';
        $array['body'] = $param['subject'];
        $array['fee_type'] = 1;
        $array['input_charset'] = 'UTF-8';
        $array['notify_url'] = MOBILE_SITE_URL . '/api/payment/wxpay/notify_url.php';
        $array['out_trade_no'] = $param['pay_sn'];
        $array['partner'] = $this->payment_config['wxpay_partnerid'];
        $array['total_fee'] = $param['amount'];
        $array['spbill_create_ip'] = get_server_ip();

        ksort($array);

        $string = '';
        $string_encode = '';
        foreach ($array as $key => $val) {
            $string .= $key . '=' . $val . '&';
            $string_encode .= $key . '=' . urlencode($val). '&';
        }

        $stringSignTemp = $string . 'key=' . $this->payment_config['wxpay_partnerkey'];
        $signValue = md5($stringSignTemp);
        $signValue = strtoupper($signValue);

        $wx_package = $string_encode . 'sign=' . $signValue;
        return $wx_package;
    }

    /**
     * 获取微信支付签名
     */
    private function _get_wx_signature($param) {
        $param['appkey'] = $this->payment_config['wxpay_appkey'];

        $string = '';

        ksort($param);
        foreach ($param as $key => $value) {
            $string .= $key . '=' . $value . '&';
        }
        $string = rtrim($string, '&');

        $sign = sha1($string);

        return $sign;
    }

    /**
     * 微信APP订单支付
     */
    public function wx_app_pay3Op() {
        $pay_sn = $_POST['pay_sn'];

        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $param = array();
        $param['pay_sn'] = $pay_sn;
        $param['subject'] = $pay_info['data']['subject'];
        $param['amount'] = $pay_info['data']['api_pay_amount'] * 100;

        $data = $this->_get_wx_pay_info3($param);
        if(isset($data['error'])) {
            output_error($data['error']);
        }
        output_data($data);
    }

    /**
     * 微信APP虚拟订单支付
     */
    public function wx_app_vr_pay3Op() {
        $pay_sn = $_POST['pay_sn'];

        $pay_info = $this->_get_vr_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $param = array();
        $param['pay_sn'] = $pay_sn;
        $param['subject'] = $pay_info['data']['subject'];
        $param['amount'] = $pay_info['data']['api_pay_amount'] * 100;

        $data = $this->_get_wx_pay_info3($param);
        if(isset($data['error'])) {
            output_error($data['error']);
        }
        output_data($data);
   }

    /**
     * 获取支付参数
     */
    private function _get_wx_pay_info3($pay_param) {
        //https://pay.weixin.qq.com/wiki/doc/api/app/app.php?chapter=9_1
        $noncestr = md5(rand());

        $param = array();
        $param['appid'] = $this->payment_config['wxpay_appid'];
        $param['mch_id'] = $this->payment_config['wxpay_partnerid'];
        $param['nonce_str'] = $noncestr;
        $param['body'] = $pay_param['subject'];
        $param['out_trade_no'] = $pay_param['pay_sn'];  //商户订单号
        $param['total_fee'] = $pay_param['amount'];
        $param['spbill_create_ip'] = get_server_ip();
        $param['notify_url'] = MOBILE_SITE_URL . '/api/payment/wxpay3/notify_url.php';
        $param['trade_type'] = 'APP';

        $sign = $this->_get_wx_pay_sign3($param);
        $param['sign'] = $sign;

        $post_data = '<xml>';
        foreach ($param as $key => $value) {
            $post_data .= '<' . $key .'>' . $value . '</' . $key . '>';
        }
        $post_data .= '</xml>';

        $prepay_result = http_postdata('https://api.mch.weixin.qq.com/pay/unifiedorder', $post_data);
        $prepay_result = simplexml_load_string($prepay_result);
        if($prepay_result->return_code != 'SUCCESS') {
            return array('error' => '支付失败code:1002');
        }

        // 生成正式支付参数
        $data = array();
        $data['appid'] = $this->payment_config['wxpay_appid'];
        $data['noncestr'] = $noncestr;
        $data['package'] = 'prepay_id=' . $prepay_result->prepay_id;
        $data['package'] = 'Sign=WXpay';
        $data['partnerid'] = $this->payment_config['wxpay_partnerid'];
        $data['prepayid'] = (string)$prepay_result->prepay_id;
        $data['timestamp'] = TIMESTAMP;
        $sign = $this->_get_wx_pay_sign3($data);
        $data['sign'] = $sign;
        return $data;
    }

    private function _get_wx_pay_sign3($param) {
        ksort($param);
        foreach ($param as $key => $val) {
            $string .= $key . '=' . $val . '&';
        }
        $string .= 'key=' . $this->payment_config['wxpay_partnerkey'];
        return strtoupper(md5($string));
    }

    /**
     * ccb银联支付
     */
    public function ccb_ylOp() {
        $pay_sn = $_POST['pay_sn'];

        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $param = array();
        $param['pay_sn'] = $pay_sn;
        $param['subject'] = $pay_info['data']['subject'];
        $param['amount'] = $pay_info['data']['api_pay_amount'];

        $data = $this->_get_ccb_yl($param);
        if(isset($data['error'])) {
            output_error($data['error']);
        }
        output_data($data);
    }

    /**
     * 获取支付参数
     */
    private function _get_ccb_yl($pay_param) {
        $MERCHANTID = $this->payment_config['MERCHANTID'];  //商户代码
        $POSID= $this->payment_config['POSID'];              //商户柜台代码
        $BRANCHID= $this->payment_config['BRANCHID'];           //分行代码
        $ORDERID= $pay_param['pay_sn'];     //定单号CHAR(30)
        $PAYMENT= $pay_param['amount'];                  //付款金额NUMBER(16,2)
        $CURCODE='01';                   //币种 CHAR(2)
        $REMARK1= substr(md5($BRANCHID.$PAYMENT),0,20);  //备注1 CHAR(30)
        $REMARK2='';                     //备注2 CHAR(30)
        $TXCODE='520100';                //交易码 CHAR(6)
        //以下为防钓鱼专用字段
        $TYPE='1';                       //接口类型
        $PUB= $this->payment_config['PUB'];                       //公钥后30位
        $GATEWAY= $this->payment_config['GATEWAY'];    //网关类型
        $CLIENTIP = $_SERVER['REMOTE_ADDR'];

        $REGINFO = 'TaiHuaSuan';     //客户注册信息
        //$PROINFO = 'www.htths.com';     //商品信息
        $PROINFO = $pay_param['subject'];     //商品信息
        $REFERER='';	 //商户URL

        $datastr = 'MERCHANTID='.$MERCHANTID.'&POSID='.$POSID.'&BRANCHID='.$BRANCHID.'&ORDERID='.$ORDERID.'&PAYMENT='.$PAYMENT.'&CURCODE='.$CURCODE.'&TXCODE='.$TXCODE.'&REMARK1='.$REMARK1.'&REMARK2='.$REMARK2.'&TYPE='.$TYPE.'&PUB='.$PUB.'&GATEWAY='.$GATEWAY.'&CLIENTIP='.$CLIENTIP.'&REGINFO='.$REGINFO.'&PROINFO='.$PROINFO.'&REFERER='.$REFERER;
        $MAC = md5($datastr);
        $purl = 'https://ibsbjstar.ccb.com.cn/app/ccbMain?'.$datastr.'&MAC='.$MAC;

        return $purl;
    }

    public function alipay_mbOp(){
        $pay_sn = $_POST['pay_sn'];
        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $inc_file_base = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS;
        require_once($inc_file_base.'alipay.config.php');
        require_once($inc_file_base.'lib/alipay_notify.class.php');
        require_once($inc_file_base.'lib/alipay_rsa.function.php');
        require_once($inc_file_base.'lib/alipay_core.function.php');

        //确认PID和接口名称是否匹配。
        date_default_timezone_set("PRC");

        //将post接收到的数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串。
        $params = array(
            //以下信息非特殊情况不需要改动
            'partner' => $alipay_config['partner'],				  //合作者身份ID
            'seller_id' => $this->payment_config["mb_alipay_account"],	              //卖家支付宝账号
            'out_trade_no' => $pay_info['data']["pay_sn"],				  //商户网站唯一订单号
            'subject' =>  '太划算' . $pay_info['data']['order_list'][0]['order_sn'],  //商品名称
            'body' => '太划算商城',		          //商品详情
            'total_fee' => $pay_info['data']['api_pay_amount'],	              //总金额yuan
            'notify_url' => MOBILE_SITE_URL.'/api/payment/alipay_mb/notify_url.php',				  //交易子类
            'service' => $alipay_config['service'],                 //接口名称
            'payment_type' => '1',	  //支付类型。默认值为：1（商品购买）。
            '_input_charset' => $alipay_config['input_charset']				      //交易类型
        );
        $data=createLinkstring($params);

        //打印待签名字符串。工程目录下的log文件夹中的log.txt。
        logResult($data);

        //将待签名字符串使用私钥签名,且做urlencode. 注意：请求到支付宝只需要做一次urlencode.
        $rsa_sign=urlencode(rsaSign($data, $alipay_config['private_key']));

        //把签名得到的sign和签名类型sign_type拼接在待签名字符串后面。
        $data = $data.'&sign='.'"'.$rsa_sign.'"'.'&sign_type='.'"'.$alipay_config['sign_type'].'"';

        //返回给客户端,建议在客户端使用私钥对应的公钥做一次验签，保证不是他人传输。
        output_data(array('orderInfo'=>$data));
        //echo $data;


    }
    public function unionpayOp(){
        $pay_sn = $_POST['pay_sn'];
        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.'sdk/acp_service.php';
        if(!is_file($inc_file)){
            output_error('支付接口不存在');
        }
        require($inc_file);
        $params = array(
            //以下信息非特殊情况不需要改动
            'version' => '5.0.0',                 //版本号
            'encoding' => 'utf-8',				  //编码方式
            'txnType' => '01',				      //交易类型
            'txnSubType' => '01',				  //交易子类
            'bizType' => '000201',				  //业务类型
            'frontUrl' =>  com\unionpay\acp\sdk\SDK_FRONT_NOTIFY_URL,  //前台通知地址
            'backUrl' => com\unionpay\acp\sdk\SDK_BACK_NOTIFY_URL,	  //后台通知地址
            'signMethod' => '01',	              //签名方法
            'channelType' => '08',	              //渠道类型，07-PC，08-手机
            'accessType' => '0',		          //接入类型
            'currencyCode' => '156',	          //交易币种，境内商户固定156

            //TODO 以下信息需要填写
            'merId' => $this->payment_config["merId"],		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
            'orderId' => $pay_info['data']["pay_sn"],	//商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
            'txnTime' => date("YmdHis"),	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
            'txnAmt' => $pay_info['data']['api_pay_amount'] * 100,	//交易金额，单位分，此处默认取demo演示页面传递的参数
// 		'reqReserved' =>'透传信息',        //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据

            //TODO 其他特殊用法请查看 pages/api_05_app/special_use_purchase.php
        );

        com\unionpay\acp\sdk\AcpService::sign ( $params ); // 签名
        $url = com\unionpay\acp\sdk\SDK_App_Request_Url;

        $result_arr = com\unionpay\acp\sdk\AcpService::post ($params,$url);
        if(count($result_arr)<=0) { //没收到200应答的情况
            output_error('连接银联服务器失败');
            //printResult ($url, $params, "" );
            //return;
        }

        if (!com\unionpay\acp\sdk\AcpService::validate ($result_arr) ){
            output_error('应答报文验签失败');

        }
        if ($result_arr["respCode"] == "00"){
            //成功
            //output_data(array('tn'=> $result_arr["tn"], 'params'=>$params));
            output_data(array('tn'=> $result_arr["tn"]));
        } else {
            //其他应答码做以失败处理
            output_error($result_arr["respMsg"]);
        }

    }

    public function wxjspayOp(){
        $pay_sn = $_GET['pay_sn'];
        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.'lib/WxPay.Api.php';
        if(!is_file($inc_file)){
            output_error('支付接口不存在');
        }
        require($inc_file);

        $inc_file2 = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.'WxPay.JsApiPay.php';
        if(!is_file($inc_file2)){
            output_error('支付接口不存在');
        }
        require($inc_file2);
        
        //①、获取用户openid
        $tools = new JsApiPay();
        //$openId = $tools->GetOpenid();
        $openId = 'o7Tftjm9K2sZWnfTPgLOsPJ7-m_o';
        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody("test");
        $input->SetAttach("test");
        $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder($input);

        $jsApiParameters = $tools->GetJsApiParameters($order);

        $html =  <<<EOB
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
<title>微信安全支付</title>
</head>
<body>
正在加载…
<script type="text/javascript">
function jsApiCall() {
console.log({$jsApiParameters});
    WeixinJSBridge.invoke(
        'getBrandWCPayRequest',
        {$jsApiParameters},
        function(res) {
        console.log(res);
            var h;
            if (res && res.err_msg == "get_brand_wcpay_request:ok") {
                // success;
                h = 'http://www1.htths.com';
            } else {
                // fail;
                alert(res && res.err_msg);
                h = 'http://m.htths.com';
            }
            location.href = h;
        }
    );
}
window.onload = function() {
    if (typeof WeixinJSBridge == "undefined") {
        if (document.addEventListener) {
            document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
        } else if (document.attachEvent) {
            document.attachEvent('WeixinJSBridgeReady', jsApiCall);
            document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
        }
    } else {
        jsApiCall();
    }
}
</script>
</body>
</html>
EOB;


        try {
            //exit('222');
            echo $html;
        } catch (Exception $ex) {
            if (C('debug')) {
                header('Content-type: text/plain; charset=utf-8');
                echo $ex, PHP_EOL;
            } else {
                Tpl::output('msg', $ex->getMessage());
                Tpl::showpage('payment_result');
            }
        }
        exit;

    }

    public function hftxOp(){
      $pay_sn = $_POST['pay_sn'];
      $pay_info = $this->_get_real_order_info($pay_sn);
      if(isset($pay_info['error'])) {
        output_error($pay_info['error']);
      }
//      output_data($pay_info);
//      $merchantAcctId = "1000526000101";   // 测试用
      $merchantAcctId = "1004261707001";
      $terminalId = "yd001";
      //编码方式，1代表 UTF-8; 2 代表 GBK; 3代表 GB2312 默认为1,该参数必填。
      $inputCharset = "1";
      //接收支付结果的页面地址，该参数一般置为空即可。
      $pageUrl = "http://api.htths.com/api/payment/hftx/receivePg.php";  // todo
      //服务器接收支付结果的后台地址，该参数务必填写，不能为空。
      $bgUrl = "http://api.htths.com/api/payment/hftx/notify_url.php"; // todo
      //网关版本，固定值：hw1.0,该参数必填。
      $version =  "3.0"; //  todo 旧版3.0
      //语言种类，1代表中文显示，2代表英文显示。默认为1,该参数必填。
      $language =  "1";
      //签名类型,该值为4，代表PKI加密方式,该参数必填。
      $signType =  "4";
      //支付人姓名,可以为空。
      $payerName= $pay_info['data']['buyer_name'];
      //支付人联系类型，1 代表电子邮件方式；2 代表手机联系方式。可以为空。
      $payerContactType =  "2";
      //支付人联系方式，与payerContactType设置对应，payerContactType为1，则填写邮箱地址；payerContactType为2，则填写手机号码。可以为空。
      $payerContact =  "";
      $payerIdentityCard =  "";  // todo 身份证
      $mobileNumber =  $pay_info['data']['buyer_phone'];
      $cardNumber =  "";  // 卡号
      //必填
      $customerId =  $pay_info['data']['buyer_id'];

      //商户订单号，以下采用时间来定义订单号，商户可以根据自己订单号的定义规则来定义该值，不能为空。
      $orderId = ''.$pay_info['data']['order_list'][0]['order_sn'];
      //询盘流水号
      $inquireTrxNo = "";
      $orderCurrency = "CNY";  // todo ?  CNY
      $settlementCurrency = "CNY";  // todo ?  CNY
      //订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试。该参数必填。
      $orderAmount = ''. $pay_info['data']['api_pay_amount'] * 100;
      //订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101，不能为空。
      $orderTime = date("YmdHis");
      //商品名称，可以为空。
      $productName= $pay_info['data']['pay_sn'];  // todo 4.0文档显示不可空
      //商品数量，可以为空。
      $productNum = "";
      //商品代码，可以为空。
      $productId = "100020";  // todo
      //商品描述，可以为空。
      $productDesc = "T";
      //扩展字段1，商户可以传递自己需要的参数，支付完Atimes会原值返回，可以为空。
      $ext1 = $pay_info['data']['pay_sn'];
      //扩展自段2，商户可以传递自己需要的参数，支付完Atimes会原值返回，可以为空。
      $ext2 = "ext2";
      //支付方式，30，必填。
      $payType = "30";
      //银行代码，如果payType为00，该值可以为空；如果payType为10，该值必须填写，具体请参考银行列表。
      $bankId = "";
      $refererUrl ="";
      $customerIp = getIp();
      $orderTimeout = "";
      $divDetails = "";
      //同一订单禁止重复提交标志，实物购物车填1，虚拟产品用0。1代表只能提交一次，0代表在支付不成功情况下可以再提交。可为空。
      $redoFlag = "1";
      // signMsg 签名字符串 不可空，生成加密签名串
      $pid = "";

      function param_ck_null($kq_va,$kq_na){
        if($kq_va == ""){
          $kq_va="";
        }else{
          return $kq_va=$kq_na.'='.$kq_va.'&';
        }
      }
      $kq_all_para=param_ck_null($inputCharset,'inputCharset');
      $kq_all_para.=param_ck_null($pageUrl,"pageUrl");
      $kq_all_para.=param_ck_null($bgUrl,'bgUrl');
      $kq_all_para.=param_ck_null($version,'version');
      $kq_all_para.=param_ck_null($language,'language');
      $kq_all_para.=param_ck_null($signType,'signType');
      $kq_all_para.=param_ck_null($merchantAcctId,'merchantAcctId');
      $kq_all_para.=param_ck_null($terminalId,'terminalId');
      $kq_all_para.=param_ck_null($payerName,'payerName');
      $kq_all_para.=param_ck_null($payerContactType,'payerContactType');
      $kq_all_para.=param_ck_null($payerContact,'payerContact');
      $kq_all_para.=param_ck_null($payerIdentityCard,'payerIdentityCard');
      $kq_all_para.=param_ck_null($mobileNumber,'mobileNumber');
      $kq_all_para.=param_ck_null($cardNumber,'cardNumber');
      $kq_all_para.=param_ck_null($customerId,'customerId');
      $kq_all_para.=param_ck_null($orderId,'orderId');
      $kq_all_para.=param_ck_null($inquireTrxNo,'inquireTrxNo');
      $kq_all_para.=param_ck_null($orderCurrency,'orderCurrency');
      $kq_all_para.=param_ck_null($settlementCurrency,'settlementCurrency');
      $kq_all_para.=param_ck_null($orderAmount,'orderAmount');
      $kq_all_para.=param_ck_null($orderTime,'orderTime');
      $kq_all_para.=param_ck_null($productName,'productName');
      $kq_all_para.=param_ck_null($productNum,'productNum');
      $kq_all_para.=param_ck_null($productId,'productId');
      $kq_all_para.=param_ck_null($productDesc,'productDesc');
      $kq_all_para.=param_ck_null($ext1,'ext1');
      $kq_all_para.=param_ck_null($ext2,'ext2');
      $kq_all_para.=param_ck_null($payType,'payType');
      $kq_all_para.=param_ck_null($bankId,'bankId');
      $kq_all_para.=param_ck_null($refererUrl,'refererUrl');
      $kq_all_para.=param_ck_null($customerIp,'customerIp');
      $kq_all_para.=param_ck_null($orderTimeout,'orderTimeout');
      $kq_all_para.=param_ck_null($divDetails,'divDetails');
      $kq_all_para.=param_ck_null($redoFlag,'redoFlag');


      $kq_all_para=substr($kq_all_para,0,strlen($kq_all_para)-1);
//      output_data(array('tn'=> $kq_all_para)) ;

      /////////////  RSA 签名计算 ///////// 开始 //
      $fp = fopen(BASE_PATH.DS.'api'.DS.'payment'.DS.'hftx'.DS."cer".DS."10042617070.pem", "r");

      $priv_key = fread($fp, filesize(BASE_PATH.DS.'api'.DS.'payment'.DS.'hftx'.DS."cer".DS."10042617070.pem"));
      fclose($fp);
      $pkeyid = openssl_get_privatekey($priv_key);

      // compute signature
      openssl_sign($kq_all_para, $signMsg, $pkeyid,OPENSSL_ALGO_SHA1);

      // free the key from memory
      openssl_free_key($pkeyid);

      $signMsg = base64_encode($signMsg);

      /////////////  RSA 签名计算 ///////// 结束 //
//      output_data(array('tn'=> $signMsg)) ;

      /////////////  后台置单 ///////// 开始 //
      $post_data = array (
        "inputCharset" => $inputCharset,
        "pageUrl" => $pageUrl,
        "bgUrl" => $bgUrl,
        "version" => $version,
        "language" => $language,
        "signType" => $signType,
        "merchantAcctId" => $merchantAcctId,
        "terminalId" => $terminalId,
        "payerName" => $payerName,
        "payerContactType" => $payerContactType,
        "payerContact" => $payerContact,
        "payerIdentityCard" => $payerIdentityCard,
        "mobileNumber" => $mobileNumber,
        "cardNumber" => $cardNumber,
        "customerId" => $customerId,
        "orderId" => $orderId,
        "inquireTrxNo" => $inquireTrxNo,
        "orderCurrency" => $orderCurrency,
        "settlementCurrency" => $settlementCurrency,
        "orderAmount" => $orderAmount,
        "orderTime" => $orderTime,
        "productName" => $productName,
        "productNum" => $productNum,
        "productId" => $productId,
        "productDesc" => $productDesc,
        "ext1" => $ext1,
        "ext2" => $ext2,
        "payType" => $payType,
        "bankId" => $bankId,
        "refererUrl" => $refererUrl,
        "customerIp" => $customerIp,
        "orderTimeout" => $orderTimeout,
        "divDetails" => $divDetails,
        "redoFlag" => $redoFlag,
        "signMsg" => $signMsg
      );

//      $url = 'https://mertest.chinapnr.com/pay/recvquickpay.htm';  // 测试环境
      $url = 'https://global.chinapnr.com/pay/recvquickpay.htm';
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_HEADER, 0);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);// 设置 POST 参数
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//不验证证书
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);//不验证证书
      $respData = curl_exec($curl);
      $respUrl = "";
      curl_close($curl);

      /////////////  后台置单 ///////// 结束//
      if (strpos($respData,"errCode")) {
        echo $respData;
      }else{
        $respUrl = $respData;
      }

      output_data(array('respUrl'=> $respUrl)) ;
    }

}

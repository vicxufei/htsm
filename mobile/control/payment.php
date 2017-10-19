<?php
/**
 * 支付回调
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

class paymentControl extends mobileHomeControl{

    private $payment_code;

    public function __construct() {
        parent::__construct();

        $this->payment_code = $_GET['payment_code'];
    }

    public function testOp() {
        file_put_contents('file_put_contents.txt',json_encode($_POST).PHP_EOL,FILE_APPEND);
    }

    /**
     * 支付回调
     */
    public function returnOp() {
        unset($_GET['act']);
        unset($_GET['op']);
        unset($_GET['payment_code']);

        $payment_api = $this->_get_payment_api();

        $payment_config = $this->_get_payment_config();

        $callback_info = $payment_api->getReturnInfo($payment_config);

        if($callback_info) {
            //验证成功
            $result = $this->_update_order($callback_info['out_trade_no'], $callback_info['trade_no']);
            if($result['state']) {
                //Tpl::output('result', 'success');
                //Tpl::output('message', '支付成功');
                header("Location: http://m.htths.com");
                //确保重定向后，后续代码不会被执行
                exit;
            } else {
                //Tpl::output('result', 'fail');
                //Tpl::output('message', '支付失败');
                header("Location: http://m.htths.com");
                //确保重定向后，后续代码不会被执行
                exit;
            }
        } else {
            //验证失败
            //Tpl::output('result', 'fail');
            //Tpl::output('message', '支付失败');
            header("Location: http://m.htths.com");
            //确保重定向后，后续代码不会被执行
            exit;
        }

        //Tpl::showpage('payment_message');
    }

    /**
     * 支付提醒
     */
    public function notifyOp() {

        // wxpay_jsapi
        if ($this->payment_code == 'wxpay_jsapi') {
            $api = $this->_get_payment_api();
            $params = $this->_get_payment_config();
            $api->setConfigs($params);

            list($result, $output) = $api->notify();

            file_put_contents('wxpay_jsapi.txt',json_encode($result),FILE_APPEND);
            if ($result) {
                $internalSn = $result['out_trade_no'] . '_' . $result['attach'];
                $externalSn = $result['transaction_id'];
                $updateSuccess = $this->_update_order($internalSn, $externalSn, $result['openid']);

                if (!$updateSuccess) {
                    // @todo
                    // 直接退出 等待下次通知
                    exit;
                }
            }

            echo $output;
            exit;
        }

        // 恢复框架编码的post值
        $_POST['notify_data'] = html_entity_decode($_POST['notify_data']);

        $payment_api = $this->_get_payment_api();

        $payment_config = $this->_get_payment_config();

        $callback_info = $payment_api->getNotifyInfo($payment_config);

        if($callback_info) {
            //验证成功
            $result = $this->_update_order($callback_info['out_trade_no'], $callback_info['trade_no'], $callback_info['who_pay']);
            if($result['state']) {
                echo 'success';die;
            }
        }

        //验证失败
        echo "fail";die;
    }

    /**
     * 获取支付接口实例
     */
    private function _get_payment_api() {
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';

        if(is_file($inc_file)) {
            require($inc_file);
        }

        $payment_api = new $this->payment_code();

        return $payment_api;
    }

    /**
     * 获取支付接口信息
     */
    private function _get_payment_config() {
        $model_mb_payment = Model('mb_payment');

        //读取接口配置信息
        $condition = array();
        if($this->payment_code == 'wxpay3') {
            $condition['payment_code'] = 'wxpay3';
        } else {
            $condition['payment_code'] = $this->payment_code;
        }
        $payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);

        return $payment_info['payment_config'];
    }

    /**
     * 更新订单状态
     */
    private function _update_order($out_trade_no, $trade_no, $who_pay = '') {
        $model_order = Model('order');
        $logic_payment = Logic('payment');

        $tmp = explode('_', $out_trade_no);
        $out_trade_no = $tmp[0];
        if (!empty($tmp[1])) {
            $order_type = $tmp[1];
        } else {
            $order_pay_info = Model('order')->getOrderPayInfo(array('pay_sn'=> $out_trade_no));
            if(empty($order_pay_info)){
                $order_type = 'v';
            } else {
                $order_type = 'r';
            }
        }

        // wxpay_jsapi
        $paymentCode = $this->payment_code;
        if ($paymentCode == 'wxpay_jsapi') {
            $paymentCode = 'wx_jsapi';
        } elseif ($paymentCode == 'wxpay3') {
            $paymentCode = 'wxpay';
        }

        if ($order_type == 'r') {
            $result = $logic_payment->getRealOrderInfo($out_trade_no);
            if (intval($result['data']['api_pay_state'])) {
                return array('state'=>true);
            }
            $order_list = $result['data']['order_list'];
            $result = $logic_payment->updateRealOrder($out_trade_no, $paymentCode, $order_list, $trade_no, $who_pay);

            $api_pay_amount = 0;
            if (!empty($order_list)) {
                foreach ($order_list as $order_info) {
                    $api_pay_amount += $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
                }
            }
            $log_buyer_id = $order_list[0]['buyer_id'];
            $log_buyer_name = $order_list[0]['buyer_name'];
            $log_desc = '实物订单使用'.orderPaymentName($paymentCode).'成功支付，支付单号：'.$out_trade_no;

        } elseif ($order_type == 'v') {
            $result = $logic_payment->getVrOrderInfo($out_trade_no);
            $order_info = $result['data'];
            if (!in_array($result['data']['order_state'],array(ORDER_STATE_NEW,ORDER_STATE_CANCEL))) {
                return array('state'=>true);
            }
            $result = $logic_payment->updateVrOrder($out_trade_no, $paymentCode, $result['data'], $trade_no);

            $api_pay_amount = $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
            $log_buyer_id = $order_info['buyer_id'];
            $log_buyer_name = $order_info['buyer_name'];
            $log_desc = '虚拟订单使用'.orderPaymentName($paymentCode).'成功支付，支付单号：'.$out_trade_no;
        }
        if ($result['state']) {
            //记录消费日志
            QueueClient::push('addConsume', array('member_id'=>$log_buyer_id,'member_name'=>$log_buyer_name,
            'consume_amount'=>ncPriceFormat($api_pay_amount),'consume_time'=>TIMESTAMP,'consume_remark'=>$log_desc));
        }

        return $result;
    }

    /**
     * 支付提醒unionpay
     */
    public function notify4unionpayOp() {
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.'sdk/acp_service.php';
        if(!is_file($inc_file)){
            output_error('支付接口不存在');
        }

        require($inc_file);
        $data = '';
        foreach ($_POST as $key=>$value){
            if($key != signature){
                $data .= $key . "=" . $value . "&";
            }
        }
        $data = substr ( $data, 0, count ( $data ) - 2 );


        $result_arr=array(
            'sign' => $_POST['signature'],
            'data' => $data
        );
        $jsongyf = json_encode($result_arr);
        $ret =  com\unionpay\acp\sdk\AcpService::validateAppResponse ($jsongyf);
        if($ret){

            function yf111_log($file,$txt)
            {
                $fp =  fopen($file,'ab+');
                //gmdate('Y-m-d H:i:s', time() + 3600 * 8)
                fwrite($fp,'-----------'.date('Y-m-d H:i:s', time()).'-----------------');
                fwrite($fp,$txt);
                fwrite($fp,"\r\n\r\n\r\n");
                fclose($fp);
            }
            yf111_log('yf-notify4unionpay.txt','验签成功');

            //($_POST['orderId'], $_POST['queryId'] 分别对应为pay_sn 和 tn
            $updateSuccess = $this->_update_order($_POST['orderId'], $_POST['queryId']);

            if (!$updateSuccess) {
                // @todo
                // 直接退出 等待下次通知
                exit;
            }

        }

    }

    public function alipay_appOp(){
        $inc_file_base = BASE_PATH.DS.'api'.DS.'payment'.DS.'alipay_mb'.DS;
        require_once($inc_file_base.'alipay.config.php');
        require_once($inc_file_base.'lib/alipay_notify.class.php');
        require_once($inc_file_base.'lib/alipay_rsa.function.php');
        require_once($inc_file_base.'lib/alipay_core.function.php');


        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_config);
        if($alipayNotify->getResponse($_POST['notify_id']))//判断成功之后使用getResponse方法判断是否是支付宝发来的异步通知。
        {
            if($alipayNotify->getSignVeryfy($_POST, $_POST['sign'])) {//使用支付宝公钥验签

                //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
                //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
                //商户订单号
                $out_trade_no = $_POST['out_trade_no'];

                //支付宝交易号
                $trade_no = $_POST['trade_no'];
//                $who_pay = $_POST['buyer_id'];
                $who_pay = $_POST['buyer_email'];

                //交易状态
                $trade_status = $_POST['trade_status'];

                if($_POST['trade_status'] == 'TRADE_FINISHED') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
                    if($_POST['seller_id'] =='2088421823554211'){
                        $updateSuccess = $this->_update_order($out_trade_no, $trade_no, $who_pay);
                        if($updateSuccess['state']){
                            echo "success";		//请不要修改或删除
                        }
                    }
                }
                else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
                    if($_POST['seller_id'] =='2088421823554211'){
                        $updateSuccess = $this->_update_order($out_trade_no, $trade_no, $who_pay);
                        if($updateSuccess['state']){
                            echo "success";		//请不要修改或删除
                        }
                    }
                }
                //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            }
//            else //验证签名失败
//            {
//                echo "sign fail";
//            }
        }
//        else //验证是否来自支付宝的通知失败
//        {
//            echo "response fail";
//        }

    }


    public function hftxOp() {
      $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.'hftx'.DS.'receiveBg.php';
      if(is_file($inc_file)) {
        require($inc_file);
      }

      if ($ok == 1) {
        switch($_REQUEST[payResult]){
          case '10':
            //此处做商户逻辑处理
            $rtnOK=1;
            $msg="deal Success, check sign success";

//            echo '$_POST[\'ext1\']'. $_POST['ext1'];
//            echo '$_POST[\'orderId\']'. $_POST['orderId'];
            $updateSuccess = $this->_update_order($_POST['ext1'], $_POST['orderId']);
            if (!$updateSuccess) {
              // @todo
              // 直接退出 等待下次通知
              exit;
            }

            break;
          default:
            $rtnOK=0;
            $msg="deal failed, check sign success";
            break;

        }

      }else{
        $rtnOK=0;
        $msg="check sign failed";
      }
//      echo 'ddd'.$msg;
    }

}

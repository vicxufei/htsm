<?php
/**
 * 微信支付接口类
 *
 *
 * @copyright  Copyright (c) 2007-2015 ShopNC Inc. (http://www.shopnc.net)
 * @license    http://www.shopnc.net
 * @link       http://www.shopnc.net
 * @since      File available since Release v1.1
 */
defined('ByShopWWI') or exit('Access Invalid!');

/**
 * @todo TEST 传递的URL参数是否冲突
 * @todo 后续接收通知
 * @todo 后续页面显示 以及异步结果提示
 */
class wxpay3
{

    /**
     * 获取notify信息
     */
    public function getNotifyInfo($payment_config) {
        $result = $this->_verify3($payment_config);

        if ($result) {
            return array(
                //商户订单号
                'out_trade_no' => $result['out_trade_no'],
                //微信支付交易号
                'trade_no' => $result['transaction_id'],
                //openid
                'who_pay' => $result['openid'],
            );
        }

        return false;
    }

    /**
     * 验证返回信息(v3)
     */
    private function _verify3($payment_config) {
        if(empty($payment_config)) {
            return false;
        }

        $xml = file_get_contents("php://input");
        $array = simplexml_load_string($xml);
        $param = array();
        foreach ($array as $key => $value) {
            $param[$key] = (string)$value;
        }

        ksort($param);
//        file_put_contents('wxpay3.txt',json_encode($param),FILE_APPEND);

        $hash_temp = '';
        foreach ($param as $key => $value) {
            if($key != 'sign') {
                $hash_temp .= $key . '=' . $value . '&';
            }
        }

        $hash_temp .= 'key' . '=' . $payment_config['wxpay_partnerkey'];

        $hash = strtoupper(md5($hash_temp));

        if($hash == $param['sign']) {
            return array(
                'out_trade_no' => $param['out_trade_no'],
                'transaction_id' => $param['transaction_id'],
                'openid' => $param['openid'],
            );
        } else {
            return false;
        }
    }
}

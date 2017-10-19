<?php
/**
 * Created by PhpStorm.
 * User: yefeng
 * Date: 16/4/12
 * Time: 下午10:04
 */
//phpinfo();
//echo date('y',time()) % 9+1;
//echo date('ymdHi',time());

//$payment_config = array(
//    'MERCHANTID' => '105320553110168', //商户代码
//    'POSID' => '495318273',  //商户柜台代码
//    'BRANCHID' => '322000000', //分行代码
//    'PUB' => '854580f60f57c2a88750e23d020111', //公钥后30位
//    'GATEWAY' => 'UnionPay', //网关类型
//);

//$payment_config = array(
//    'merId' => '777290058110048', //商户代码
//    'merId' => '898320553990313', //商户代码
//);

//wxjsapi
$payment_config = array(
    'appId' => 'wxeda52f5413d2e571',
    'appSecret' => '300f96311731a9705afbcbc1a60759bc',
    'partnerId' => '1226353802',
    'apiKey' => 'TCHTSMlwh19781102197811021978110',
);
echo serialize($payment_config);

<?php
/**
 * 微信支付通知地址
 *
 * 
 * @copyright  Copyright (c) 2007-2015 ShopNC Inc. (http://www.shopnc.net)
 * @license    http://www.shopnc.net
 * @link       http://www.shopnc.net
 * @since      File available since Release v1.1
 */
$_GET['act']	= 'payment';
$_GET['op']		= 'notify4unionpay';
$_GET['payment_code'] = 'unionpay';

//$_POST = array (
//    'accNo' => '6226********0048',
//    'accessType' => '0',
//    'bizType' => '000201',
//    'certId' => '68759585097',
//    'currencyCode' => '156',
//    'encoding' => 'utf-8',
//    'merId' => '777290058110048',
//    'orderId' => '310522839936712445',
//    'queryId' => '201607271050188813288',  //tn
//    'respCode' => '00',
//    'respMsg' => 'Success!',
//    'settleAmt' => '1',
//    'settleCurrencyCode' => '156',
//    'settleDate' => '0726',
//    'signMethod' => '01',
//    'traceNo' => '881328',
//    'traceTime' => '0727105018',
//    'txnAmt' => '1',
//    'txnSubType' => '01',
//    'txnTime' => '20160727105018',
//    'txnType' => '01',
//    'version' => '5.0.0',
//    'signature' => 'FqKrtLZTdGGMCkDVTWQE6G76IqGl4RK+8+uMh9+FwpildQE/bkYMeMk45Z5m37tw0SzS/zGqoIZmatO33gldfp8wv/AOVC29AJBbPtOW4s6OxzJgiwULcTQWL9ENqo43RSLRkaNMJDF1iagapt7MR0TtsuZbO9vI3ni/cHrYfuLU3DMREJfGUstVR3fcbv/iAkmawtOHjAv0UTM4o8YxOD3uB6zsbW1V0yUNd4he3sRwZLNraE1518uAyBbwc2i8uODZhdaQFZ2Zor/MpfLuVYBK41QuB+oHH3Ard3Tp+2kIY0KHZg/OPqhOGSB+UTKGHZ/1N0RZXOZ0qtYOG9GYZw==',
//);

//    function yf_log($file,$txt)
//    {
//        $fp =  fopen($file,'ab+');
//        fwrite($fp,'-----------'.date('Y-m-d H:i:s', time()).'-----------------');
//        fwrite($fp,$txt);
//        fwrite($fp,"\r\n\r\n\r\n");
//        fclose($fp);
//    }
//
//yf_log('yf-log.txt',var_export($_POST,true));
require_once(dirname(__FILE__).'/../../../index.php');

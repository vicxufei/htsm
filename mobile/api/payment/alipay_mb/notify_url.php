<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：1.0
 * 日期：2016-06-06
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */
//file_put_contents('alipay_mb.txt',var_export($_POST,true),FILE_APPEND);

$_GET['act'] = 'payment';
$_GET['op']	= 'alipay_app';
$_GET['payment_code']	= 'alipay_app';

//$_POST = array (
//    'discount' => '0.00',
//    'payment_type' => '1',
//    'subject' => '太划算1609071246236101',
//    'trade_no' => '2016090721001004590224111473',
//    'buyer_email' => 'gyf_1128@yahoo.com.cn',
//    'gmt_create' => '2016-09-07 13:17:01',
//    'notify_type' => 'trade_status_sync',
//    'quantity' => '1',
//    'out_trade_no' => '410526567588004309',
//    'seller_id' => '2088421823554211',
//    'notify_time' => '2016-09-07 13:17:02',
//    'body' => '太划算商城',
//    'trade_status' => 'TRADE_SUCCESS',
//    'is_total_fee_adjust' => 'N',
//    'total_fee' => '1.00',
//    'gmt_payment' => '2016-09-07 13:17:02',
//    'seller_email' => '5907026@qq.com',
//    'price' => '1.00',
//    'buyer_id' => '2088002252622595',
//    'notify_id' => '830262c3bb938a511a27aabdcf7a026kjy',
//    'use_coupon' => 'N',
//    'sign_type' => 'RSA',
//    'sign' => 'S2Dy99S9wX27uc6hD1AoeAidiIfkQ4bbFrFmfpzsrhPDeV1lL9DckUpWMtHZKolOmOJnl1VRthk+09rq4xLwwrNtqd9orBxk2YHW9EnWL7oGuZ4qw70woioAadE1LB7KliChTmYVPFr4sGDLcLfWFOJ8Ef8srFW7iWtAmMbO8mo=',
//);
require_once(dirname(__FILE__).'/../../../index.php');

?>
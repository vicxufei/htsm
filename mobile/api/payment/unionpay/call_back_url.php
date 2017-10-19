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
$_GET['op']		= 'notify';
$_GET['payment_code'] = 'unionpay';

function yf_log($file,$txt)
{
    $fp =  fopen($file,'ab+');
    fwrite($fp,'-----------'.date('Y-m-d H:i:s', time()).'-----------------');
    fwrite($fp,$txt);
    fwrite($fp,"\r\n\r\n\r\n");
    fclose($fp);
}

yf_log('yf-callback-log.txt',var_export($_POST,true));

//require_once(dirname(__FILE__).'/../../../index.php');

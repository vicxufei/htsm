<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wxeda52f5413d2e571", "300f96311731a9705afbcbc1a60759bc");
$url = $_POST['url'];
$signPackage = $jssdk->GetSignPackage($url);
//json_encode($signPackage);
//$wx_config = array(
//    'debug' => true,
//    'appId' => $signPackage["appId"],
//    'timestamp' => $signPackage["timestamp"],
//    'nonceStr' => $signPackage["nonceStr"],
//    'signature' => $signPackage["signature"],
////    'jsApiList' => ['onMenuShareTimeline', 'onMenuShareAppMessage']
//);
//$data['code'] = 200;
//$data['datas'] = $wx_config;
header('Content-type: text/plain; charset=utf-8');
echo json_encode($signPackage,true);
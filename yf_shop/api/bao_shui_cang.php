<?php
/**
 * 接收保税仓订单状态
 */
$_GET['act']	= 'bao_shui_cang';
$_GET['op']		= 'order_state';
//function yf_log($file,$txt)
//{
//  $fp =  fopen($file,'ab+');
//  fwrite($fp,'-----------'.date('Y-m-d H:i:s', time()).'-----------------');
//  fwrite($fp,$txt);
//  fwrite($fp,"\r\n\r\n\r\n");
//  fclose($fp);
//}
//yf_log('bao_shui_cang-order_state.txt','hhhggg');
//yf_log('bao_shui_cang-order_state.txt',var_export($_POST,true));
//
//$postStr = file_get_contents('php://input');
//yf_log('bao_shui_cang-order_state.txt',$postStr);

 require_once(dirname(__FILE__).'/../../index.php');
?>
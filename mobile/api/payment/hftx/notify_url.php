<?php
/**
 * 汇付天下
 *
 */
function yf_log($file,$txt)
{
  $fp =  fopen($file,'ab+');
  fwrite($fp,'-----------'.date('Y-m-d H:i:s', time()).'-----------------');
  fwrite($fp,$txt);
  fwrite($fp,"\r\n\r\n\r\n");
  fclose($fp);
}
yf_log('receiveBg.txt',var_export($_REQUEST,true));



$_GET['act']	= 'payment';
$_GET['op']		= 'hftx';
$_GET['payment_code'] = 'hftx';

require_once(dirname(__FILE__).'/../../../index.php');


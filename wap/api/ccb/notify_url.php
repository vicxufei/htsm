<?php
/**
 * Created by PhpStorm.
 * User: yefeng
 * Date: 16/7/25
 * Time: 下午3:14
 */

function ccb_log($file,$txt)
{
    $fp =  fopen($file,'ab+');
    fwrite($fp,'-----------'.date('Y-m-d H:i:s', time()).'-----------------');
    fwrite($fp,$txt);
    fwrite($fp,"\r\n\r\n\r\n");
    fclose($fp);
}

$abc = array(
    'hetuan'=>'www.htths.com'
);
ccb_log('ccbyl-log-notify.txt',var_export($abc,true));

$post_data = file_get_contents('php://input');
ccb_log('ccbyl-log-notify.txt',$post_data);

ccb_log('ccbyl-log-notify.txt',var_export($_POST,true));
echo 'success';



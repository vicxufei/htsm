<?php
/**
 * Created by PhpStorm.
 * User: yefeng
 * Date: 16/7/15
 * Time: 下午12:10
 */

function ccb_log($file,$txt)
{
    $fp =  fopen($file,'ab+');
    fwrite($fp,'-----------'.date('Y-m-d H:i:s', time()).'-----------------');
    fwrite($fp,$txt);
    fwrite($fp,"\r\n\r\n\r\n");
    fclose($fp);
}

$post_data = file_get_contents('php://input');
ccb_log('ccbyl-log-callback.txt',$post_data);

$abc = array(
    'aaa'=>'123',
    'bbb' => '456'
);
ccb_log('ccbyl-log-notify.txt',$abc);
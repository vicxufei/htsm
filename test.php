<?php
/**
 * Created by PhpStorm.
 * User: yefeng
 * Date: 16/5/30
 * Time: 上午8:40
 */
echo 'hello yefng';

function yf_log($file,$txt)
{
    $fp =  fopen($file,'ab+');
    //gmdate('Y-m-d H:i:s', time() + 3600 * 8)
    fwrite($fp,'-----------'.date('Y-m-d H:i:s', time()).'-----------------');
    fwrite($fp,$txt);
    fwrite($fp,"\r\n\r\n\r\n");
    fclose($fp);
}
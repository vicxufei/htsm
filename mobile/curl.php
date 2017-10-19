<?php
/**
 * Created by PhpStorm.
 * User: yefeng
 * Date: 16/4/6
 * Time: 下午12:35
 */
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "http://api.htths.com/index.php?act=brand&op=country");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
//执行并获取HTML文档内容
$output = curl_exec($ch);
//释放curl句柄
curl_close($ch);
//打印获得的数据
print_r($output);
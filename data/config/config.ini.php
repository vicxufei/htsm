<?php

$config = array();
$config['shop_site_url']        = 'http://www.htths.com';
$config['debug']            = true;
//if($_COOKIE['XDEBUG_SESSION'] == 'PHPSTORM'){
// $config['shop_site_url']        = 'http://mall.htths.com/yf_shop';
// $config['debug']            = true;
//}else{
// $config['shop_site_url']        = 'http://www1.htths.com';
// $config['debug']            = false;
//}
//$config['cms_site_url']         = 'http://mall.htths.com/cms';         //deleted
//$config['microshop_site_url']   = 'http://mall.htths.com/microshop';   //deleted
//$config['circle_site_url']      = 'http://mall.htths.com/circle';      //deleted
$config['admin_site_url']       = 'http://htadmin.htths.com';
$config['mobile_site_url']      = 'http://api.htths.com';
$config['wap_site_url']         = 'http://m.htths.com';
//$config['chat_site_url']        = 'http://mall.htths.com/chat';
//$config['node_site_url']        = 'http://mall.htths.com:8090';
//$config['delivery_site_url']    = 'http://mall.htths.com/delivery';
//$config['chain_site_url']       = 'http://mall.htths.com/chain';
$config['chain_site_url']       = 'http://chain.htths.com';
$config['member_site_url']      = 'http://i.htths.com';
//$config['member_site_url']      = 'http://mall.htths.com/member';
$config['upload_site_url']      = 'http://img2.htths.com';
//$config['resource_site_url']    = 'http://www.htths.com/data/resource';
$config['resource_site_url']    = 'http://res.htths.com';
$config['static_url']           = 'http://static.htths.com';
//$config['cms_modules_url']      = 'http://mall.htths.com/admin/modules/cms';
//$config['microshop_modules_url']= 'http://mall.htths.com/admin/modules/microshop';
//$config['circle_modules_url']   = 'http://mall.htths.com/admin/modules/circle';
$config['admin_modules_url']    = 'http://htadmin.htths.com/modules/shop';
$config['mobile_modules_url']   = 'http://htadmin.htths.com/modules/mobile';
$config['version']              = '20151205252S';
$config['setup_date']           = '2015-12-15 10:45:03';
$config['gip']                  = 0;
$config['dbdriver']             = 'mysqli';
$config['tablepre']             = 'ht_';
$config['db']['1']['dbhost']       = 'localhost';
$config['db']['1']['dbport']       = '3306';
$config['db']['1']['dbuser']       = 'yefeng';
$config['db']['1']['dbpwd']        = 'Gyf_112800';
$config['db']['1']['dbname']       = 'htshops2';
$config['db']['1']['dbcharset']    = 'UTF-8';
$config['db']['slave']                  = $config['db']['master'];
$config['session_expire']   = 3600;
$config['lang_type']        = 'zh_cn';
$config['cookie_pre']       = 'ht_';
$config['cache_open'] = true;
$config['redis']['prefix']        = 'nc_';
$config['redis']['master']['port']        = 6379;
$config['redis']['master']['host']        = '127.0.0.1';
$config['redis']['master']['pconnect']    = 0;
$config['redis']['slave']             = array();
$config['fullindexer']['open']      = true;
$config['fullindexer']['appname']   = 'shopnc';
$config['url_model'] = true;
$config['subdomain_suffix'] = '';
$config['session_type'] = 'redis';
$config['session_save_path'] = 'tcp://127.0.0.1:6379';
$config['node_chat'] = false;
//流量记录表数量，为1~10之间的数字，默认为3，数字设置完成后请不要轻易修改，否则可能造成流量统计功能数据错误
$config['flowstat_tablenum'] = 3;
$config['sms']['gwUrl'] = 'http://106.ihuyi.com/webservice/sms.php?method=Submit';
$config['sms']['serialNumber'] = '';
$config['sms']['password'] = '';
$config['sms']['sessionKey'] = '';
$config['queue']['open'] = true;
$config['queue']['host'] = '127.0.0.1';
$config['queue']['port'] = 6379;
//$config['oss']['open'] = false;
//$config['oss']['img_url'] = '';
//$config['oss']['api_url'] = '';
//$config['oss']['bucket'] = '';
//$config['oss']['access_id'] = '';
//$config['oss']['access_key'] = '';
$config['https'] = false;


define('STATIC_URL',$config['static_url']);
return $config;
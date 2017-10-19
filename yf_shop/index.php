<?php
/**
 * 商城板块初始化文件
 * @gyf
 */
define('APP_ID','shop');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
require __DIR__ . '/../yfshop.php';
$wapurl = WAP_SITE_URL;
$agent = $_SERVER['HTTP_USER_AGENT'];

//if(!in_array($_GET['act'],array('seccode','shangjia_login','seller_center','store_goods_add','store_order','statistics_general','store_deliver'))){
//    if(strpos($agent,"comFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS"))
//    {header("Location:$wapurl");}
//}

if(in_array($_GET['act'],array('goods'))){
    if(strpos($agent,"comFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS"))
    {header("Location:$wapurl");}
}

define('APP_SITE_URL',SHOP_SITE_URL);
define('TPL_NAME',TPL_SHOP_NAME);
define('SHOP_RESOURCE_SITE_URL',SHOP_SITE_URL.DS.'resource');
define('SHOP_TEMPLATES_URL',SHOP_SITE_URL.'/templates/'.TPL_NAME);
define('BASE_TPL_PATH',BASE_PATH.'/templates/'.TPL_NAME);
define('HT_TPL_DIR',APP_ID);
if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');
Base::run();

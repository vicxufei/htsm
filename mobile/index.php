<?php
/**
 * 手机接口初始化文件
 * @gyf
 */

function yf_debug($data){
    if($_SERVER["REMOTE_ADDR"] == '49.64.23.199 '){
        output_data($data);
        exit();
    }
}


define('APP_ID','mobile');
define('IGNORE_EXCEPTION', true);
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));

require __DIR__ . '/../yfshop.php';
define('MOBILE_RESOURCE_SITE_URL',MOBILE_SITE_URL.DS.'resource');

//if (!is_null($_GET['key']) && !is_string($_GET['key'])) {
//    $_GET['key'] = null;
//}
//if (!is_null($_POST['key']) && !is_string($_POST['key'])) {
//    $_POST['key'] = null;
//}
//if (!is_null($_REQUEST['key']) && !is_string($_REQUEST['key'])) {
//    $_REQUEST['key'] = null;
//}

//框架扩展
require(BASE_PATH.'/framework/function/function.php');

if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');

Base::run();

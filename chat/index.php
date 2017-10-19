<?php
/**
 * 初始化文件
 * @gyf
 */
define('APP_ID','chat');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
if (!@include(dirname(dirname(__FILE__)).'/yfshop.php')) exit('yfshop.php isn\'t exists!');

if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');

Base::run();
?>
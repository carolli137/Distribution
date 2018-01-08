<?php
/**
 * 初始化文件
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.suibianlu.com
 * @link       交流群号：575710573
 */
define('APP_ID','chat');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
if (!@include(dirname(dirname(__FILE__)).'/33hao.php')) exit('33hao.php isn\'t exists!');

if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');

Base::run();
?>
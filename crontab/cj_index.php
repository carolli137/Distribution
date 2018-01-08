<?php
/**
 * 队列
 *
 *
 * 计划任务触发 by suibianlu.com
 */


$_SERVER['argv'][1] = $_GET['act'];
@$_SERVER['argv'][2] = $_GET['op'];

if (empty($_SERVER['argv'][1])) exit('Access Invalid!');

define('APP_ID','crontab');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
define('TRANS_MASTER',true);
require __DIR__ . '/../33hao.php';

if (PHP_SAPI == 'cli') {
    $_GET['act'] = $_SERVER['argv'][1];
    $_GET['op'] = empty($_SERVER['argv'][2]) ? 'index' : $_SERVER['argv'][2];
}
if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');

Base::run();

<?php
/**
 * 前台control父类
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.suibianlu.com
 * @link       交流群号：575710573
 */
defined('In33hao') or exit('Access Invalid!');

/********************************** 前台control父类 **********************************************/

class BaseControl {
	public function __construct(){
		Language::read('common');
	}
}

<?php
//by suibianlu.com 好商城
$config = array();
$config['base_site_url'] 		= 'http://www.haoshop.com';
$config['shop_site_url']        = 'http://www.haoshop.com/shop';
$config['cms_site_url']         = 'http://www.haoshop.com/cms';
$config['microshop_site_url']   = 'http://www.haoshop.com/microshop';
$config['circle_site_url']      = 'http://www.haoshop.com/circle';
$config['admin_site_url']       = 'http://www.haoshop.com/admin';
$config['mobile_site_url']      = 'http://www.haoshop.com/mobile';
$config['wap_site_url']         = 'http://www.haoshop.com/wap';
$config['chat_site_url']        = 'http://www.haoshop.com/chat';
$config['node_site_url'] 		= 'http://www.haoshop.com:33'; //如果要启用IM，把 http://www.haoshop.com 修改为：http://您的服务器IP
$config['delivery_site_url']    = 'http://www.haoshop.com/delivery';
$config['chain_site_url']       = 'http://www.haoshop.com/chain';
$config['member_site_url']      = 'http://www.haoshop.com/member';
$config['upload_site_url']      = 'http://www.haoshop.com/data/upload';
$config['resource_site_url']    = 'http://www.haoshop.com/data/resource';
$config['version']              = '201706150001';
$config['setup_date']           = '2018-01-04 15:59:02';
$config['gip']                  = 0;
$config['dbdriver']             = 'mysqli';
$config['tablepre']             = '33hao_';
$config['db']['1']['dbhost']    = 'localhost';
$config['db']['1']['dbport']    = '3306';
$config['db']['1']['dbuser']    = 'root';
$config['db']['1']['dbpwd']     = '123456';
$config['db']['1']['dbname']    = '33hao';
$config['db']['1']['dbcharset'] = 'UTF-8';
$config['db']['slave']          = $config['db']['master'];
$config['session_expire']   = 3600;
$config['lang_type']        = 'zh_cn';
$config['cookie_pre']       = '588B_';
$config['cache_open'] = false;
//$config['redis']['prefix']        = 'hao_';
//$config['redis']['master']['port']        = 6379;
//$config['redis']['master']['host']        = '127.0.0.1';
//$config['redis']['master']['pconnect']    = 0;
//$config['redis']['slave']             = array();
//$config['fullindexer']['open']      = false;
//$config['fullindexer']['appname']   = '33hao';
$config['debug']            = false;
$config['url_model'] = false; //如果要启用伪静态，把false修改为true
$config['subdomain_suffix'] = '';//如果要启用店铺二级域名，请填写不带www的域名，比如suibianlu.com
//$config['session_type'] = 'redis';
//$config['session_save_path'] = 'tcp://127.0.0.1:6379';
$config['node_chat'] = false;//如果要启用IM，把false修改为true
//流量记录表数量，为1~10之间的数字，默认为3，数字设置完成后请不要轻易修改，否则可能造成流量统计功能数据错误
$config['flowstat_tablenum'] = 3;
$config['queue']['open'] = false;
$config['queue']['host'] = '127.0.0.1';
$config['queue']['port'] = 6379;
$config['https'] = false;
//开店数量限制，0为不限
$config['store_limit'] = 0;
//发商品数量限制，0为不限
$config['sg_goods_limit'] = 0;
return $config;
<?php
//by suibianlu.com 好商城
$config = array();
$config['base_site_url'] 		= '===url===';
$config['shop_site_url']        = '===url===/shop';
$config['cms_site_url']         = '===url===/cms';
$config['microshop_site_url']   = '===url===/microshop';
$config['circle_site_url']      = '===url===/circle';
$config['admin_site_url']       = '===url===/admin';
$config['mobile_site_url']      = '===url===/mobile';
$config['wap_site_url']         = '===url===/wap';
$config['chat_site_url']        = '===url===/chat';
$config['node_site_url'] 		= '===url===:33'; //如果要启用IM，把 ===url=== 修改为：http://您的服务器IP
$config['delivery_site_url']    = '===url===/delivery';
$config['chain_site_url']       = '===url===/chain';
$config['member_site_url']      = '===url===/member';
$config['upload_site_url']      = '===url===/data/upload';
$config['resource_site_url']    = '===url===/data/resource';
$config['version']              = '201706150001';
$config['setup_date']           = '===setup_date===';
$config['gip']                  = 0;
$config['dbdriver']             = 'mysqli';
$config['tablepre']             = '===db_prefix===';
$config['db']['1']['dbhost']    = '===db_host===';
$config['db']['1']['dbport']    = '===db_port===';
$config['db']['1']['dbuser']    = '===db_user===';
$config['db']['1']['dbpwd']     = '===db_pwd===';
$config['db']['1']['dbname']    = '===db_name===';
$config['db']['1']['dbcharset'] = '===db_charset===';
$config['db']['slave']          = $config['db']['master'];
$config['session_expire']   = 3600;
$config['lang_type']        = 'zh_cn';
$config['cookie_pre']       = '===cookie_pre===';
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

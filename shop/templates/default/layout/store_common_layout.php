<?php defined('In33hao') or exit('Access Invalid!');
$wapurl = WAP_SITE_URL;
	$agent = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($agent,"comFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")){
		global $config;
        if(!empty($config['wap_site_url'])){
            $url = $config['wap_site_url'];
            switch ($_GET['act']){
			case 'goods':
			  $url .= '/tmpl/product_detail.html?goods_id=' . $_GET['goods_id'];
			  break;
			case 'store_list':
			  $url .= '/shop.html';
			  break;
			case 'show_store':
			  $url .= '/tmpl/store.html?store_id=' . $_GET['store_id'];
			  break;
			}
        } else {
            header('Location:'.$wapurl.$_SERVER['QUERY_STRING']);
        }
        header('Location:' . $url);
        exit();	
	}
?>
<!doctype html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<?php echo html_entity_decode($output['setting_config']['qq_appcode'],ENT_QUOTES); ?>
<?php echo html_entity_decode($output['setting_config']['sina_appcode'],ENT_QUOTES); ?>
<?php echo html_entity_decode($output['setting_config']['share_qqzone_appcode'],ENT_QUOTES); ?>
<?php echo html_entity_decode($output['setting_config']['share_sinaweibo_appcode'],ENT_QUOTES); ?>
<style type="text/css">
body {
_behavior: url(<?php echo SHOP_TEMPLATES_URL;
?>/css/csshover.htc);
}
</style>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/store_header.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/IE6_PNG.js"></script>
<script>
DD_belatedPNG.fix('.pngFix');
</script>
<script>
// <![CDATA[
if((window.navigator.appName.toUpperCase().indexOf("MICROSOFT")>=0)&&(document.execCommand))
try{
document.execCommand("BackgroundImageCache", false, true);
   }
catch(e){}
// ]]>
</script>
<![endif]-->
<script>
var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var LOGIN_SITE_URL = '<?php echo LOGIN_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';var SITEURL = '<?php echo SHOP_SITE_URL;?>';var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';
</script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
</head>
<body>
<!-- PublicTopLayout Begin -->
<?php require_once template('layout/layout_top');?>
<!-- PublicHeadLayout Begin -->
<div class="header-wrap">
  <header class="public-head-layout wrapper">
    <h1 class="site-logo"><a href="<?php echo SHOP_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$output['setting_config']['site_logo']; ?>" class="pngFix"></a></h1>
	<div class="heade_store_info">
    	<div class="slogo">
        	<a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$output['store_info']['store_id']));?>" title="<?php echo $output['setting_config']['site_name']; ?>" class="store_name"><?php echo $output['store_info']['store_name']; ?></a>
            <br>
            <?php if (!$output['store_info']['is_own_shop']) { ?>
            <span class="all-rate"  style="text-align: center;">
			<img src="<?php echo SHOP_TEMPLATES_URL;?><?php echo '/images/store_grade/'.$output['store_info']['grade_id'].'.png'; ?>" class="pngFix">
              
            </span>
			<?php } ?>
        </div>
        <div class="pj_info">
        	<?php  foreach ($output['store_info']['store_credit'] as $value) {?>
            <div class="shopdsr_item">
                <div class="shopdsr_title"><?php echo $value['text'];?></div>
                <div class="shopdsr_score"><?php echo $value['credit'];?></div>
            </div>
            <?php } ?>
    	</div>
        <div class="sub">
          <div class="store-logo"><img src="<?php echo getStoreLogo($output['store_info']['store_label'],'store_logo');?>" alt="<?php echo $output['store_info']['store_name'];?>" title="<?php echo $output['store_info']['store_name'];?>" /></div>
          <?php include template('store/info');?>
        </div>
	</div>
    <div class="heade_service_info">
        <div class="displayed">
            <i></i>在线客服
            <div class="sub">
                <?php include template('store/callcenter');?>
            </div>
        </div>
   </div>
    <div class="head-search-bar" id="search">
        <form class="search-form" method="get" action="<?php echo SHOP_SITE_URL.'/';?>index.php" name="formSearch" id="formSearch">
          <input name="act" id="search_act" value="search" type="hidden" />
          <input type="hidden" name="keyword" value="<?php echo $_GET['inkeyword'];?>" />
          <input name="inkeyword" id="inkeyword" type="text" class="input-text" value="<?php echo $_GET['inkeyword'];?>" x-webkit-speech lang="zh-CN" onwebkitspeechchange="foo()" x-webkit-grammar="builtin:search" placeholder="<?php echo $lang['nc_what_goods'];?>" />
          <a href="javascript:void(0)" class="search-btn-all" nctype="search_in_shop"><span>搜全站</span></a><a href="javascript:void(0)" class="search-btn-store" nctype="search_in_store"><span>搜店内</span></a>
        </form>
      </div>
    
    
  </header>
  </div>
<!-- PublicHeadLayout End -->



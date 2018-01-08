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
<meta name="author" content="33HAO">
<meta name="copyright" content="33HAO Inc. All Rights Reserved">
<meta name="renderer" content="webkit">
<meta name="renderer" content="ie-stand">
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<link rel="shortcut icon" href="<?php echo BASE_SITE_URL;?>/favicon.ico" />
<?php echo html_entity_decode($output['setting_config']['qq_appcode'],ENT_QUOTES); ?>
<?php echo html_entity_decode($output['setting_config']['sina_appcode'],ENT_QUOTES); ?>
<?php echo html_entity_decode($output['setting_config']['share_qqzone_appcode'],ENT_QUOTES); ?>
<?php echo html_entity_decode($output['setting_config']['share_sinaweibo_appcode'],ENT_QUOTES); ?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/store_header.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<style type="text/css">
body {_behavior: url(<?php echo SHOP_TEMPLATES_URL;?>/css/csshover.htc);}
</style>
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
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.masonry.js"></script>
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
        	<a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$output['store_info']['store_id']));?>" title="<?php echo $output['setting_config']['site_name']; ?>" class="store_name"><?php echo $output['store_info']['store_name']; ?></a> <img src="<?php echo SHOP_TEMPLATES_URL;?><?php echo '/images/store_grade/'.$output['store_info']['grade_id'].'.png'; ?>" class="pngFix">
            <br>
            <span class="all-rate">
           <span member_id="<?php echo $output['store_info']['member_id'];?>"></span>
    <?php if(!empty($output['store_info']['store_qq'])){?>
    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $output['store_info']['store_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $output['store_info']['store_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $output['store_info']['store_qq'];?>:52" style=" vertical-align: middle;"/></a>
    <?php }?>
    <?php if(!empty($output['store_info']['store_ww'])){?>
    <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=1&charset=<?php echo CHARSET;?>" ><img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="<?php echo $lang['nc_message_me'];?>" style=" vertical-align: middle;"/></a>
    <?php }?>
            </span>
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
          <?php include template('store/srore_info');?>
        </div>
	</div>
    <div class="heade_service_info">
        <div class="displayed">
            <div>手机逛</div>
            <i></i>
            <div class="sub">
               <img src="<?php echo storeQRCode($output['store_info']['store_id']);?>" title="店铺二维码，使用手机扫一扫逛起吧">
               <p>扫一扫，手机逛起来</p>
            </div>
        </div>
   </div>
    <div class="head-search-bar" id="search">
        <form class="search-form" method="get" action="<?php echo SHOP_SITE_URL.'/';?>index.php" name="formSearch" id="formSearch">
          <input name="act" id="search_act" value="search" type="hidden" />
          <input type="hidden" name="keyword" value="<?php echo $_GET['inkeyword'];?>" />
          <input name="inkeyword" id="inkeyword" type="text" class="input-text" value="<?php echo $_GET['inkeyword'];?>" x-webkit-speech lang="zh-CN" onwebkitspeechchange="foo()" x-webkit-grammar="builtin:search" placeholder="<?php echo $lang['nc_what_goods'];?>" />
          <a href="javascript:void(0)" class="search-btn-all" nctype="search_in_shop"><span>搜全站</span></a><a href="javascript:void(0)" class="search-btn-store" nctype="search_in_store"><span>搜本店</span></a>
        </form>
      </div>
  </header>
  </div>
<!-- PublicHeadLayout End -->
<div id="store_decoration_content" class="background" style="<?php echo $output['decoration_background_style'];?>">
<?php if(!empty($output['decoration_nav'])) {?>
<style><?php echo $output['decoration_nav']['style'];?></style>
<?php } ?>
<div class="ncsl-nav">
    <?php if(isset($output['decoration_banner'])) { ?>
    <!-- 启用店铺装修 -->
    <?php if($output['decoration_banner']['display'] == 'true') { ?>
    <div id="decoration_banner" class="banner"> <img src="<?php echo $output['decoration_banner']['image_url'];?>" alt=""> </div>
    <?php } ?>
    <?php } else { ?>
    <!-- 不启用店铺装修 -->
    <div class="banner"><a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$output['store_info']['store_id']));?>" class="img">
      <?php if(!empty($output['store_info']['store_banner'])){?>
      <img src="<?php echo getStoreLogo($output['store_info']['store_banner'],'store_logo');?>" alt="<?php echo $output['store_info']['store_name']; ?>" title="<?php echo $output['store_info']['store_name']; ?>" class="pngFix">
      <?php }else{?>
      <div class="ncs-default-banner"></div>
      <?php }?>
      </a></div>
    <?php } ?>
    <div id="nav" class="ncs-nav">
      <ul>
	  <?php if(!empty($output['goods_class_list'])){?>
        <li id="store_nav_class_button" class="normal ncs-nav-classes"> 
          <!-- 店铺商品分类 --> 
          <a href="javascript:;"><span>站内所有分类<i></i></span></a>
          <ul id="store_nav_class_menu" class="classes-menu" style="display:none;">
            <?php if(!empty($output['goods_class_list']) && is_array($output['goods_class_list'])){?>
            <?php foreach($output['goods_class_list'] as $value){?>
            <li class="store-nav-class-sub-menu"><a href="<?php echo urlShop('show_store', 'goods_all', array('store_id' => $output['store_info']['store_id'], 'stc_id' => $value['stc_id']));?>" title="<?php echo $value['stc_name'];?>"><i></i><?php echo $value['stc_name'];?></a>
              <?php if(!empty($value['children']) && is_array($value['children'])){?>
              <ul class="store-nav-class-menu-item" style="display:none;">
                <?php foreach($value['children'] as $value1){?>
                <li><a href="<?php echo urlShop('show_store', 'goods_all', array('store_id' => $output['store_info']['store_id'], 'stc_id' => $value1['stc_id']));?>" title="<?php echo $value1['stc_name'];?>"><i></i><?php echo $value1['stc_name'];?></a></li>
                <?php }?>
              </ul>
              <?php }?>
            </li>
            <?php }?>
            <?php }?>
          </ul>
        </li>
		<?php }?>
        <li class="<?php if($output['page'] == 'index'){?>active<?php }else{?>normal<?php }?>"><a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$output['store_info']['store_id']));?>"><span><?php echo $lang['nc_store_index'];?><i></i></span></a></li>
        <?php if(!empty($output['store_navigation_list'])){
      		foreach($output['store_navigation_list'] as $value){
                if($value['sn_if_show']) {
      			if($value['sn_url'] != ''){?>
        <li class="<?php if($output['page'] == $value['sn_id']){?>active<?php }else{?>normal<?php }?>"><a href="<?php echo $value['sn_url']; ?>" <?php if($value['sn_new_open']){?>target="_blank"<?php }?>><span><?php echo $value['sn_title'];?><i></i></span></a></li>
        <?php }else{ ?>
        <li class="<?php if($output['page'] == $value['sn_id']){?>active<?php }else{?>normal<?php }?>"><a href="<?php echo urlShop('show_store', 'show_article', array('store_id' => $output['store_info']['store_id'], 'sn_id' => $value['sn_id']));?>"><span><?php echo $value['sn_title'];?><i></i></span></a></li>
        <?php }}}} ?>
        <li class="<?php if ($output['page'] == 'store_sns') {?>active<?php }else{?>normal<?php }?>"><a href="<?php echo urlShop('store_snshome', 'index', array('sid' => $output['store_info']['store_id']))?>"><span>店铺动态<i></i></span></a></li>
      </ul>
    </div>
  </div>
</div>
<script type="text/javascript">
var PRICE_FORMAT = '<?php echo $lang['currency'];?>%s';
$(function(){
		$('a[nctype="search_in_store"]').click(function(){
		if ($('#inkeyword').val() == '') {
			return false;
		}
		$('#search_act').val('show_store');
		$('<input type="hidden" value="<?php echo $output['store_info']['store_id'];?>" name="store_id" /> <input type="hidden" name="op" value="goods_all" />').appendTo("#formSearch");
		$('#formSearch').submit();
	});
	$('a[nctype="search_in_shop"]').click(function(){
		document.formSearch.keyword.value=document.formSearch.inkeyword.value;
		if ($('#inkeyword').val() == '') {
			return false;
		}
		$('#formSearch').submit();
	});
	$("#formSearch").keydown(function(e){
         if(e.keyCode == 13){
			document.formSearch.keyword.value=document.formSearch.inkeyword.value;
			}
		});
	$('#inkeyword').css("color","#999999");
  //店铺商品分类
    $('#store_nav_class_button').on('mouseover', function() {
        $('#store_nav_class_menu').show();
    });
    $('#store_nav_class_button').on('mouseout', function() {
        $('#store_nav_class_menu').hide();
    });
    $('.store-nav-class-sub-menu').on('mouseover', function() {
        $('.store-nav-class-menu-item').hide();
        $(this).children('.store-nav-class-menu-item').show();
    });
    $('.store-nav-class-sub-menu').on('mouseout', function() {
        $('.store-nav-class-menu-item').hide();
    });
});
  </script>
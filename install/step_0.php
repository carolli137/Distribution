<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $html_title;?></title>
<link href="css/install.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../data/resource/js/jquery.js"></script>
<link href="../data/resource/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../data/resource/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="../data/resource/js/jquery.mousewheel.js"></script>
</head>

<body>
<?php defined('In33hao') or exit('Access Invalid!');?>
<?php echo $html_header;?>
<div class="main">
  <div class="text-box" id="text-box">
    <div class="license">
      <h1>好商城V5 安装协议</h1>
      <p>感谢你选择好商城V5，本系统由好商城根据网域天创网络流传的版本所改进版本，系统版权归属于软件开发商，只用于学习研究交流使用。</p>
      <p>
用户须知：本协议是您与好商城之间关于您安装使用好商城suibianlu.com提供的好商城V5版电商系统及服务的法律协议。无论您是个人组织、盈利与否、用途如何（包括以学习和研究为目的），均与好商城无关！好商城只提供相关技术服务！不负责任何商业责任！还请知悉。</p>
      <p></p>
      <p align="right">好商城网店技术交流中心</p>
    </div>
  </div>
  <div class="btn-box"><a href="index.php?step=1" class="btn btn-primary">同意协议进入安装</a><a href="javascript:window.close()" class="btn">不同意</a></div>
</div>
<div class="footer">
 <h6><a href="http://www.suibianlu.com" target="_blank">程序来源于 bbs.suibianlu.com</a></h6>
</div>
<script type="text/javascript">
$(document).ready(function(){
	//自定义滚定条
	$('#text-box').perfectScrollbar();
});
</script>
</body>
</html>

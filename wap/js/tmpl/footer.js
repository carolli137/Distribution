$(function (){
	var cart_count = 0;
	cart_count = getCookie('cart_count');
    if (getQueryString('key') != '') {
        var key = getQueryString('key');
        var username = getQueryString('username');
        addCookie('key', key);
        addCookie('username', username);
    } else {
        var key = getCookie('key');
    }
    var html = '<div class="nctouch-footer-wrap posr">'
        +'<div class="nav-text">';
    if(key){
        html += '<a href="'+WapSiteUrl+'/tmpl/member/member.html">我的商城</a>'
            + '<a id="logoutbtn" href="javascript:void(0);">注销</a>'
            + '<a href="'+WapSiteUrl+'/tmpl/member/member_feedback.html">反馈</a>'
	    + '<a href="' + WapSiteUrl + '/tmpl/article_list.html?ac_id=2">帮助</a>';
            
    } else {
        html += '<a href="'+WapSiteUrl+'/tmpl/member/login.html">登录</a>'
            + '<a href="'+WapSiteUrl+'/tmpl/member/register.html">注册</a>'
            + '<a href="'+WapSiteUrl+'/tmpl/member/login.html">反馈</a>'
	    + '<a href="' + WapSiteUrl + '/tmpl/article_list.html?ac_id=2">帮助</a>';
    }
        html += '<a href="javascript:void(0);" class="gotop">返回顶部</a>' + "</div>" + '<!--<div class="copyright">' + 'Copyright&nbsp;&copy;&nbsp;2005-2016 <a href="javascript:void(0);">好商城V5 www.suibianlu.com</a>版权所有' + "</div>--></div>";
        
      if (cart_count > 0) {
      	var fnav = '<div id="footnav" class="footnav clearfix"><ul>'
		+'<li><a href="'+WapSiteUrl+'"><i class="home"></i><p>首页</p></a></li>'
		+'<li><a href="'+WapSiteUrl+'/tmpl/product_first_categroy.html"><i class="categroy"></i><p>分类</p></a></li>'
		+'<li><a href="'+WapSiteUrl+'/tmpl/search.html"><i class="search"></i><p>搜索</p></a></li>'
		+'<li><a href="'+WapSiteUrl+'/tmpl/cart_list.html"><span id="cart_count"><i class="cart"></i><sup>' + cart_count + '</sup></span><p>购物车</p></a></li>'
		+'<li><a href="'+WapSiteUrl+'/tmpl/member/member.html"><i class="member"></i><p>我的商城</p></a></li></ul>'
		+'</div>';
      	} else {
      	var fnav = '<div id="footnav" class="footnav clearfix"><ul>'
		+'<li><a href="'+WapSiteUrl+'"><i class="home"></i><p>首页</p></a></li>'
		+'<li><a href="'+WapSiteUrl+'/tmpl/product_first_categroy.html"><i class="categroy"></i><p>分类</p></a></li>'
		+'<li><a href="'+WapSiteUrl+'/tmpl/search.html"><i class="search"></i><p>搜索</p></a></li>'
		+'<li><a href="'+WapSiteUrl+'/tmpl/cart_list.html"><span id="cart_count"><i class="cart"></i></span><p>购物车</p></a></li>'
		+'<li><a href="'+WapSiteUrl+'/tmpl/member/member.html"><i class="member"></i><p>我的商城</p></a></li></ul>'
		+'</div>';	
      	}
	$("#footer").html(html+fnav);
    var key = getCookie('key');
	$('#logoutbtn').click(function(){
		var username = getCookie('username');
		var key = getCookie('key');
		var client = 'wap';
		$.ajax({
			type:'get',
			url:ApiUrl+'/index.php?act=logout',
			data:{username:username,key:key,client:client},
			success:function(result){
				if(result){
					delCookie('username');
					delCookie('key');
					delCookie('cart_count');
					location.href = WapSiteUrl;
				}
			}
		});
	});
	if(typeof(navigate_id) == 'undefined'){navigate_id="0";}
	//当前页面
	if(navigate_id == "1"){
		$(".footnav .home").parent().addClass("current");
		$(".footnav .home").attr('class','home2');
	}else if(navigate_id == "2"){
		$(".footnav.categroy").parent().addClass("current");
		$(".footnav.categroy").attr('class','categroy2');
	}else if(navigate_id == "3"){
		$(".footnav .search").parent().addClass("current");
		$(".footnav .search").attr('class','search2');
	}else if(navigate_id == "4"){
		$(".footnav .cart").parent().parent().addClass("current");
		$(".footnav .cart").attr('class','cart2');
	}else if(navigate_id == "5"){
		$(".footnav .member").parent().addClass("current");
		$(".footnav .member").attr('class','member2');
	}
});
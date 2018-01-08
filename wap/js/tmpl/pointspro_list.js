// 积分列表 33hao
var page = pagesize;
var curpage = 1;
var hasmore = true;
var footer = false;
var keyword = decodeURIComponent(getQueryString('keyword'));
var key = getCookie("key");//getQueryString('key');
var my = getQueryString('my');
var order = getQueryString('order');
var area_id = getQueryString('area_id');
var points_min = getQueryString('points_min');
var points_max = getQueryString('points_max');

var ci = getQueryString('ci');
var myDate = new Date();
var searchTimes = myDate.getTime();

$(function(){
    $.animationLeft({
        valve : '#search_adv',
        wrapper : '.nctouch-full-mask',
        scroll : '#list-items-scroll'
    });
    $("#header").on('click', '.header-inp', function(){
        location.href = WapSiteUrl + '/tmpl/search.html?keyword=' + keyword;
    });
    if (keyword != '') {
    	$('#keyword').html(keyword);
    }

    // 商品展示形式
    $('#show_style').click(function(){
        if ($('#product_list').hasClass('grid')) {
            $(this).find('span').removeClass('browse-grid').addClass('browse-list');
            $('#product_list').removeClass('grid').addClass('list');
        } else {
            $(this).find('span').addClass('browse-grid').removeClass('browse-list');
            $('#product_list').addClass('grid').removeClass('list');
        }
    });

    // 排序显示隐藏
    $('#sort_default').click(function(){
        if ($('#sort_inner').hasClass('hide')) {
            $('#sort_inner').removeClass('hide');
        } else {
            $('#sort_inner').addClass('hide');
        }
    });
    $('#nav_ul').find('a').click(function(){
        $(this).addClass('current').parent().siblings().find('a').removeClass('current');
        if (!$('#sort_inner').hasClass('hide') && $(this).parent().index() > 0) {
            $('#sort_inner').addClass('hide');
        }
    });
    $('#sort_inner').find('a').click(function(){
        $('#sort_inner').addClass('hide').find('a').removeClass('cur');
        var text = $(this).addClass('cur').text();
        $('#sort_default').html(text + '<i></i>');
    });

    get_list();
    $(window).scroll(function(){
        if(($(window).scrollTop() + $(window).height() > $(document).height()-1)){
            get_list();
        }
    });
    search_adv();
});

function get_list() {
    $('.loading').remove();
    if (!hasmore) {
        return false;
    }
    hasmore = false;
    param = {};
    param.page = page;
    param.curpage = curpage;

	if(my=='isable')
	{
		 param.isable = 1;
	}
    if (key != '') {
        param.key = key;
    }
	
    if (order != '') {
        param.orderby = order;
    }

    $.getJSON(ApiUrl + '/index.php?act=pointprod&op=index' + window.location.search.replace('?','&'), param, function(result){
    	if(!result) {
    		result = [];
    		result.datas = [];
    		result.datas.goods_list = [];
    	}
        $('.loading').remove();
        curpage++;
        var html = template.render('home_body', result);
        $("#product_list .goods-secrch-list").append(html);
		//$('#list-items-scroll').html(template.render('search_items',result.datas));
		if (points_min) {
    		$('#points_min').val(points_min);
    	}
    	if (points_max) {
    		$('#points_max').val(points_max);
    	}
        hasmore = result.hasmore;
    });
}

function search_adv() {
    	
    	if (points_min) {
    		$('#points_min').val(points_min);
    	}
    	if (points_max) {
    		$('#points_max').val(points_max);
    	}   	

    	$('#search_submit2').click(function(){
    		var queryString = '?keyword=' + keyword, ci = '';
    		queryString += '&area_id=' + $('#area_id').val();
    		if ($('#points_min').val() != '') {
    			queryString += '&points_min=' + $('#points_min').val();
    		}
    		if ($('#points_max').val() != '') {
    			queryString += '&points_max=' + $('#points_max').val();
    		}
    		
    		window.location.href = WapSiteUrl + '/tmpl/pointspro_list.html' + queryString;
    	});
    	$('a[nctype="items"]').click(function(){
    		var myDate = new Date();
    		if(myDate.getTime() - searchTimes > 300) {
    			$(this).toggleClass('current');
    			searchTimes = myDate.getTime();
    		}
    	});
    	$('input[nctype="price"]').on('blur',function(){
    		if ($(this).val() != '' && ! /^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test($(this).val())) {
    			$(this).val('');
    		}
    	});
    	$('#reset').click(function(){
    		$('a[nctype="items"]').removeClass('current');
    		$('input[nctype="price"]').val('');
    		$('#area_id').val('');
    	});
   
}

function init_get_list(o, k) {
	if(o==2&&k=='isable')
	{
		var key = getCookie("key");
		if(key== undefined||key=='')
		{
			 $.sDialog({
                        skin: "block",
                        content: "请先登录，确定去登录。",
                        okBtn: true,
                        cancelBtn: true,
                        okFn: function() {
							addCookie('redirect_uri','/tmpl/pointspro_list.html');
                            window.location.href = WapSiteUrl+'/tmpl/member/login.html';
                        }
                    })
			//return false;
		}
	}
    order = o;
    my = k;
    curpage = 1;
    hasmore = true;
    $("#product_list .goods-secrch-list").html('');
    $('#footer').removeClass('posa');
    get_list();
}
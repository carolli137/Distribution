$(function(){
    if (getQueryString('seller_key') != '') {
        var key = getQueryString('seller_key');
        var seller_name = getQueryString('seller_name');
        addCookie('seller_key', key);
        addCookie('seller_name', seller_name);
    } else {
        var key = getCookie('seller_key');
        var seller_name = getCookie('seller_name');
    }
    if(key && seller_name){
        $.ajax({
            type:'post',
            url:ApiUrl+"/index.php?act=seller_index",
            data:{key:key},
            dataType:'json',
            success:function(result){
                checkSellerLogin(result.login);
                var html = ''
                    + '<div class="member-info">'
                        + '<div class="user-avatar"><img src="' + result.datas.store_info.store_avatar + '"/></div>'
                        + '<div class="user-name"><span>'+result.datas.seller_info.seller_name+'</span></div>'
                        + '<div class="store-name"><span>'+result.datas.store_info.store_name+'<sup>' + result.datas.store_info.grade_name + '</sup></span></div>'
                    + '</div>'
                    + '<div class="member-collect">'
                        + '<span><a href="javascript:;"><em>' + result.datas.store_info.daily_sales.ordernum + '</em><p>昨日销量</p></a></span>'
                        + '<span><a href="javascript:;"><em>' +result.datas.store_info.monthly_sales.ordernum + '</em><p>当月销量</p></a></span>'
                        + '<span><a href="javascript:;"><em>' +result.datas.statics.online + '</em><p>出售中</p></a></span>'
                    + '</div>';
                $(".member-top").html(html);
                
                //订单管理
                var html = ''
                    + '<li><a href="store_orders_list.html?data-state=state_new">'+ (result.datas.seller_info.order_nopay_count > 0 ? '<em></em>' : '') +'<i class="cc-01"></i><p>待付款</p></a></li>'
                    + '<li><a href="store_orders_list.html?data-state=state_pay">' + (result.datas.seller_info.order_noreceipt_count > 0 ? '<em></em>' : '') + '<i class="cc-02"></i><p>待发货</p></a></li>'
                    + '<li><a href="store_orders_list.html?data-state=state_send">' + (result.datas.seller_info.order_notakes_count > 0 ? '<em></em>' : '') + '<i class="cc-03"></i><p>已发货</p></a></li>'
                    + '<li><a href="store_orders_list.html?data-state=state_success">' + (result.datas.seller_info.return > 0 ? '<em></em>' : '') + '<i class="cc-05"></i><p>已完成</p></a></li>';
                $("#order_ul").html(html);
                
                //商品管理
			var html = ''
				+ '<li><a href="store_goods_list.html"><i class="cc-07"></i><p>出售中</p></a></li>'
				+ '<li><a href="store_goods_list.html?showtype=offlinegoods"><i class="cc-10"></i><p>仓库中</p></a></li>'
				+ '<li><a href="store_goods_list.html?showtype=illegalgoods"><i class="cc-14"></i><p>违规商品</p></a></li>'
				+ '<li><a href="store_goods_add.html"><i class="cc-04"></i><p>发布商品</p></a></li>'
                $("#goods_ul").html(html);
                
                 //统计结算
                var html = ''
                    + '<li><div><p style="font-size:18px;color:red;font-weight:bold;">11111</p><p>营业总额</p></div></li><li><div><p style="font-size:18px;color:red;font-weight:bold;">2222</p><p>30天销量</p></div></li><li><div><p style="font-size:18px;color:red;font-weight:bold;">3333</p><p>有效订单量</p></div></li><li><div><p style="font-size:18px;color:red;font-weight:bold;">444</p><p>结算金额</p></div></li>'
                $("#asset_ul").html(html);
                
                return false;
            }
        });
    } else {
        delCookie('seller_key');
        delCookie('seller_name');
        delCookie('store_name');
        var html = ''
            + '<div class="member-info">'
                + '<a href="login.html" class="default-avatar" style="display:block;"></a>'
                + '<a href="login.html" class="to-login">点击登录</a>'
            + '</div>'
            + '<div class="member-collect">'
                + '<span>'
                    + '<a href="login.html">'
                        + '<em>0</em>'
                        + '<p>昨日销量</p>'
                    + '</a>'
                + '</span>'
                + '<span>'
                    + '<a href="login.html">'
                        + '<em>0</em>'
                        + '<p>当月销量</p>'
                    + '</a>'
                + '</span>'
                + '<span>'
                    + '<a href="login.html">'
                        + '<em>0</em>'
                        + '<p>出售中</p>'
                    + '</a>'
                + '</span>'
            + '</div>';
        $(".member-top").html(html);
        
        //订单管理
        var html = ''
            + '<li><a href="login.html"><i class="cc-01"></i><p>待付款</p></a></li>'
            + '<li><a href="login.html"><i class="cc-02"></i><p>待发货</p></a></li>'
            + '<li><a href="login.html"><i class="cc-03"></i><p>待自提</p></a></li>'
            + '<li><a href="login.html"><i class="cc-05"></i><p>已取消</p></a></li>'
        $("#order_ul").html(html);

       //商品管理
        var html = ''
			+ '<li><a href="login.html"><i class="cc-07"></i><p>出售中</p></a></li>'
			+ '<li><a href="login.html"><i class="cc-10"></i><p>仓库中</p></a></li><li>'
			+ '<a href="login.html"><i class="cc-14"></i><p>违规商品</p></a></li>'
			+ '<li><a href="login.html"><i class="cc-04"></i><p>发布商品</p></a></li>'
			$("#goods_ul").html(html);
        return false;
    }

    //滚动header固定到顶部
    $.scrollTransparent();
});
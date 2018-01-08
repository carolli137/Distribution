$(function() { 
    if (getQueryString("seller_key") != "") {
        var a = getQueryString("seller_key");
        var e = getQueryString("sellername");
        addCookie("seller_key", a);
        addCookie("sellername", e)
    } else {
        var a = getCookie("seller_key")
    }
    if (a) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=seller_stat&op=ordersamount",
            data: {
                key: a,
				stattype:'year'
            },
            dataType: "json",
            success: function(a) {  
                 document.write(JSON.stringify(a));
            } 
        })
    } else {
    	 
         var i = '<div class="member-info">' + '<a href="login.html" class="default-avatar" style="display:block;"></a>' + '<a href="login.html" class="to-login">点击登录</a>' + "</div>" + '<div class="member-collect"><span><a href="login.html"><i class="favorite-goods"></i>' + "<p>店铺商品</p>" + '</a> </span><span><a href="login.html"><i class="favorite-store"></i>' + "<p>店铺订单</p>" + '</a> </span><span><a href="login.html"><i class="goods-browse"></i>' + "<p>我的众包</p>" + "</a> </span></div>";
         $(".member-top").html(i);
         
        return false
    }
    $.scrollTransparent()
});
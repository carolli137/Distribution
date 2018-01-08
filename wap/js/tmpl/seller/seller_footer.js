$(function() {
    var html = '<div id="footnav" class="footnav clearfix"><ul>' + '<li><a href="' + WapSiteUrl + '/tmpl/seller/seller.html"><i class="home"></i><p>商家中心</p></a></li>' + '<li><a href="' + WapSiteUrl + '/tmpl/seller/store_orders_list.html"><i  class="cc-09"></i><p>订单管理</p></a></li>' + '<li><a href="' + WapSiteUrl + '/tmpl/seller/store_goods_list.html"><i class="cc-07"></i><p>商品管理</p></a></li>' + '<li><a href="' + WapSiteUrl + '/tmpl/seller/store_orders_list.html?data-state=state_pay"><i class="cc-03"></i><p>待发货</p></a></li>' + '<li><a href="' + WapSiteUrl + '/tmpl/seller/store_goods_add.html"><i class="cc-04"></i><p>发布商品</p></a></li></ul>' + '</div>';
    $("#footer").html(html);
    $("#logoutbtn").click(function() {
        var a = getCookie("sellername");
        var e = getCookie("seller_key");
        var i = "wap";
        $.ajax({
            type: "get",
            url: ApiUrl + "/index.php?act=logout",
            data: {
                username: a,
                key: e,
                client: i
            },
            success: function(a) {
                if (a) {
                    delCookie("sellername");
                    delCookie("seller_key");
                    location.href = WapSiteUrl + "/tmpl/seller/seller.html"
                }
            }
        })
    })
});
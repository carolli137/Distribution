var page = pagesize; 
var curpage = 1;
var hasMore = true;
var footer = false;
var reset = true;
var orderKey = "";
var showtype=getQueryString("showtype");
$(function() {
    var e = getCookie("seller_key");
    if (!e) {
        window.location.href = WapSiteUrl + "/tmpl/seller/login.html"
    }
    if (getQueryString("data-state") != "") {
        $("#filtrate_ul").find("li").has('a[data-state="' + getQueryString("data-state") + '"]').addClass("selected").siblings().removeClass("selected")
    }
    $("#search_btn").click(function() {
        reset = true;
        t()
    });
    $("#fixed_nav").waypoint(function() {
        $("#fixed_nav").toggleClass("fixed")
    },
    {
        offset: "50"
    });
	 
    function t() {
        if (reset) {
            curpage = 1;
            hasMore = true
        }
        $(".loading").remove();
        if (!hasMore) {
            return false
        }
        hasMore = false;
        var t = $("#filtrate_ul").find(".selected").find("a").attr("data-state");
		 
        var r = $("#goods_key").val();
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=seller_goods&op=goods_list&page=" + page + "&curpage=" + curpage,
            data: {
                key: e,
                goods_type: t,
                keyword: r,
				search_type:0
            },
            dataType: "json",
            success: function(e) {
				 
                checkLogin(e.login);
                curpage++;
                hasMore = e.hasmore;
                if (!hasMore) {
                    get_footer()
                }
                if (e.datas.goods_list.length <= 0) {
                    $("#footer").addClass("posa")
                } else {
                    $("#footer").removeClass("posa")
                }
                var t = e;
                t.WapSiteUrl = WapSiteUrl;
                t.ApiUrl = ApiUrl;
                t.key = getCookie("seller_key");
                template.helper("$getLocalTime",
                function(e) {
                    var t = new Date(parseInt(e) * 1e3);
                    var r = "";
                    r += t.getFullYear() + "年";
                    r += t.getMonth() + 1 + "月";
                    r += t.getDate() + "日 ";
                    r += t.getHours() + ":";
                    r += t.getMinutes();
                    return r
                });
                template.helper("p2f",
                function(e) {
                    return (parseFloat(e) || 0).toFixed(2)
                });
                template.helper("parseInt",
                function(e) {
                    return parseInt(e)
                });
				 
                var r = template.render("order-list-tmpl", t);
                if (reset) {
                    reset = false;
                    $("#order-list").html(r)
                } else {
                    $("#order-list").append(r)
                }
            }
        })
    }
    $("#order-list").on("click", ".edit-goods", r);
    $("#order-list").on("click", ".offline-goods", o);
    $("#order-list").on("click", ".online-goods", n);
    $("#order-list").on("click", ".delete-goods", l);
    $("#order-list").on("click", ".evaluation-again-order", d);
    $("#order-list").on("click", ".viewdelivery-order", c);
    $("#order-list").on("click", ".check-payment",
    function() {
        var e = $(this).attr("data-paySn");
        toPay(e, "member_buy", "pay");
        return false
    });
    function r() {
        var e = $(this).attr("goods_id");
        window.location.href="store_goods_edit.html?goods_id="+e;
    }
    function a(r) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=member_order&op=order_cancel",
            data: {
                order_id: r,
                key: e
            },
            dataType: "json",
            success: function(e) {
                if (e.datas && e.datas == 1) {
                    reset = true;
                    t()
                } else {
                    $.sDialog({
                        skin: "red",
                        content: e.datas.error,
                        okBtn: false,
                        cancelBtn: false
                    })
                }
            }
        })
    }
    function o() {
        var e = $(this).attr("goods_id");
        $.sDialog({
            content: "是否下架商品？<h6>下架后还可以在仓库中上架！</h6>",
            okFn: function() {
                i(e)
            }
        })
    }
    function i(r) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=seller_goods&op=goods_unshow",
            data: {
                commonids: new Array(r),
                key: e
            },
            dataType: "json",
            success: function(e) { 
                if (e.datas && e.datas == 1) {
                    $.sDialog({
						skin: "red",
						content: "操作成功",
						okFn: function() {
							window.location.href = WapSiteUrl + "/tmpl/seller/store_goods_list.html";
						}
					})
                } else {
                    $.sDialog({
                        skin: "red",
                        content: e.datas.error,
                        okBtn: false,
                        cancelBtn: false
                    })
                }
            },error:function(er){alert(JSON.stringify(er));}
        })
    }
    function n() {
        var e = $(this).attr("goods_id");
        $.sDialog({
            content: "是否上架商品？<h6>上架后可以正常销售！</h6>",
            okFn: function() {
                s(e)
            }
        })
    }
    function s(r) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=seller_goods&op=goods_show",
            data: {
                commonids: new Array(r),
                key: e
            },
            dataType: "json",
            success: function(e) {
                if (e.datas && e.datas == 1) {
                    $.sDialog({
						skin: "red",
						content: "操作成功",
						okFn: function() {
							window.location.href = WapSiteUrl + "/tmpl/seller/store_goods_list.html";
						}
					})
                } else {
                    $.sDialog({
                        skin: "red",
                        content: e.datas.error,
                        okBtn: false,
                        cancelBtn: false
                    })
                }
            },error:function(er){alert(JSON.stringify(er));}
        })
    }
    function l() {
        var e = $(this).attr("goods_id");
        $.sDialog({
            content: "是否删除商品？<h6>删除后系统不保存！</h6>",
            okFn: function() {
                d(e)
            }
        })
    }
    function d(r) {
       $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=seller_goods&op=goods_drop",
            data: {
                commonids: new Array(r),
                key: e
            },
            dataType: "json",
            success: function(e) {
                if (e.datas && e.datas == 1) {
                    $.sDialog({
						skin: "red",
						content: "操作成功",
						okFn: function() {
							window.location.href = WapSiteUrl + "/tmpl/seller/store_goods_list.html";
						}
					})
                } else {
                    $.sDialog({
                        skin: "red",
                        content: e.datas.error,
                        okBtn: false,
                        cancelBtn: false
                    })
                }
            },error:function(er){alert(JSON.stringify(er));}
        })
    }
    function c() {
        var e = $(this).attr("order_id");
        location.href = WapSiteUrl + "/tmpl/member/order_delivery.html?order_id=" + e
    }
    $("#filtrate_ul").find("a").click(function() {
        $("#filtrate_ul").find("li").removeClass("selected");
        $(this).parent().addClass("selected").siblings().removeClass("selected");
        reset = true;
        window.scrollTo(0, 0);
        t()
    });
	//根据传值显示不同标签
  if(showtype=="")
	  t();
  else
	$("#"+showtype).click();
    //t();
  $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
            t()
        }
    })
});
function get_footer() {
    if (!footer) {
        footer = true;
        $.ajax({
            url: WapSiteUrl + "/js/tmpl/seller/seller_footer.js",
            dataType: "script"
        })
    }
}
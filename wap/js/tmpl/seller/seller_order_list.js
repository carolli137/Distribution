var page = pagesize; 
var curpage = 1;
var hasMore = true;
var footer = false;
var reset = true;
var orderKey = "";
var showtype=getQueryString("showtype");
var myexpresslist="";
var sendorderid=0;
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
        var r = $("#order_key").val();
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=seller_order&op=order_list&page=" + page + "&curpage=" + curpage,
            data: {
                key: e,
                state_type: t,
                order_key: r
            },
            dataType: "json",
            success: function(e) {
                checkLogin(e.login);
                curpage++;
                hasMore = e.hasmore;
                if (!hasMore) {
                    get_footer()
                }
			 
                if (e.datas.order_list.length <= 0) {
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
    $("#order-list").on("click", ".cancel-order", r);
    $("#order-list").on("click", ".spay-order-price", o);
    $("#order-list").on("click", ".send-order", n);  
    
    function r() {
        var e = $(this).attr("order_id");
        var os = $(this).attr("order_sn");
        $.sDialog({
            content: "<div style='text-align:left;'><h6>订单号："+os+"</h6><h6>取消原因：</h6><h6><input type='radio'  name='cancelreason' value='无法备齐货物'>无法备齐货物</h6><h6><input type='radio' name='cancelreason' value='不是有效的订单'>不是有效的订单</h6><h6><input type='radio' name='cancelreason' value='买家主动要求'>买家主动要求</h6><h6><input type='radio' name='cancelreason' checked value='其他原因'>其他原因</h6></div>",
            okFn: function() {
				var rt=$("input[name='cancelreason']:checked").val();
				 a(e,rt);
            }
        })
    }
    function a(r,rt) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=seller_order&op=order_cancel",
            data: {
                order_id: r,
				reason:rt,
                key: e
            },
            dataType: "json",
            success: function(e) {
                if (e.datas && e.datas == 1) {
					$.sDialog({
                        skin: "red",
                        content: "订单取消成功",
                        okBtn: true,
						okFn:function(){
						 reset = true;
						 t();
						},
                        cancelBtn: false
                    })

                   
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
        var e = $(this).attr("order_id");
        var bn = $(this).attr("buyer_name");
        var os = $(this).attr("order_sn");
        $.sDialog({
		    skin: "red",
            content: "<div style='text-align:left'>修改订单价格<h6>买家："+bn+"</h6><h6>订单号："+os+"</h6><h6>输入新价格：<input type='tel' name='newprice' id='newprice' style='width:2rem;padding-left:1rem;height:1rem;IME-MODE:disabled;'></h6></div>",
            okFn: function() {
               var price=$("#newprice").val();
			   if(price!=""&&(/^\d+$/.test(price))){ 
			       i(e,price);
			   }else{ 
				  alert("输入数字价格");
				  return false;
			   }
				   

				
            }
        })
    }
    function i(orderid,price) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=seller_order&op=order_spay_price",
            data: {
                order_id: orderid,
				order_fee:price,
                key: e
            },
            dataType: "json",
            success: function(e) {
                if (!e.datas.error) {
                    $.sDialog({
                        skin: "red",
                        content: e.datas,
                        okBtn: true,
						okFn:function(){
						 reset = true;
						 t();
						},
                        cancelBtn: false
                    })

                } else {
                    $.sDialog({
                        skin: "red",
                        content: e.datas.error,
                        okBtn: true,
                        cancelBtn: false
                    })
                }
            }
        })
    }
    function n() {
        sendorderid = $(this).attr("order_id");
        $.sDialog({
			autoTime: '30000',
			skin: "red", 
            content: "无需物流，商家自行发货<br/>确认发货？",
            okBtn: true,
			okFn:function(){
			    

						$.ajax({
							type: "post",
							url: ApiUrl + "/index.php?act=seller_order&op=order_deliver_send",
							data: {
								order_id: sendorderid, 
								key: e
							},
							dataType: "json",
							success: function(e) {
								checkLogin(e.login);
								if (e) { 
									$.sDialog({
										skin: "block",
										content: "发货成功!",
										okBtn: true,
										cancelBtn: true,
										okFn: function() {
											window.location.href="store_orders_list.html?data-state=state_send";
										}
									})
  
								}else{
								
									
									$.sDialog({
										skin: "block",
										content: "发货失败!",
										okBtn: true,
										cancelBtn: true 
									})
								
								}
							}
						  }) 
 

			},
            cancelBtn: true
        })
    }
   
    

    $("#filtrate_ul").find("a").click(function() {
        $("#filtrate_ul").find("li").removeClass("selected");
        $(this).parent().addClass("selected").siblings().removeClass("selected");
        reset = true;
        window.scrollTo(0, 0);
        t()
    });

    t();
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
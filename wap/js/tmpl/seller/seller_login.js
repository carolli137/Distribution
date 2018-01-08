$(function () {  
	var e = getCookie("seller_key");
	
    if (e) {
        window.location.href = WapSiteUrl + "/tmpl/seller/seller.html";
        return
    }
    var r = document.referrer;
    $.sValid.init({
        rules: {
            username: "required",
            userpwd: "required"
        },
        messages: {
            username: "用户名必须填写！",
            userpwd: "密码必填!"
        },
        callback: function (e, r, a) {
            if (e.length > 0) {
                var i = "";
                $.map(r, function (e, r) {
                    i += "<p>" + e + "</p>"
                });
                errorTipsShow(i)
            } else {
                errorTipsHide()
            }
        }
    });
    var a = true;
    $("#loginbtn").click(function () { 
        if (!$(this).parent().hasClass("ok")) {
            return false
        }
        if (a) {
            a = false
        } else {
            return false
        }
        var e = $("#username").val();
        var i = $("#userpwd").val();
        var t = "wap";
		 
        if ($.sValid()) {
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=seller_login&op=index",
                data: {
                    seller_name: e,
                    password: i,
                    client: t
                },
                dataType: "json",
                success: function (e) {
                    a = true;
                    if (!e.datas.error) {
                        if (typeof e.datas.key == "undefined") {
							
                            return false
                        } else {
                            var i = 0;
                            if ($("#checkbox").prop("checked")) {
                                i = 188
                            }
                            //updateCookieCart(e.datas.key);
                             addcookie("sellername", e.datas.username, i);
                             addcookie("seller_key", e.datas.key, i);
                            location.href = WapSiteUrl + "/tmpl/seller/seller.html";
							 
                        }
                        errorTipsHide()
                    } else {
						
                        errorTipsShow("<p>" + e.datas.error + "</p>")
                    }
                }
            })
        }
    });
    $(".weibo").click(function () {
        location.href = ApiUrl + "/index.php?act=connect&op=get_sina_oauth2"
    });
    $(".qq").click(function () {
        location.href = ApiUrl + "/index.php?act=connect&op=get_qq_oauth2"
    })
});
$(function() {
    var e = getCookie("seller_key");
    if (!e) {
        location.href = "login.html"
    }
    function s() {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=seller_address&op=address_list",
            data: {
                key: e
            },
            dataType: "json",
            success: function(e) {
                //checkLogin(e.login);
                if (e.datas.address_list == null) {
                    return false
                }
                var s = e.datas;
                var t = template.render("saddress_list", s);
                $("#address_list").empty();
                $("#address_list").append(t);
                $(".deladdress").click(function() {
                    var e = $(this).attr("address_id");
                    $.sDialog({
                        skin: "block",
                        content: "确认删除吗？",
                        okBtn: true,
                        cancelBtn: true,
                        okFn: function() {
                            a(e)
                        }
                    })
                })
            }
        })
    }
    s();
    function a(a) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=seller_address&op=address_del",
            data: {
                address_id: a,
                key: e
            },
            dataType: "json",
            success: function(e) {
                checkLogin(e.login);
                if (e) {



					$.sDialog({
                        skin: "block",
                        content: "地址删除成功!",
                        okBtn: true,
                        cancelBtn: true,
                        okFn: function() {
                            s()
                        }
                    })

  
                    
                }
            }
        })
    }
});
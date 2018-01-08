$(function() {
    var a = getCookie("seller_key");
    $.sValid.init({
        rules: {
            true_name: "required",
            mob_phone: "required",
            area_info: "required",
            address: "required"
        },
        messages: {
            true_name: "姓名必填！",
            mob_phone: "手机号必填！",
            area_info: "地区必填！",
            address: "街道必填！"
        },
        callback: function(a, e, r) {
            if (a.length > 0) {
                var i = "";
                $.map(e,
                function(a, e) {
                    i += "<p>" + a + "</p>"
                });
                errorTipsShow(i)
            } else {
                errorTipsHide()
            }
        }
    });
    $("#header-nav").click(function() {
        $(".btn").click()
    });
    $(".btn").click(function() {
        if ($.sValid()) {
            var e = $("#true_name").val();
            var r = $("#mob_phone").val();
            var i = $("#address").val();
            var d = $("#area_info").attr("data-areaid2");
            var t = $("#area_info").attr("data-areaid");
            var n = $("#area_info").val();
            var o = $("#is_default").attr("checked") ? 1 : 0;
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=seller_address&op=address_add",
                data: {
                    key: a,
                    seller_name: e,
                    telphone: r,
                    city_id: d,
                    area_id: t,
                    address: i,
                    area_info: n,
                    is_default: o
                },
                dataType: "json",
                success: function(a) {
                    if (a.code==200) {
						$.sDialog({
							skin: "block",
							content: "地址添加成功",
							okBtn: true,
							cancelBtn: true,
							okFn: function() {
								 location.href = WapSiteUrl + "/tmpl/seller/seller_address_list.html"
							}
						 })
                       
                    } else {
						$.sDialog({
							skin: "block",
							content: "地址添加失败"+JSON.stringify(a),
							okBtn: true,
							cancelBtn: false 
						 })
                    }
                }
            })
        }
    });
    $("#area_info").on("click",
    function() {
        $.areaSelected({
            success: function(a) {
                $("#area_info").val(a.area_info).attr({
                    "data-areaid": a.area_id,
                    "data-areaid2": a.area_id_2 == 0 ? a.area_id_1: a.area_id_2
                })
            }
        })
    })
});
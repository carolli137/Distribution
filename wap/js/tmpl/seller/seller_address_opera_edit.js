$(function() { 
    var a = getQueryString("address_id");
    var e = getCookie("seller_key");
    $.ajax({
        type: "post",
        url: ApiUrl + "/index.php?act=seller_address&op=address_info",
        data: {
            key: e,
            address_id: a
        },
        dataType: "json",
        success: function(a) {
            checkLogin(a.login);
            $("#true_name").val(a.datas.address_info.seller_name);
            $("#mob_phone").val(a.datas.address_info.telphone);
            $("#area_info").val(a.datas.address_info.area_info).attr({
                "data-areaid": a.datas.address_info.area_id,
                "data-areaid2": a.datas.address_info.city_id
            });
            $("#address").val(a.datas.address_info.address);
            var e = a.datas.address_info.is_default == "1" ? true: false;
            $("#is_default").prop("checked", e);
            if (e) {
                $("#is_default").parents("label").addClass("checked")
            }
        }
    });
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
                var d = "";
                $.map(e,
                function(a, e) {
                    d += "<p>" + a + "</p>"
                });
                errorTipsShow(d)
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
            var r = $("#true_name").val();
            var d = $("#mob_phone").val();
            var i = $("#address").val();
            var s = $("#area_info").attr("data-areaid2");
            var t = $("#area_info").attr("data-areaid");
            var n = $("#area_info").val();
            var o = $("#is_default").attr("checked") ? 1 : 0;
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=seller_address&op=address_add",
                data: {
                    key: e,
                    seller_name: r,
                    telphone: d,
                    city_id: s,
                    area_id: t,
                    address: i,
                    area_info: n,
                    is_default: o,
                    address_id: a
                },
                dataType: "json",
                success: function(a) {
                   if (a.code==200) {
						$.sDialog({
							skin: "block",
							content: "地址编辑成功",
							okBtn: true,
							cancelBtn: true,
							okFn: function() {
								 location.href = WapSiteUrl + "/tmpl/seller/seller_address_list.html"
							}
						 })
                       
                    } else {
						$.sDialog({
							skin: "block",
							content: "地址编辑失败"+JSON.stringify(a),
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
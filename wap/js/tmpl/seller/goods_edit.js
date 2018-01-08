$(function() {
    var key = getCookie("key");
    if (!key) {
        window.location.href = WapSiteUrl + "/tmpl/seller/login.html";
        return
    }
    var commonid = getQueryString("commonid");
    $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=seller_goods&op=goods_info",
                data: {
                    key: key,
                    goods_commonid: commonid,
                },
                dataType: "json",
                success: function(e) {
                    var gc_name = e.datas.goodscommon_info.gc_name.replace("&gt;", ">");
                    gc_name = gc_name.replace("&gt;", ">");
                    gc_name = gc_name.replace(" ", "");
                    gc_name = gc_name.replace(" ", "");

                    $("#g_name").val(e.datas.goodscommon_info.goods_name);
                    $("#g_jingle").val(e.datas.goodscommon_info.goods_jingle);
                    $("#gc_info").val(gc_name);
                    $("#gc_info").attr("data-gcid",e.datas.goodscommon_info.gc_id);
                    $("#gc_info").attr("data-cate_name",gc_name);
                    $("#g_price").val(e.datas.goodscommon_info.goods_price);
                    $("#g_marketprice").val(e.datas.goodscommon_info.goods_marketprice);
                    $("#g_costprice").val(e.datas.goodscommon_info.goods_costprice);
                    $("#g_storage").val(e.datas.goodscommon_info.g_storage);
                    $("#g_alarm").val(e.datas.goodscommon_info.goods_storage_alarm);
                    $("#g_body").val(e.datas.goodscommon_info.goods_body);
                    $("#g_serial").val(e.datas.goodscommon_info.goods_serial);
                    $("#g_freight").val(e.datas.goodscommon_info.goods_freight);

                }
    })
    $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=seller_goods&op=goods_image_info",
                data: {
                    key: key,
                    goods_commonid: commonid,
                },
                dataType: "json",
                success: function(d) {
                    $("#image_path").attr("value",d.datas.image_list[0].images[0].goods_image);
                    $("#image_path").attr("data-img",d.datas.image_list[0].images[0].goods_image_url);
                    $("#file_01").after('<div class="pic-thumb"><img src="'+d.datas.image_list[0].images[0].goods_image_url+'"/></div>');

                    $("#image_all_0").attr("value",d.datas.image_list[0].images[1].goods_image);
                    $("#image_all_0").attr("data-img",d.datas.image_list[0].images[1].goods_image_url);
                    $("#file_02").after('<div class="pic-thumb"><img src="'+d.datas.image_list[0].images[1].goods_image_url+'"/></div>');

                    $("#image_all_1").attr("value",d.datas.image_list[0].images[2].goods_image);
                    $("#image_all_1").attr("data-img",d.datas.image_list[0].images[2].goods_image_url);
                    $("#file_03").after('<div class="pic-thumb"><img src="'+d.datas.image_list[0].images[2].goods_image_url+'"/></div>');

                    $("#image_all_2").attr("value",d.datas.image_list[0].images[3].goods_image);
                    $("#image_all_2").attr("data-img",d.datas.image_list[0].images[3].goods_image_url);
                    $("#file_04").after('<div class="pic-thumb"><img src="'+d.datas.image_list[0].images[3].goods_image_url+'"/></div>');

                    $("#image_all_3").attr("value",d.datas.image_list[0].images[4].goods_image);
                    $("#image_all_3").attr("data-img",d.datas.image_list[0].images[4].goods_image_url);
                    $("#file_05").after('<div class="pic-thumb"><img src="'+d.datas.image_list[0].images[4].goods_image_url+'"/></div>');
                }
    })
    $('input[name="file"]').ajaxUploadImage({
            url : ApiUrl + "/index.php?act=seller_album&op=image_upload",
            data:{
                key:key,
                name:"file"
            },
            start :  function(element){
                element.parent().after('<div class="upload-loading"><i></i></div>');
                element.parent().siblings('.pic-thumb').remove();
            },
            success : function(element, result){
                // checkLogin(result.login);
                if (result.datas.error) {
                    element.parent().siblings('.upload-loading').remove();
                    $.sDialog({
                        skin:"red",
                        content:'图片尺寸过大！',
                        okBtn:false,
                        cancelBtn:false
                    });
                    return false;
                }
                element.parent().after('<div class="pic-thumb"><img src="'+result.datas.thumb_name+'"/></div>');
                element.parent().siblings('.upload-loading').remove();
                element.parents('a').next().val(result.datas.name);
                element.parents('a').next().attr('data-img',result.datas.thumb_name);
            }
    });

    $.sValid.init({
        rules: {
            g_name: "required",
            gc_info: "required",
            g_price: "required",
            g_marketprice: "required",
            image_path: "required",
        },
        messages: {
            g_name: "商品名称必填！",
            gc_info: "请选择商品分类！",
            g_price: "商品价格必填！",
            g_marketprice: "市场价格必填",
            image_path: "请上传主图",
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
            var g_name          = $("#g_name").val();
            var g_jingle        = $("#g_jingle").val();
            var cate_id         = $("#gc_info").attr("data-gcid");
            var cate_name       = $("#gc_info").attr("data-cate_name");
            var g_price         = $("#g_price").val();
            var g_marketprice   = $("#g_marketprice").val();
            var g_costprice     = $("#g_costprice").val();
            var g_discount      = g_price/g_marketprice*100;
            var image_path      = $("#image_path").val();//主图
            var image_all       = $("#image_all_0").val()+','+$("#image_all_1").val()+','+$("#image_all_2").val()+','+$("#image_all_3").val()+','+image_path;
            var g_alarm         = $("#g_alarm").val();//库存预警
            var g_storage       = $("#g_storage").val();//库存
            var image_body_0    = $("#image_body_0").attr('data-img');
            var image_body_1    = $("#image_body_1").attr('data-img');
            var image_body_2    = $("#image_body_2").attr('data-img');
            var image_body_3    = $("#image_body_3").attr('data-img');
            var image_body_4    = $("#image_body_5").attr('data-img');
            var g_body          = $("#g_body").val();//描述
            var g_serial        = $("#g_serial").val();//库存
            var g_state         = $("#g_state").attr("checked") ? 1 : 0;//上架
            var g_commend       = $("#g_commend").attr("checked") ? 1 : 0;//推荐
            var g_vat           = $("#g_vat").attr("checked") ? 1 : 0;//发票
            var g_freight       = $("#g_freight").val();//运费

            if(image_body_0 != "" && image_body_0 != undefined && image_body_0 != null){
                g_body = g_body+'<p></p><p><img src="'+image_body_0+'" /></p>';
            }
            if(image_body_1 != "" && image_body_1 != undefined && image_body_1 != null){
                g_body = g_body+'<p></p><p><img src="'+image_body_1+'" /></p>';
            }
            if(image_body_2 != "" && image_body_2 != undefined && image_body_2 != null){
                g_body = g_body+'<p></p><p><img src="'+image_body_2+'" /></p>';
            }
            if(image_body_3 != "" && image_body_3 != undefined && image_body_3 != null){
                g_body = g_body+'<p></p><p><img src="'+image_body_3+'" /></p>';
            }
            if(image_body_4 != "" && image_body_4 != undefined && image_body_4 != null){
                g_body = g_body+'<p></p><p><img src="'+image_body_4+'" /></p>';
            }
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=seller_goods&op=goods_edit",
                data: {
                    key: key,
                    commonid:commonid,
                    cate_id: cate_id,
                    cate_name: cate_name,
                    g_name: g_name,
                    g_jingle: g_jingle,
                    g_price: g_price,
                    g_marketprice: g_marketprice,
                    g_costprice:g_costprice,
                    g_discount:parseInt(g_discount),
                    g_alarm:g_alarm,//库存预警
                    g_storage:g_storage,
                    g_serial:g_serial,
                    g_body:g_body,
                    m_body:'',//wap描述
                    province_id:0,//所在地一级
                    city_id:0,// 所在地二级
                    g_freight:g_freight,//运费
                    freight:0,//运费魔板
                    image_path:image_path,//主图
                    image_all:image_all,
                    g_state: g_state,
                    g_vat: g_vat,//发票
                    sgcate_id:0,//本店编号
                    g_commend: g_commend,
                    b_id:'',//品牌ID
                    b_name:'',//品牌名称
                    g_barcode:'',//商品条形码
                    sup_id:'',//供货商ID
                },
                dataType: "json",
                success: function(a) {
                    if (a) {
                        window.location.href = WapSiteUrl + "/tmpl/seller/goods_list.html";
                    } else {
                        $.sDialog({
                            skin: "red",
                            content: a.datas.error,
                            okBtn: false,
                            cancelBtn: false
                        });
                    }
                }
            })
        }
    });

    $("#gc_info").on("click",
    function() {
        $.areaSelected({
            success: function(a) {
                $("#gc_info").val(a.gc_info).attr({
                    "data-gcid": a.gc_id,
                    "data-cate_name": a.gc_info
                })
            }
        })
    })

});



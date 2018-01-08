;(function($){
    var key = getCookie('seller_key');
    var seller_name = getCookie('seller_name');
    var username = getCookie('username');
    var referurl = document.referrer;
    if (key == null) {
        location.href = WapSiteUrl + '/tmpl/seller/login.html';
    }
    $.sValid.init({
        rules:{
            cate_id: "required",
            cate_name: "required",
            g_name: "required",
            g_price: "required",
            g_marketprice: "required",
            g_storage: "required",
            goods_image_path: "required"
        },
        messages:{
            cate_id: "请选择商品分类",
            cate_name: "请选择商品分类",
            g_name: "请填写商品名称",
            g_price: "请填写商品价格",
            g_marketprice: "请填写市场价格",
            g_storage: "请填写商品库存",
            goods_image_path: "请填写商品图片"
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                errorTipsShow(errorHtml);
            }else{
                errorTipsHide();
            }
        }  
    });

    var key = getCookie("seller_key");

    //提交
    $(".submit").click(function(){
        if($.sValid()){
            var step = $(this).attr('step');
            $.ajax({
                url: ApiUrl + "/index.php?act=seller_goods&op=save_goods",
                type: 'POST',
                dataType: 'json',
                data: $("form").serialize() + '&key=' + key + '&client=' + Client,
                success: function(result){
                    var data = result.datas;
                    if (data.result == 'ok') {
                        var next_step = data.next_step;
                        var cur_step = data.next_step - 1;
                        var html = '<div class="alert-box"><i class="fa fa-check"></i>' + data.next_tips + '</div><a href="goods_add.html" class="input next action-button add-again">继续发布</a><a href="index.html" class="input next action-button add-again">返回首页</a>';
                        $("li.step-" + cur_step).addClass('active');
                        $("fieldset.step-" + cur_step).hide();
                        $("fieldset.step-" + next_step).html(html);
                        $("fieldset.step-" + next_step).show();
                    }
                },
                error: function(error){

                }
            });
            return;
        }
    });
})(Zepto)

;(function($){

    var key = getCookie('seller_key');

    //动态加载
    $.ajax({
        url: ApiUrl + "/index.php?act=seller_goods&op=goods_add",
        type: 'POST',
        dataType: 'json',
        data: {client: Client, key: key},
        success: function(result){
            var data = result.datas;
            var goods_class_html = template.render('goods_class_html', data);
            var goods_class_staple_html = template.render('goods_class_staple_html', data);

            $("#goodsClassStapleArea").html(goods_class_staple_html);
            $("#btn_select_goods_class").html(goods_class_html);
        },
        error: function(){

        }
    });

    //步骤
    var current_fs, next_fs, previous_fs; //fieldsets
    var left, opacity, scale; //fieldset properties which we will animate
    var animating; //flag to prevent quick multi-click glitches
    $(".next").click(function(){
        if(animating) return false;
        animating = true;
        
        current_fs = $(this).parent();
        next_fs = $(this).parent().next();
        
        //activate next step on progressbar using the index of next_fs
        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
        
        //show the next fieldset
        next_fs.show(); 
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                current_fs.hide();
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale current_fs down to 80%
                scale = 1 - (1 - now) * 0.2;
                //2. bring next_fs from the right(50%)
                left = (now * 50)+"%";
                //3. increase opacity of next_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({'transform': 'scale('+scale+')'});
                //next_fs.css({'left': left, 'opacity': opacity});
                next_fs.css({'opacity': opacity});
            }, 
            duration: 800, 
            complete: function(){
                animating = false;
            }, 
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });
    });

    $(".previous").click(function(){
        if(animating) return false;
        animating = true;
        
        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();
        
        //de-activate current step on progressbar
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
        
        //show the previous fieldset
        previous_fs.show(); 
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                current_fs.hide();
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale previous_fs from 80% to 100%
                scale = 0.8 + (1 - now) * 0.2;
                //2. take current_fs to the right(50%) - from 0%
                left = ((1-now) * 50)+"%";
                //3. increase opacity of previous_fs to 1 as it moves in
                opacity = 1 - now;
                //current_fs.css({'left': left});
                previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
            }, 
            duration: 800, 
            complete: function(){
                current_fs.hide();
                animating = false;
            }, 
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });
    });

    $(".submit").click(function(){
        return false;
    });

    $("#goodsClassStapleArea").parent().find('.action-button').removeAttr('disabled').removeClass('disabled');
    $("#msform").delegate("#goodsClassStapleArea", 'change', function() {
        var stapleId = $(this).val();
        if (stapleId > 0) {
            $.ajax({
                url: ApiUrl + "/index.php?act=seller_goods&op=ajax_show_comm",
                type: 'POST',
                dataType: 'json',
                data: {'stapleid': stapleId, 'key': key, client: Client},
                success: function(result) {
                    var data = result.datas.goods_class;
                    var html = template.render('goods_class_staple_select_html', data);
                    $(".gc-wrap.gc-wrap-pre").html(html);
                    $("#goodsClassStapleArea").parent().find('.action-button').removeAttr('disabled').removeClass('disabled');
                    $("#cate_id").val($(".gc-select").eq(2).val());

                    var cate_name = new Array();
                    for (i = 0; i < $(".gc-select").length; i++) {
                        cate_name.push($(".gc-select").eq(i).find("option:selected").text());
                    }
                    $("#cate_name").val(cate_name.join(' &gt;'));
                },
                error: function() {

                }
            });
        } else {
            $("#goodsClassStapleArea").parent().find('.action-button').attr('disabled', 'disabled').addClass('disabled');
        }

    });

    $("#msform").delegate(".gc-select", 'change', function() {
        var index_id = $(this).index();
        var gc_id = $(this).val();
        var deep = index_id + 2;
        var cur_select = $('.gc-select').eq(index_id);
        var next_select = $('.gc-select').eq(index_id + 1);
        if (gc_id > 0 && deep <= 3) {
            next_select.remove();
            $.ajax({
                url: ApiUrl + "/index.php?act=seller_goods&op=ajax_goods_class",
                type: 'POST',
                dataType: 'json',
                data: {'gc_id': gc_id, 'deep': deep, 'key': key, client: Client},
                success: function(result){
                    result.datas.deep = deep;
                    var data = result.datas;
                    var html = template.render('goods_class_html', data);
                    cur_select.after('<select class="input input-select gc-select col-3">' + html + '</select>');
                },
                error: function(){

                }
            });
        }
        if (gc_id == '') {
            for (i = 1; i + index_id <= 2; i++) {
                if (index_id == 0) {
                    $('.gc-select').eq(i).remove();
                }
                if (index_id == 1) {
                    $('.gc-select').eq(2).remove();
                }
            }
        }
        if ($('.gc-select').eq(2).val() > 0) {
            $(this).parent().next('.action-button').removeAttr('disabled').removeClass('disabled');
        } else {
            $(this).parent().next('.action-button').attr('disabled', 'disabled').addClass('disabled');
        }
    });

    /* 商品图片ajax上传 */
    $('#goods_image').fileupload({
        dataType: 'json',
        url: ApiUrl + '/index.php?act=seller_goods&op=ajax_image_upload&upload_type=uploadedfile',
        formData: {name: 'goods_image', key: key, client: Client},
        add: function (e,data) {
            data.submit();
        },
        done: function (e,data) {
            var param = data.result;
            if (typeof(param.error) != 'undefined') {
                alert(param.error);
            } else {
                $('input[name="goods_image"]').after('<div class="input input-thumb input-pay-thumb"><img height="60" src="' + param.thumb_name_60 + '"></div>');
                $('input[name="goods_image_path"]').val(param.name);
            }
        }
    });

    $("#g_price").blur(function(event) {
        discountCalculator();
    });

    $("#g_marketprice").blur(function(event) {
        discountCalculator();
    });

    $("#g_price").change(function(event) {
        discountCalculator();
    });

    $("#g_marketprice").change(function(event) {
        discountCalculator();
    });

    function discountCalculator() {
        var _price = parseFloat($('input[name="g_price"]').val());
        var _marketprice = parseFloat($('input[name="g_marketprice"]').val());
        if((!isNaN(_price) && _price != 0) && (!isNaN(_marketprice) && _marketprice != 0)){
            var _discount = parseInt(_price / _marketprice * 100);
            $('input[name="g_discount"]').val(_discount);
        }
    }
})(jQuery)

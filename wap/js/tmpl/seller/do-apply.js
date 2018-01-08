;(function($){
    var key = getCookie('key');
    var seller_name = getCookie('seller_name');
    var username = getCookie('username');
    var referurl = document.referrer;
    if (key == null) {
        location.href = WapSiteUrl + '/tmpl/member/login.html';
    }
    $.sValid.init({
        rules:{
            seller_name: "required",
            store_name: "required"
        },
        messages:{
            seller_name: "商家账号必须填写!",
            store_name: "店铺名称必须填写!"
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

    var key = getCookie("key");

    //提交
    $(".submit").click(function(){
        if($.sValid()){
            var step = $(this).attr('step');
            $.ajax({
                url: ApiUrl + "/index.php?act=seller_apply&op=do_apply",
                type: 'POST',
                dataType: 'json',
                data: $("form").serialize() + '&key=' + key + '&step=' + step + '&client=' + Client,
                success: function(result){
                    var data = result.datas;
                    if (data.result == 'ok') {
                        var next_step = data.next_step;
                        var cur_step = data.next_step - 1;
                        var html = '<div class="alert-box"><i class="fa fa-check"></i>' + data.next_tips + '</div>';
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

    var key = getCookie('key');

    //动态加载
    $.ajax({
        url: ApiUrl + "/index.php?act=seller_apply&op=do_apply",
        type: 'POST',
        dataType: 'json',
        data: {client: Client, key: key},
        success: function(result){
            var data = result.datas;

            if (data.join_state.step == 4) {
                $("#progressbar li").removeClass('active');
                for (var i = 1; i <= 4; i++) {
                    $("#progressbar li.step-" + i).addClass('active');
                    if (i < 4) {
                        $("fieldset.step-" + i).hide();
                    } else {
                        var html = '<div class="alert-box"><i class="fa fa-check"></i>' + data.join_state.msg + '</div>';
                        $("fieldset.step-" + i).html(html);
                        $("fieldset.step-" + i).show();
                    }
                };
                return;
            }

            if (data.join_state.step == 5) {

                Zepto.sValid.init({
                    rules: {
                        paying_money_certificate_val: "required"
                    },
                    messages: {
                        paying_money_certificate_val: "请上传付款凭证!"
                    },
                    callback:function (eId,eMsg,eRules){
                        if(eId.length >0){
                            var errorHtml = "";
                            Zepto.map(eMsg,function (idx,item){
                                errorHtml += "<p>"+idx+"</p>";
                            });
                            errorTipsShow(errorHtml);
                        }else{
                            errorTipsHide();
                        }
                    }
                });

                $("#progressbar li").removeClass('active');
                for (var i = 1; i <= data.join_state.step; i++) {
                    $("#progressbar li.step-" + i).addClass('active');
                    if (i < data.join_state.step) {
                        $("fieldset.step-" + i).hide();
                    } else {
                        $("fieldset.step-" + i).find('.fs-subtitle').html(data.join_state.msg);
                        $("fieldset.step-" + i).show();
                    }
                };
                return;
            }

            if (data.join_state.step == 6) {
                $("#progressbar li").removeClass('active');
                for (var i = 1; i <= data.join_state.step; i++) {
                    $("#progressbar li.step-" + i).addClass('active');
                    if (i < data.join_state.step) {
                        $("fieldset.step-" + i).hide();
                    } else {
                        $("#progressbar li.step-" + i).removeClass('active');
                        var html = '<div class="alert-box"><i class="fa fa-check"></i>' + data.join_state.msg + '</div>';
                        $("fieldset.step-" + i).html(html);
                        $("fieldset.step-" + i).show();
                    }
                };
                return;
            }

            if (data.join_state.step == 100) {
                location.href = WapSiteUrl + '/tmpl/seller/login.html';
            }

            var sg_html = template.render('sg_html', data);
            var sc_html = template.render('sc_html', data);

            $(".apply-document").html(data.agreement);
            $("#sg_id").html(sg_html);
            $("#sc_id").html(sc_html);
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

    $('input[name="paying_money_certificate"]').fileupload({
        dataType: 'json',
        url: ApiUrl + '/index.php?act=seller_apply&op=ajax_upload_image',
        formData: {client: Client},
        add: function (e,data) {
            data.submit();
        },
        done: function (e,data) {
            if (!data.result){
                alert('上传失败，请尝试上传小图或更换图片格式');return;
            }
            if(data.result.state) {
                $(".input-pay-thumb").remove();
                $('input[name="paying_money_certificate"]').after('<div class="input input-thumb input-pay-thumb"><img height="60" src="'+data.result.pic_url+'"></div>');
                $('input[name="paying_money_certificate_val"]').val(data.result.pic_name);
            } else {
                alert(data.result.message);
            }
        },
        fail: function(){
            alert('上传失败，请尝试上传小图或更换图片格式');
        }
    });

    $('#btn_select_category').on('click', function() {
        $(".gc-wrap-pre").remove();
        $.ajax({
            url: ApiUrl + "/index.php?act=seller_apply&op=get_goods_category",
            type: 'POST',
            dataType: 'json',
            data: {client: Client},
            success: function(result){
                var data = result.datas;
                var html = template.render('gc_html', data);
                $('#btn_select_category').after('<div class="gc-wrap gc-wrap-pre"><select class="input input-select gc-select col-3">' + html + '</select></div>');
            },
            error: function(){

            }
        });
    });

    $("#msform").delegate('.gc-select', 'change', function(event) {
        var index_id = $(this).index();
        var gc_id = $(this).val();
        var cur_select = $('.gc-select').eq(index_id);

        if (index_id == 2) {
            var gc_id_arr = new Array();
            var gc_name_arr = new Array();
            var html = '';
            for (var i = 0; i <= index_id; i++) {
                if (i == 2) {
                    html += '<span>' + $(".gc-select").eq(i).find("option:selected").text() + ' (分佣比例: ' + $(".gc-select").eq(i).find("option:selected").attr('data-commis-rate') + '%)</span>';
                } else {
                    html += '<span>' + $(".gc-select").eq(i).find("option:selected").text() + '</span>';
                }
                gc_id_arr.push($(".gc-select").eq(i).val());
                gc_name_arr.push($(".gc-select").eq(i).find("option:selected").text());
            };
            html += '<span class="handle"><em class="delete-gc" data="' + gc_id_arr.join(",") + '">删除</em></span>';
            $(".gc-result dl").append('<dd>' + html + '</dd><input type="hidden" name="store_class_ids[]" value="' + gc_id_arr.join(",") + '" /><input type="hidden" name="store_class_names[]" value="' + gc_name_arr.join(",") + '" />');
            $(".gc-wrap-pre").remove();
            return;
        } else {
            $.ajax({
                url: ApiUrl + "/index.php?act=seller_apply&op=get_goods_category",
                type: 'POST',
                dataType: 'json',
                data: {client: Client, 'gc_id': gc_id},
                success: function(result){
                    var data = result.datas;
                    var html = template.render('gc_html', data);
                    
                    cur_select.after('<select class="input input-select gc-select col-3">' + html + '</select>');
                },
                error: function(){

                }
            });
        }
    });

    $(".delete-gc").click(function(event) {
        $(this).parent().parent().parent().parent().remove();
    });
})(jQuery)
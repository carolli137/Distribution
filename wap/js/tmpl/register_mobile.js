$(function(){
    //加载验证码
    //loadSeccode();
    $("#refreshcode").bind('click',function(){
        loadSeccode();
    });
    
    $.sValid.init({
        rules:{
//          captcha: {
//          	required:true,
//          	minlength:4
//          },
        	mobile: {
                required:true,
                mobile:true
        	}
        },
        messages:{
//          captcha: {
//          	required : "请填写图形验证码",
//          	minlength : "图形验证码不正确"
//          },
            mobile: {
            	required : "请填写手机号",
                mobile : "手机号码不正确"
            }
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
    // 发送手机验证码
    var mobile = $.trim($("#mobile").val());
    var sec_val = $.trim($("#captcha").val());
    var sec_key = $.trim($("#codekey").val());
    $('#usermobile').html(mobile);
    //send_sms(mobile, sec_val, sec_key);
    $('#send').click(function(){
    	var mobile = $.trim($("#mobile").val());
    	var sec_val = $.trim($("#captcha").val());
    	var sec_key = $.trim($("#codekey").val());
    	if($.sValid()){
    		
        send_sms(mobile, sec_val, sec_key);
         }
    });
    
    $('#refister_mobile_btn').click(function(){
        if (!$(this).parent().hasClass('ok')) {
            return false;
        }
        var mobile = $.trim($("#mobile").val());
        var captcha = $.trim($("#mobilecode").val());
        if (captcha.length == 0) {
            errorTipsShow('<p>请填写验证码<p>');
        }
        check_sms_captcha(mobile, captcha);
        return false;
        
    });
});
// 发送手机验证码
function send_sms(mobile, sec_val, sec_key) {
    $.getJSON(ApiUrl+'/index.php?act=connect&op=get_sms_captcha', {type:1,phone:mobile,sec_val:sec_val,sec_key:sec_key}, function(result){
        if(!result.datas.error){
            $.sDialog({
                skin:"green",
                content:'发送成功',
                okBtn:false,
                cancelBtn:false
            });
            $('.code-again').hide();
            $('.code-countdown').show().find('em').html(result.datas.sms_time);
            var times_Countdown = setInterval(function(){
                var em = $('.code-countdown').find('em');
                var t = parseInt(em.html() - 1);
                if (t == 0) {
                    $('.code-again').show();
                    $('.code-countdown').hide();
                    clearInterval(times_Countdown);
                } else {
                    em.html(t);
                }
            },1000);
        }else{
            //loadSeccode();
            errorTipsShow('<p>' + result.datas.error + '<p>');
        }
    });
}

function check_sms_captcha(mobile, captcha) {
    $.getJSON(ApiUrl + '/index.php?act=connect&op=check_sms_captcha', {type:1,phone:mobile,captcha:captcha }, function(result){
        if (!result.datas.error) {
            window.location.href = 'register_mobile_password.html?mobile=' + mobile + '&captcha=' + captcha;
        } else {
            //loadSeccode();
            errorTipsShow('<p>' + result.datas.error + '<p>');
        }
    });
}
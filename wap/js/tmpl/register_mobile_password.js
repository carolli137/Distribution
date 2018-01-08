$(function(){
    var mobile = getQueryString("mobile");
    var captcha = getQueryString("captcha");
    
    // 显示密码
    $('#checkbox').click(function(){
        if ($(this).prop('checked')) {
            $('#password').attr('type', 'text');
        } else {
            $('#password').attr('type', 'password');
        }
    });

    $.sValid.init({//注册验证
        rules:{
            password:"required"
        },
        messages:{
            password:"密码必填!"
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                errorTipsShow(errorHtml);
            }else{
                errorTipsHide()
            }
        }  
    });
    var register_member = 0;
    $('#completebtn').click(function(){
        if (!$(this).parent().hasClass('ok')) {
            return false;
        }
        var password = $("#password").val();
		if (register_member) {
		    errorTipsShow("<p>正在处理中，请勿重复点击！</p>");
            return false;
        }
        if($.sValid()){
            register_member = 1;
            $.ajax({
                type:'post',
                url:ApiUrl+"/index.php?act=connect&op=sms_register",  
                data:{phone:mobile, captcha:captcha, password:password, client:'wap'},
                dataType:'json',
                success:function(result){
                    if(!result.datas.error){
                        addCookie('username',result.datas.username);
                        addCookie('key',result.datas.key);
                        location.href = WapSiteUrl + '/tmpl/member/member.html';
                    }else{
                        errorTipsShow("<p>"+result.datas.error+"</p>");
                        register_member = 0;
                    }
                }
            });         
        }
    });
});



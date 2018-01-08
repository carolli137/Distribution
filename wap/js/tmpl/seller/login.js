$(function(){
    var key = getCookie('seller_key');
    var seller_name = getCookie('seller_name');
    if (key && seller_name) {
    	delCookie('seller_key');
		delCookie('seller_name');
        window.location.href = WapSiteUrl+'/tmpl/seller/seller.html';
        return;
    }
    //上级网址
    var referurl = document.referrer;
    $.sValid.init({
        rules:{
            username:"required",
            userpwd:"required"
        },
        messages:{
            username:"用户名必须填写！",
            userpwd:"密码必填!"
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
    var allow_submit = true;
    $('#loginbtn').click(function(){//会员登陆
        if (!$(this).parent().hasClass('ok')) {
            return false;
        }
        if (allow_submit) {
            allow_submit = false;
        } else {
            return false;
        }
        var username = $('#username').val();
        var pwd = $('#userpwd').val();
        var client = 'wap';
        if($.sValid()){
            $.ajax({
                type:'post',
                url:ApiUrl+"/index.php?act=seller_login",
                data:{seller_name:username,password:pwd,client:client},
                dataType:'json',
                success:function(result){
                    allow_submit = true;
                    if(!result.datas.error){
                        if(typeof(result.datas.key)=='undefined'){
                            return false;
                        }else{
                            var expireHours = 0;
                            if ($('#checkbox').prop('checked')) {
                                expireHours = 188;
                            }
                            
                            //存储卖家信息
                            addCookie('seller_name',result.datas.seller_name, expireHours);
                            addCookie('store_name',result.datas.store_name, expireHours);
                            addCookie('seller_key',result.datas.key, expireHours);							if(result.datas.mem)							{								var mem=result.datas.mem;								if(mem.username&&mem.key)								{									addCookie('username',mem.username, expireHours);									addCookie('key',mem.key, expireHours);								}							}

                            if (referurl.indexOf('do-applly')) {
                                referurl = WapSiteUrl + '/tmpl/seller/seller.html';
                            }
                            location.href = referurl;
                        }
                        errorTipsHide();
                    }else{
                        errorTipsShow('<p>' + result.datas.error + '</p>');
                    }
                }
            });  
        }
    });
});
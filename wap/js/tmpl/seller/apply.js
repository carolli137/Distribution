$(function(){
    var key = getCookie('seller_key');
    if (key) {
        window.location.href = WapSiteUrl+'/tmpl/seller/index.html';
        return;
    }
    var referurl = document.referrer;//上级网址
    var client = Client;
    $.ajax({
        type:'post',
        url:ApiUrl+"/index.php?act=seller_apply",
        data:{client:client},
        dataType:'json',
        success:function(result){
            var data = result.datas;
            if(!data.error){
                var info = data.result.info;
                var pic_list_html = template.render('pic_list', info);
                $(".adv_list").html(pic_list_html);
                $('.adv_list').each(function() {
                if ($(this).find('.item').length < 2) {
                    return;
                }

                Swipe(this, {
                    startSlide: 2,
                    speed: 400,
                    auto: 3000,
                    continuous: true,
                    disableScroll: false,
                    stopPropagation: false,
                    callback: function(index, elem) {},
                    transitionEnd: function(index, elem) {}
                });
            });
                errorTipsHide();
            }else{
                errorTipsShow('<p>' + result.datas.error + '</p>');
            }
        }
    });
});
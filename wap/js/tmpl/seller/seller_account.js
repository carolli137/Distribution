$(function() { 
    var a = getCookie("seller_key");
	//取数据 
     
    $.ajax({
        type: "post",
        url: ApiUrl + "/index.php?act=seller_store&op=store_info",
        data: {
            key: a
        },
        dataType: "json",
        success: function(a) {
			if(a.code==400){
					
					$.sDialog({
							skin: "red",
							content: a.datas.error,
							okBtn: true,
							okFn:function(){window.location.href = WapSiteUrl + "/tmpl/seller/store_goods_list.html";},
							cancelBtn: false
						});
					return;
			 } 
            checkLogin(a.login); 
            $("#store_qq").val(a.datas.store_info.store_qq); 
            $("#store_ww").val(a.datas.store_info.store_ww); 
            $("#store_keywords").val(a.datas.store_info.store_keywords); 
            $("#store_des").val(a.datas.store_info.store_description);  
            $("#store_phone").val(a.datas.store_info.store_phone);  
			
			$("#store_zy").val(a.datas.store_info.store_zy);
			document.getElementById("store_banner_img").src=a.datas.store_info.store_banner;
			

			}
			 });
			 
$('input[name="file"]').ajaxUploadImage({
            url : ApiUrl + "/index.php?act=seller_store&op=file_upload",
            data:{key:a},
            start :  function(element){
                element.parent().after('<div class="upload-loading"><i></i></div>');
                element.parent().siblings('.pic-thumb').remove();
            },
            success : function(element, result){
                checkLogin(result.login);
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
                element.parent().after('<div class="pic-thumb"><img src="'+result.datas.file_url+'"/></div>');
                element.parent().siblings('.upload-loading').remove();
                element.parents('a').next().val(result.datas.file_name);
            }
        });
			 
    $("#header-nav").click(function() {
        $(".btn").click()
    });
    $(".btn").click(function() {
       
            var e = $("#store_qq").val();
            var r = $("#store_ww").val();
            var i = $("#store_keywords").val();
            var m = $("#store_des").val();
            var n = $("#store_phone").val();
            var o = $("#store_zy").val();
			var b = $("#store_banner").val();
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=seller_store&op=store_edit",
                data: {
                    key: a,
                    store_qq: e,
                    store_ww: r,
                    store_phone: n,
                    store_zy: o,
                    seo_keywords: i,
                    seo_description: m,
					store_banner:b
                },
                dataType: "json",
                success: function(a) {
                    if (a&&a.code==200) {
						$.sDialog({
							skin: "red",
							content: "编辑成功",
							okBtn: true,
							okFn:function(){window.location.href = WapSiteUrl + "/tmpl/seller/seller_account.html";},
							cancelBtn: false
						 }); 
                    } else {
						$.sDialog({
							skin: "red",
							content: "编辑失败"+JSON.stringify(a),
							okBtn: false, 
							cancelBtn: false
						 });  
                    }
                }
            })
         
    });
    
});
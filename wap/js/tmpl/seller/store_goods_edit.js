$(function() { 
    var a = getCookie("seller_key");
   //取数据
    var gid = getQueryString("goods_id");
    $.ajax({
        type: "post",
        url: ApiUrl + "/index.php?act=seller_goods&op=goods_info",
        data: {
            key: a,
            goods_commonid: gid
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
            
            $("#g_name").val(a.datas.goodscommon_info.goods_name);
            $("#g_price").val(a.datas.goodscommon_info.goods_price);	
            $("#g_discount").val(a.datas.goodscommon_info.goods_discount);			
            $("#g_marketprice").val(a.datas.goodscommon_info.goods_marketprice);
            $("#g_storage").val(a.datas.goodscommon_info.g_storage);
            $("#g_serial").val(a.datas.goodscommon_info.goods_serial);
            var tf=a.datas.goodscommon_info.goods_freight;
            tf=(tf/1.5).toFixed(2);
            
            $("#g_freight").val(tf);
            
            $("#g_body").val(a.datas.goodscommon_info.goods_body);

			 
			$('#gs1').attr("checked",false);
			$('#gs2').attr("checked",false);
			//$('#goodstate input[name="g_state"]:eq(1)').attr("checked",'checked'); 
			 $('#gs'+a.datas.goodscommon_info.goods_state).attr("checked",true);
			   
			//$("input:radio[name='g_state']").eq(1).attr("checked",true);
			
			

			//商品分类信息
		    var cat_name=a.datas.goodscommon_info.gc_name.replace(new RegExp("&amp;gt;","g"),">");
            cat_name=cat_name.replace(new RegExp("&gt;","g"),">");
            $("#area_info").val(cat_name).attr({
                "data-cid1": a.datas.goodscommon_info.gc_id,
                "data-cid2": a.datas.goodscommon_info.gc_id_2,
                "data-cid3": a.datas.goodscommon_info.gc_id_3,
                "data-catname": a.datas.goodscommon_info.gc_name
            });
        },error:function (cc) {
			alert(JSON.stringify(cc));
			
		}
   });

 
	$("#g_freight").on("blur",
        function() {
    	    
             var catid=$("#area_info").attr("data-cid1");
			 var minvalue=0;
			 //var alertstr="运费至少3元.";
			 var feelvalue=$(this).val();
			 var sourcefee=0;
			 var grapfee=0;
			
			 //判断物流费数字
			if(!(/^\d+(\.\d+)?$/.test(feelvalue))){
			  alert("运费请输入数字"); 
			  $(this).val("");
			  $(this).focus();
			  $("#feeinfo").html("");
			
			}else{
		
				 if(feelvalue<minvalue){
					  alert('请输入正确物流费用，'+alertstr);
					  $(this).val("");
			  		  $(this).focus();
					  $("#feeinfo").html("");
			
			
				 }else{
				   
				   grapfee= (feelvalue).toFixed(2); 
				   $("#feeinfo").html("总费用："+grapfee);
				 
				 }
			 }
    });
    
//新上传代码
	$.ajax({
	                type: "post",
	                url: ApiUrl + "/index.php?act=seller_goods&op=goods_image_info",
	                data: {
	                    key: a,
	                    goods_commonid: gid,
	                },
	                dataType: "json",
	                success: function(d) {
	                	if(!d.datas.image_list[0]){return false;}
	                	var listimg=d.datas.image_list[0];
						if(listimg.images[0]){ 
	                    $("#image_body_0").attr("value",listimg.images[0].goods_image);
	                    $("#image_body_0").attr("data-img",listimg.images[0].goods_image_url);
	                    $("#file_011").parent("span").after('<div class="pic-thumb"><img src="'+listimg.images[0].goods_image_url+'"/></div>');
	                   }
						if(listimg.images[1]){
	
	                    $("#image_body_1").attr("value",listimg.images[1].goods_image);
	                    $("#image_body_1").attr("data-img",listimg.images[1].goods_image_url);
	                    $("#file_012").parent("span").after('<div class="pic-thumb"><img src="'+listimg.images[1].goods_image_url+'"/></div>');
	                   }
						if(listimg.images[2]){
	
	                    $("#image_body_2").attr("value",listimg.images[2].goods_image);
	                    $("#image_body_2").attr("data-img",listimg.images[2].goods_image_url);
	                    $("#file_013").parent("span").after('<div class="pic-thumb"><img src="'+listimg.images[2].goods_image_url+'"/></div>');
	                   }
						if(listimg.images[3]){
	
	                    $("#image_body_3").attr("value",listimg.images[3].goods_image);
	                    $("#image_body_3").attr("data-img",listimg.images[3].goods_image_url);
	                    $("#file_014").parent("span").after('<div class="pic-thumb"><img src="'+listimg.images[3].goods_image_url+'"/></div>');
	                   }
						if(listimg.images[4]){
	
	                    $("#image_body_4").attr("value",listimg.images[4].goods_image);
	                    $("#image_body_4").attr("data-img",listimg.images[4].goods_image_url);
	                    $("#file_015").parent("span").after('<div class="pic-thumb"><img src="'+listimg.images[4].goods_image_url+'"/></div>');
	                  }
	                }
	    })
	    $('input[name="file"]').ajaxUploadImage({
	            url : ApiUrl + "/index.php?act=seller_album&op=image_upload",
	            data:{
	                key:a,
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


	//提交数据
    $.sValid.init({
        rules: {
            g_name: "required",
            g_price: "required",
            area_info: "required",
            goods_image: "required",
            g_body: "required",
            g_storage: "required",
			goods_image_main: "required",
			g_freight:"required"
				
        },
        messages: {
            g_name: "请填写商品名字",
            g_price: "请填写商品价格！",
            area_info: "请选择商品分类",
            goods_image_main: "请至少上传一张商品主图",
            g_storage: "请填写商品库存",
            g_body: "请填写商品描述",
            g_freight:"输入物流费用"
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
			var g_img_1=$("#image_body_0").val();
			var g_img_2=$("#image_body_1").val();
			var g_img_3=$("#image_body_2").val();
			var g_img_4=$("#image_body_3").val();
			var g_img_5=$("#image_body_4").val();
			var imgall="";
			if(g_img_1!="")
				imgall=imgall+g_img_1+",";
			if(g_img_2!="")
				imgall=imgall+g_img_2+",";
			if(g_img_3!="")
				imgall=imgall+g_img_3+",";
			if(g_img_4!="")
				imgall=imgall+g_img_4+",";
			if(g_img_5!="")
				imgall=imgall+g_img_5+",";
			if(imgall!="")
				imgall=imgall.substring(0,imgall.length-1);
			 
			$.sDialog({
				        autoTime:'10000',
                        skin: "red",
                        content: '正在编辑商品...',
						okBtn: false,
                        cancelBtn: false
                        });
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=seller_goods&op=goods_edit",
                data: {
                    key: a, 
					commonid:gid,
                    cate_id: $("#area_info").attr("data-cid3"),
                    cate_name: $("#area_info").attr("data-catname"),
                    g_name: $("#g_name").val(),
					g_jingle:'',
					b_id:0,
					b_name:'',
                    g_price: $("#g_price").val(),
                    g_marketprice: $("#g_marketprice").val(),
					g_costprice: $("#g_price").val(),
                    g_discount: $("#g_discount").val(),
					image_path:$("#goods_image_main").val(),
					image_all:imgall,
                    g_storage: $("#g_storage").val(),
                    g_serial: $("#g_serial").val(),
					g_alarm:1,
					g_barcode:'',
					attr:'',
					custom:'',
                    g_body: $("#g_body").val(),
                    m_body: $("#g_body").val(),
					starttime:'2017-03-27',
					starttime_H:'00',
					starttime_i:'05',
					province_id:0,
					city_id:0,
					freight:0,
					transport_title:'',
					sgcate_id:'',
					plate_top:0,
					plate_bottom:0,
					g_freight:$("#g_freight").val(),
					g_vat:0,
					g_state:$('#goodstate input[name="g_state"]:checked').val(),
					g_commend:1,
					is_gv:0,
					g_vlimit:0,
					g_vinvalidrefund:0,
					sup_id:0,
					type_id:0
					  
                },
                dataType: "json",
                success: function(a) { 
				  
				//alert(JSON.stringify(a));
                    if (a.code==200) {
					   $.sDialog({
                        skin: "red",
                        content: '商品编辑成功！',
                        okBtn: true,
                        cancelBtn: true,
						okFn:function(){window.location.href = WapSiteUrl + "/tmpl/seller/store_goods_list.html";},
						cancelFn:function(){window.location.reload();}
                        });
                        
                    } else {
                        $.sDialog({
                        skin: "red",
                        content: '编辑失败:'+JSON.stringify(a),
                        okBtn: true,
                        cancelBtn: false
                        });
						//location.href = WapSiteUrl
                    }
                },error:function(e){
                   				 
				   alert(JSON.stringify(e));
				}
            })
        }
    });
	$("#g_marketprice").blur(function(){

     var dis=$(this).val();
	
	 var gp= $("#g_price").val(); 
	 
	 if(gp==""){
		 gp=0;
		 $(this).val(0);
	 }
	 
	 //if(/^[0-9]+.?[0-9]*$/.test(dis)&&dis<10){
	 if(/^[0-9]+.?[0-9]*$/.test(dis)){
	   $("#g_discount").val(Math.round((gp/dis)*100));
	 }else{
	              $.sDialog({
                        skin: "red",
                        content: '请输入数字',
                        okBtn: true,
						okFn:function(){$("#g_marketprice").val("");},
                        cancelBtn: false
                    });
	 }
    
	});
    $("#area_info").on("click",
    function() {
        $.goodsClassSelected({
            success: function(a) { 
                $("#area_info").val(a.area_info).attr({
                    "data-cid1":  a.area_id_1,
                    "data-cid2":  a.area_id_2,
                    "data-cid3":  a.area_id_3,
                    "data-catname": a.area_info
                })
            }
        })
    })
});
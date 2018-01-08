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
			
			//商品主图信息
            if(a.datas.goodscommon_info.goods_image_url){
			   //显示图片
			    $("#goods_image_main").parent().find("span").after('<div class="pic-thumb"><img src="' + a.datas.goodscommon_info.goods_image_url + '"/></div>');
			   //设置值
			    $("#goods_image_main").val(a.datas.goodscommon_info.goods_image);


			}
			//商品图片信息 ,暂时不处理不同规格的图片信息
             $.ajax({
				type: "post",
				url: ApiUrl + "/index.php?act=seller_goods&op=goods_image_info",
				data: {
					key: getCookie("seller_key"),
					goods_commonid: gid
				},
				dataType: "json",
				success: function(f) {
					if(f.code==200){
					  var imglistall=f.datas.image_list[0].images;  //这里因为没有处理规格，所以这样取 
					  if(imglistall.length>0){ 
					   for(j=0;j<imglistall.length;j++){ 
					      $("#goods_image"+(j+1)).parent().find("span").after('<div class="pic-thumb"><img src="' + imglistall[j].goods_image_url + '"/></div>'); 
			              $("#goods_image"+(j+1)).val(imglistall[j].goods_image);

					   }
					  }
					 
					}
					 
					
					}
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
			 //家电类物流费至少10元，其余至少3元
			
				 if(catid==308){
				   minvalue=0;
				  // alertstr="家电类商品运费至少10元."; 
				 } 
			
				 if(feelvalue<minvalue){
					  alert('请输入正确物流费用，'+alertstr);
					  $(this).val("");
			  		  $(this).focus();
					  $("#feeinfo").html("");
			
			
				 }else{
				   
				   grapfee= (feelvalue).toFixed(2);  //众包费 
				   $("#feeinfo").html("总费用："+grapfee);
				 
				 }
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
			var g_img_1=$("#goods_image1").val();
			var g_img_2=$("#goods_image2").val();
			var g_img_3=$("#goods_image3").val();
			var g_img_4=$("#goods_image4").val();
			var g_img_5=$("#goods_image5").val();
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
	$("#g_discount").blur(function(){

     var dis=$(this).val();
	
	 var gp= $("#g_price").val(); 
	 
	 if(gp==""){
		 gp=0;
		 $(this).val(0);
	 }
	 
	 if(/^[0-9]+.?[0-9]*$/.test(dis)&&dis<10){
	   $("#g_marketprice").val(Math.round((gp/dis)*10));
	 }else{
	              $.sDialog({
                        skin: "red",
                        content: '请输入数字或者折扣不能大于10',
                        okBtn: true,
						okFn:function(){$("#g_discount").val("");},
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
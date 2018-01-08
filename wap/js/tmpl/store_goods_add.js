$(function() { 
    var a = getCookie("seller_key");
    $.sValid.init({
        rules: {
            g_name: "required",
            g_price: "required",
            g_discount: "required",
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
			g_discount:"请输入折扣",
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
				   //alertstr="家电类商品运费至少10元."; 
				 } 
			
				 if(feelvalue<minvalue){
					  alert('请输入正确物流费用，'+alertstr);
					  $(this).val("");
			  		  $(this).focus();
					  $("#feeinfo").html("");
			
			
				 }else{
				   
				   grapfee= (feelvalue).toFixed(2);  //众包费 
				   $("#feeinfo").html("众包费："+(feelvalue).toFixed(2)+"元，总费用："+grapfee);
				 
				 }
			 }
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
                        content: '正在发布商品...',
						okBtn: false,
                        cancelBtn: false
                        });
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=seller_goods&op=goods_add",
                data: {
                    key: a, 
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
                    if (a.code==200) {
					   $.sDialog({
                        skin: "red",
                        content: '商品发布成功！',
                        okBtn: true,
                        cancelBtn: true,
						okFn:function(){window.location.href = WapSiteUrl + "/tmpl/seller/store_goods_list.html";},
						cancelFn:function(){window.location.reload();}
                        });
                        
                    } else {
                        $.sDialog({
                        skin: "red",
                        content: '发布失败:'+JSON.stringify(a),
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
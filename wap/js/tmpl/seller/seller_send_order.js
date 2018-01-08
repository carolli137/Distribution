$(function() {	
    var e = getCookie("seller_key");
    var orderid = getQueryString("orderid");
    if (!e) {
        location.href = "login.html"
    }
    function s() {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=seller_express&op=get_mylist", 
            data: {
				order_id:orderid,
                key: e
            },
            dataType: "json",
            success: function(ed) {
                //checkLogin(ed.login);  
                var t = template.render("sshipping_list", ed);
                $("#shipping_list").empty();
                $("#shipping_list").append(t);
				
				
                $("#order_step1").empty();
                $("#order_step1").append(template.render("orderstep1", ed.datas));
				
               // $("#order_step2").empty();
               // $("#order_step2").append(template.render("orderstep2", ed.datas));
			
  


                $(".btn-l").click(function() {
                     
                    $.sDialog({
                        skin: "block",
                        content: "确定发货？",
                        okBtn: true,
                        cancelBtn: true,
                        okFn: function() {
                            saveexpress();
                        }
                    })
                })
            } 
        });
		
		
				//默认发货地址 
				 $.ajax({
					type: "post",
					url: ApiUrl + "/index.php?act=seller_express&op=get_defaultexpress",
					data: {
						order_id:orderid,
						key: e
					},
					dataType: "json",
					success: function(f) {
						checkLogin(e.login);  
						if(!f.datas.daddress_info)
						{
							$.sDialog({
                        skin: "block",
                        content: "请先设置发货地址后，再来发货",
                        okBtn: true,
                        cancelBtn: false,
                        okFn: function() {
                            window.location.href="seller_address_opera.html";
                        }
						});
						return false;
						}
						var g = template.render("saddress_list", f.datas);
						$("#address_list").empty();
						$("#address_list").append(g);
						
						 $("#order_step2").empty();
                		 $("#order_step2").append(template.render("orderstep2", f.datas));

					}
				 });
		
    }
    s();
	
	$(".ncbtn").click(function() {
       checkLogin(e.login);  
		$.ajax({
			type: "post",
			url: ApiUrl + "/index.php?act=seller_order&op=order_deliver_send",
			data: {
				order_id: orderid, 
				key: e
			},
			dataType: "json",
			success: function(e) {
				checkLogin(e.login);
				if (e) { 
					$.sDialog({
						skin: "block",
						content: "发货成功!",
						okBtn: true,
						cancelBtn: true,
						okFn: function() {
							window.location.href="store_orders_list.html?data-state=state_send";
						}
					})

				}else{


					$.sDialog({
						skin: "block",
						content: "发货失败!",
						okBtn: true,
						cancelBtn: true 
					})

				}
			}
		  })
		
    });
	
    $("#shipping_list").on("click", ".send-order",saveexpress);  
    function saveexpress() {
		   var expressid = $(this).attr("express_id");
	       var expresssn=$("#sc"+expressid).val(); 
		   var daddress_id=$("#shippingid").val();
	       //alert(expressid+":"+expresssn);
		  if(expresssn==""){

			  
							$.sDialog({
								skin: "block",
								content: "请输入单号!",
								okBtn: true,
								cancelBtn: false
								 
							})
		  
		  }else{
			            $.ajax({
							type: "post",
							url: ApiUrl + "/index.php?act=seller_order&op=order_deliver_send",
							data: {
								order_id: orderid,
								shipping_express_id:expressid,
								shipping_code:expresssn,
								daddress_id:daddress_id,
								key: e
							},
							dataType: "json",
							success: function(e) {
								checkLogin(e.login);
								if (e) {
 
									$.sDialog({
										skin: "block",
										content: "发货成功!",
										okBtn: true,
										cancelBtn: true,
										okFn: function() {
											window.location.href="store_orders_list.html?data-state=state_send";
										}
									})
  
								}else{
								
									
									$.sDialog({
										skin: "block",
										content: "发货失败!",
										okBtn: true,
										cancelBtn: true 
									})
								
								}
							}
						  }) 
						 
		  }
    }
});

TouchSlide( { slideCell:"#tabBox1",
	endFun:function(i){ //高度自适应
		var bd = document.getElementById("tabBox1-bd");
		//bd.parentNode.style.height = bd.children[i].children[0].offsetHeight+"px";
		if(i>0)bd.parentNode.style.transition="200ms";//添加动画效果
	}
});
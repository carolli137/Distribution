$(function(){
    var special_id = getQueryString('special_id');
    loadSpecial(special_id);
})

function loadSpecial(special_id){
    $.ajax({
        url: ApiUrl + "/index.php?act=index&op=special&special_id=" + special_id,
        type: 'get',
        dataType: 'json',
        success: function(result) {
            $('title,h1').html(result.datas.special_desc);
            var data = result.datas.list;
            var html = '';

            $.each(data, function(k, v) {
                $.each(v, function(kk, vv) {
                    switch (kk) {
                        case 'adv_list':
                        case 'home3':
                            $.each(vv.item, function(k3, v3) {
                                vv.item[k3].url = buildUrl(v3.type, v3.data);
                            });
                            break;

                        case 'home1':
                            vv.url = buildUrl(vv.type, vv.data);
                            break;

                        case 'home2':
                        case 'home4':
                            vv.square_url = buildUrl(vv.square_type, vv.square_data);
                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data);
                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data);
                            break;
                    }
                    html += template.render(kk, vv);
                    return false;
                });
            });

            $("#main-container").html(html);

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

        }
    });

}

// 5.2 打折 显示时间
function takeCount() {
	setTimeout("takeCount()", 1E3);
	$(".time-remain").each(function() {
		var b = $(this),
			a = b.attr("count_down");
		if (0 < a) {
			var a = parseInt(a) - 1,
				e = Math.floor(a / 86400),
				c = Math.floor(a / 3600) % 24,
				g = Math.floor(a / 60) % 60,
				f = Math.floor(a / 1) % 60;
			0 > e && (e = 0);
			0 > c && (c = 0);
			0 > g && (g = 0);
			0 > f && (f = 0);
			b.find("[time_id='d']").html(e);
			b.find("[time_id='h']").html(c);
			b.find("[time_id='m']").html(g);
			b.find("[time_id='s']").html(f);
			b.attr("count_down", a)
		}
	})
}

$(function() {
	setTimeout("takeCount()", 1E3);
	$('.xianshi-list').each(function() {
	if ($(this).find('.item').length < 2) {
				 $(".xianshi").jfocus({
				time: 8E3
			});
		}
	});
});

<?php defined('In33hao') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<!-- 公司信息 -->

<div id="apply_company_info" class="apply-company-info">
  <div class="alert">
    <h4>注意事项：</h4>
    以下所需要上传的电子版资质文件仅支持JPG\GIF\PNG格式图片，大小请控制在1M之内。</div>
  <form id="form_company_info" action="index.php?act=store_joininc&op=step2" method="post" enctype="multipart/form-data" >
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <thead>
        <tr>
          <th colspan="2">店铺及联系人信息</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th><i>*</i>店铺名称：</th>
          <td><input name="company_name" type="text" class="w200"/>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>所在地：</th>
          <td><input id="company_address" name="company_address" type="hidden" value=""/>
          <input type="hidden" value="" name="province_id" id="province_id">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>详细地址：</th>
          <td><input name="company_address_detail" type="text" class="w200">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>联系人姓名：</th>
          <td><input name="contacts_name" type="text" class="w100" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>联系人手机：</th>
          <td><input name="contacts_phone" type="text" class="w100" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>电子邮箱：</th>
          <td><input name="contacts_email" type="text" class="w200" />
            <span></span></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <thead>
        <tr>
          <th colspan="20">身份证信息</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th><i>*</i>姓名：</th>
          <td><input name="business_sphere" type="text" class="w100" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>身份证号：</th>
          <td><input name="business_licence_number" type="text" class="w200" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>身份证正面扫描件：</th>
          <td><input name="organization_code_electronic" type="file" class="w60" />
            <span class="block">请确保图片清晰，身份证上文字可辨（清晰照片也可使用）。</span>
            <input name="organization_code_electronic1" type="hidden"/><span></span>
            </td>
        </tr>
        <tr>
          <th class="w150"><i>*</i>身份证反面扫描件：</th>
          <td><input name="general_taxpayer" type="file" class="w60" />
            <span class="block">请确保图片清晰，身份证上文字可辨（清晰照片也可使用）。</span>
            <input name="general_taxpayer1" type="hidden"/><span></span>
            </td>
        </tr>
        <tr>
          <th><i>*</i>手执身份证照片：</th>
          <td><input name="business_licence_number_elc" type="file" class="w60" />
          	
            <span class="block">请确保图片清晰，身份证上文字可辨（清晰照片也可使用）。<br><img border="0" alt="手执身份证照范例" src="<?php echo SHOP_TEMPLATES_URL;?>/images/example.jpg" style="width:300px;height:210px"></span>
	    <input name="business_licence_number_elc1" type="hidden"/><span></span>
	    
	    </td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
  </form>
  <div class="bottom"><a id="btn_apply_company_next" href="javascript:;" class="btn">下一步，提交财务资质信息</a></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	<?php foreach (array('business_licence_number_elc','organization_code_electronic','general_taxpayer') as $input_id) { ?>
    $('input[name="<?php echo $input_id;?>"]').fileupload({
        dataType: 'json',
        url: '<?php echo urlShop('store_joinin', 'ajax_upload_image');?>',
        formData: '',
        add: function (e,data) {
            data.submit();
        },
        done: function (e,data) {
            if (!data.result){
            	alert('上传失败，请尝试上传小图或更换图片格式');return;
            }
            if(data.result.state) {
            	$('input[name="<?php echo $input_id;?>"]').nextAll().remove('img');
            	$('input[name="<?php echo $input_id;?>"]').after('<img height="60" src="'+data.result.pic_url+'">');
            	$('input[name="<?php echo $input_id;?>1"]').val(data.result.pic_name);
            } else {
            	alert(data.result.message);
            }
        },
        fail: function(){
        	alert('上传失败，请尝试上传小图或更换图片格式');
        }
    });
    <?php } ?>
    $('#company_address').nc_region();
    $('#business_licence_address').nc_region();
    
    $('#business_licence_start').datepicker();
    $('#business_licence_end').datepicker();

    $('#btn_apply_agreement_next').on('click', function() {
        if($('#input_apply_agreement').prop('checked')) {
            $('#apply_agreement').hide();
            $('#apply_company_info').show();
        } else {
            alert('请阅读并同意协议');
        }
    });

    $('#form_company_info').validate({
        errorPlacement: function(error, element){
            element.nextAll('span').first().after(error);
        },
        rules : {
            company_name: {
                required: true,
                maxlength: 50 
            },
            company_address: {
                required: true,
                maxlength: 50 
            },
            company_address_detail: {
                required: true,
                maxlength: 50 
            },
            company_phone: {
                required: true,
                maxlength: 20 
            }, 
            contacts_name: {
                required: true,
                maxlength: 20 
            },
            contacts_phone: {
                required: true,
                maxlength: 20 
            },
            contacts_email: {
                required: true,
                email: true 
            },
            business_licence_number: {
                required: true,
                maxlength: 20
            },
            business_sphere: {
                required: true,
                maxlength: 500
            },
			organization_code_electronic1: {
                required: true
            },
			general_taxpayer1: {
                required: true
            },
            business_licence_number_elc1: {
                required: true
            },
        },
        messages : {
            company_name: {
                required: '请输入店铺名字',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            company_address: {
                required: '请选择区域地址',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            company_address_detail: {
                required: '请输入目前详细住址或办公地址',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            contacts_name: {
                required: '请输入联系人姓名',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            contacts_phone: {
                required: '请输入联系人电话',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            contacts_email: {
                required: '请输入常用邮箱地址',
                email: '请填写正确的邮箱地址'
            },
            business_licence_number: {
                required: '请输入身份证号',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            business_sphere: {
                required: '请填写身份证上姓名',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
			organization_code_electronic1: {
                required: '请选择上传身份证正面扫描件'
            },
			general_taxpayer1: {
                required: '请选择上传身份证反面扫描件'
            },
            business_licence_number_elc1: {
                required: '请选择上传手执身份证照'
            },
        }
    });

    $('#btn_apply_company_next').on('click', function() {
        if($('#form_company_info').valid()) {
        	$('#province_id').val($("#company_address").fetch('area_id_1'));
            $('#form_company_info').submit();
        }
    });
});
</script>
<?php
/**
 * 新增快递公司 5.2
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.suibianlu.com)
 * @license    http://www.sui bian lu.c om
 * @link       交流群号：575710573
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="index.php?act=express&op=index" title="返回快递公司列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>快递公司 - <?php echo $lang['nc_new'];?></h3>
                <h5>新增快递公司</h5>
            </div>
        </div>
    </div>
    <form id="express_form" enctype="multipart/form-data" method="post">
        <input type="hidden" name="form_submit" value="ok" />
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="e_name"><em>*</em>公司名称</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="" name="e_name" id="e_name" maxlength="20" class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="e_code"><em>*</em>公司编号</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="" name="e_code" id="e_code" class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
	    <dl class="row">
                <dt class="tit">
                    <label for="e_code_kdniao"><em>*</em>快递鸟快递公司代码</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="" name="e_code_kdniao" id="e_code_kdniao" class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="e_letter"><em>*</em>名称首字母</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="" name="e_letter" id="e_letter" maxlength="1" class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="e_url">公司网址</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="" name="e_url" id="e_url" class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">是否启用</dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="express_state1" class="cb-enable selected">是</label>
                        <label for="express_state2" class="cb-disable">否</label>
                        <input type="radio" checked="checked" value="1" name="e_state" id="express_state1">
                        <input type="radio" value="0" name="e_state" id="express_state2">
                    </div>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">是否常用</dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="express_order1" class="cb-enable">常用</label>
                        <label for="express_order2" class="cb-disable selected">不常用</label>
                        <input type="radio" value="1" name="e_order" id="express_order1">
                        <input type="radio" checked="checked" value="2" name="e_order" id="express_order2">
                    </div>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">自提站配送</dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="express_zt_state1" class="cb-enable">是</label>
                        <label for="express_zt_state2" class="cb-disable selected">否</label>
                        <input type="radio" value="1" name="e_zt_state" id="express_zt_state1">
                        <input type="radio" checked="checked" value="0" name="e_zt_state" id="express_zt_state2">
                    </div>
                    <p class="notic"></p>
                </dd>
            </dl>
            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(function(){
        $("#submitBtn").click(function(){
            if($("#express_form").valid()){
                $("#express_form").submit();
            }
        });
    });
    $(document).ready(function(){
        $('#express_form').validate({
            errorPlacement: function(error, element){
                var error_td = element.parent('dd').children('span.err');
                error_td.append(error);
            },
            rules : {
                e_name : {
                    required   : true
                },
                e_code : {
                    required   : true
                },
		e_code_kdniao : {
                    required   : true
                },
                e_letter : {
                    required   : true
                }
            },
            messages : {
                e_name : {
                    required : '<i class="fa fa-exclamation-circle"></i>快递公司名称不能为空'
                },
                e_code : {
                    required : '<i class="fa fa-exclamation-circle"></i>快递公司编号不能为空'
                },
		e_code_kdniao : {
                    required : '<i class="fa fa-exclamation-circle"></i>快递鸟快递公司代码不能为空'
                },
                e_letter  : {
                    required   : '<i class="fa fa-exclamation-circle"></i>快递公司首字母不能为空'
                }
            }
        });
    });
</script>
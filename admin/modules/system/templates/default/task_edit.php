<?php
/**
 * 计划任务
 *
 * 好商城v5.3 by suibianlu.com
**/
defined('In33hao') or exit('Access Invalid!');
?>
<style type="text/css">
	.table { clear:both; width:100%; margin-top: 8px}
	.table th, .table td{ padding:6px !important; height:30px; border-bottom: 1px solid#f0f0f0;}
	.nohover td { background:#FFF !important;}
	.tb-type2{}
	.tb-type2 tr.hover:hover .tips2{ color:#333; }
	.tb-type2 td, tb-type2 th.td{ padding:5px 5px 3px 0;  }
	.tb-type2 .tfoot td {padding:5px 5px 3px 0;}
	.tb-type2 th{ padding:5px 5px 3px 0; line-height:21px; font-size: 12px; text-align: right}
	.tb-type2 .smtxt { margin-right: 5px; width: 25px; }
	.nowrap { white-space: nowrap; }
	.tb-type2 .required { padding:3px 0 3px 5px; font-weight:700; }
	.tb-type2 .required span { line-height: 20px; font-weight: normal; color: #777; margin-left: 16px;}
	.tb-type2 .required span .checkbox { vertical-align: middle; margin-right: 4px;}
	.tb-type2 .rowform{ padding-left:5px; vertical-align: middle;}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>计划任务</h3>
        <h5>计划触发相关设置</h5>
      </div> <ul class="tab-base nc-row">
      <li><a href="?act=task&op=list"><span><?php echo $lang['nc_list'];?></span></a></li>
      <li><a href="javascript:;" class="current"><span><?php echo $lang['nc_edit'];?></span></a></li>
      <li><a href="?act=task&op=add"><span><?php echo $lang['nc_add'];?></span></a></li>
      </ul>
	  </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="edit_form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="task_id" value="<?php echo $output['task']['id'];?>" />
    <table class="table tb-type2">
      <tbody>
        <tr>
          <th width="10%" class="required">任务名称：</th>
          <td class="vatop rowform"><input type="text" name="taskname" id="taskname" class="txt" value="<?php echo $output['task']['taskname'];?>" /></td>
        </tr>
        <tr>
          <th class="required">运行程序：</th>
          <td class="vatop rowform"><input name="dourl" type="text" id="dourl" class="txt" value="<?php echo $output['task']['dourl'];?>" />
            <span style="color:#999;padding:5px 0;">程序必须放在跟目录 crontab 目录中，因此填写文件名即可</span></td>
        </tr>
        <tr>
          <th class="required">任务说明：</th>
          <td class="vatop rowform"><textarea style="width: 250px;" name="description" id="description"><?php echo $output['task']['description'];?></textarea></td>
        </tr>
        <tr>
          <th class="required">是否启用：</th>
          <td class="vatop rowform onoff"><label for="islock_enable" class="cb-enable<?php echo $output['task']['islock'] == 0?' selected':'';?>" title="<?php echo $lang['nc_yes'];?>"><span><?php echo $lang['nc_yes'];?></span></label>
            <label for="islock_disabled" class="cb-disable<?php echo $output['task']['islock'] == 1?' selected':'';?>" title="<?php echo $lang['nc_no'];?>"><span><?php echo $lang['nc_no'];?></span></label>
            <input type="radio" name="islock" id="islock_enable" value="0"<?php echo $output['task']['islock'] == 0?' checked':'';?>>
            <input type="radio" name="islock" id="islock_disabled" value="1"<?php echo $output['task']['islock'] == 1?' checked':'';?>></td>
        </tr>
        <tr>
          <th class="required">循环方式：</th>
          <td class="vatop rowform onoff"><label for="runtype_enable" class="cb-enable<?php echo $output['task']['runtype'] == 0?' selected':'';?>" title="循环"><span>循环</span></label>
            <label for="runtype_disabled" class="cb-disable<?php echo $output['task']['runtype'] == 1?' selected':'';?>" title="一次性"><span>一次性</span></label>
            <input type="radio" name="runtype" id="runtype_enable" value="0"<?php echo $output['task']['runtype'] == 0?' checked':'';?>>
            <input type="radio" name="runtype" id="runtype_disabled" value="1"<?php echo $output['task']['runtype'] == 1?' checked':'';?>></td>
        </tr>
        <tr>
          <th class="required">执行时间：</th>
          <td class="vatop rowform"><?php list($h, $m, $s) = explode(':', $output['task']['runtime']);?>
            <input type="text" id="h" name="h" class="txt" style="width:30px" value="<?php echo $h;?>">
            时（24小时制）
            <input type="text" name="i" id="i" class="txt" style="width:30px" value="<?php echo $m;?>">
            分
            <input type="text" name="s" id="s" class="txt" style="width:30px" value="<?php echo $s;?>">
            秒</td>
        </tr>
        <tr>
          <th class="required">执行周期：</th>
          <td class="vatop rowform"><input name="freq" type="radio" value="1"<?php if($output['task']['freq']==1) echo ' checked';?>>
            分&nbsp;
            <input type="radio" name="freq" value="60"<?php if($output['task']['freq']==60) echo ' checked';?>>
            时&nbsp;
            <input name="freq" type="radio" value="1440"<?php if($output['task']['freq']==1440) echo ' checked';?>>
            天&nbsp;
            <input type="radio" name="freq" value="10080"<?php if($output['task']['freq']==10080) echo ' checked';?>>
            周&nbsp;
            <input type="radio" name="freq" value="43200"<?php if($output['task']['freq']==43200) echo ' checked';?>>
            月</td>
        </tr>
        <tr>
          <th class="required">开始时间：</th>
          <td class="vatop rowform"><input type="text" name="starttime" id="starttime" class="txt date" value="<?php echo (empty($output['task']['starttime']) ? '' : date('Y-m-d',$output['task']['starttime']));?>"></td>
        </tr>
        <tr>
          <th class="required">结束时间：</th>
          <td class="vatop rowform"><input type="text" name="endtime" id="endtime" class="txt date" value="<?php echo (empty($output['task']['endtime']) ? '' : date('Y-m-d',$output['task']['endtime']));?>">
            <span style="color:#999;padding:5px 0;">不限不要填写</span></td>
        </tr>
        <tr>
          <th class="required"> 附加参数： </th>
          <td class="vatop rowform"><textarea style="width: 250px;" name="parameter" id="parameter"><?php echo $output['task']['parameter'];?></textarea>
            <p style="color:#999;padding:5px 0;">通过 get 方式向运行的程序发送的参数，格式为：<b>key=value</b></p></td>
        </tr>
        <tr>
          <th class="required">上次运行：</th>
          <td class="vatop rowform"><?php echo (empty($output['task']['lastrun']) ? '没运行过' : date('Y-m-d H:i:s',$output['task']['lastrun']));?></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
			<td>&nbsp;</td>
          <td><a href="javascript:;" class="ncap-btn-big ncap-btn-green" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a><a href="javascript:;" class="ncap-btn-big ml10" id="resetBtn"><span><?php echo $lang['nc_reset'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript">
$(function(){$("#submitBtn").click(function(){
    if($("#edit_form").valid()){
     $("#edit_form").submit();
	}
	});
});
$(function(){$("#resetBtn").click(function(){
     $("input").val(''); 
     $("textarea").val(''); 
	});
});
$(function(){
    $('#starttime').datepicker();
    $('#endtime').datepicker();
});
$(document).ready(function(){
	$("#edit_form").validate({
		errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().find('td:first'));
        },
        rules : {
        	taskname: {
        		required : true
        	},
        	dourl: {
        		required : true
        	},
        	h: {
        		required : true,
        		min:0,
        		max:24
        	},
        	i: {
        		required : true,
        		min:0,
        		max:60
        	},
        	s: {
        		required : true,
        		min:0,
        		max:60
        	}
        },
        messages : {
        	taskname: {
        		required : '任务名称不能为空'
        	},
        	dourl: {
        		required : '运行程序不能为空'
        	},
        	h: {
        		required : '时不能为空',
        		min:'时数值不对',
        		max:'时数值不对'
        	},
        	i: {
        		required : '分不能为空',
        		min:'分数值不对',
        		max:'分数值不对'
        	},
        	s: {
        		required : '秒不能为空',
        		min:'秒数值不对',
        		max:'秒数值不对'
        	}
        }
	});
});
</script> 

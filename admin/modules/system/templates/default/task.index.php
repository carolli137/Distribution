<?php
/**
 * 计划任务
 *
 * 好商城v5.3 by suibianlu.com
**/
defined('In33hao') or exit('Access Invalid!');
?>

<div class="page">
 <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>计划任务</h3>
        <h5>计划触发相关设置</h5>
      </div> <ul class="tab-base nc-row"><li><a class="current"><span><?php echo $lang['nc_list'];?></span></a></li><li><a href="index.php?act=task&op=add"><span><?php echo $lang['nc_add'];?></span></a></li></ul>    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="提示相关设置操作时应注意的要点"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="收起提示"></span>
    </div>
    <ul>
      <li><?php echo $lang['task_tips1'];?></li>
      <li><?php echo $lang['task_tips2'];?></li>
      <li><?php echo $lang['task_tips3'];?></li>
    </ul>
  </div>
  <form id="list_form" method="post" enctype="multipart/form-data">
  <input name="dopost" type="hidden" value="ok">
  <table class="flex-table">
    <thead>
      <tr>
        <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
        <th width="120" class="handle" align="center"><?php echo $lang['nc_handle'];?></th>
        <th width="60" class="align-center">任务ID</th>
        <th width="80">任务名称</th>
        <th width="80">任务程序</th>
        <th width="360">任务说明</th>
        <th width="80" align="left">是否启用</th>
        <th width=80 align="left">运行时间</th>
        <th width="150" align="left">上次运行</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
       <?php if(!empty($output['task_list']) && is_array($output['task_list'])){ ?>
       <?php foreach($output['task_list'] as $k => $v){ ?>
      <tr>
        <td class="sign"><i class="ico-check"></i></td>
        <td class="handle"><a class="btn blue" href="?act=task&op=edit&id=<?php echo $v['id']; ?>'><?php echo $lang['nc_edit'];?></a>"><i class="fa fa-pencil-square-o"></i><?php echo $lang['nc_edit'];?></a>
        <a href="javascript:;" onClick="(window.confirm('你确实要删除这个任务么！') ? location='?act=task&op=del&id=<?php echo $v['id']; ?>' : '')" class="btn red"><i class="fa fa-trash-o"></i><?php echo $lang['nc_del'];?></a>
        </td>
        <td><?php echo $v['id']; ?></td>
		<td><?php echo $v['taskname']; ?></td>
        <td><?php echo $v['dourl']; ?></td>
        <td><?php echo $v['description']; ?></td>
        <td class="align-center"><?php echo ($v['islock']==0 ? '启用' : '禁用'); ?></td>
        <td class="align-center"><?php echo $v['runtime']; ?></td>
        <td class="align-center"><?php echo (empty($v['lastrun']) ? '没运行过' : date('Y-m-d H:i:s',$v['lastrun'])); ?></td>
        <td></td>
      </tr>
      <?php } ?>
      <?php }else { ?>
      <tr class="no-data">
        <td colspan="100" class="no-data"><i class="fa fa-lightbulb-o"></i><?php echo $lang['nc_no_record'];?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
   </form>
</div>
<script>
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped: true,// 使用斑马线
		resizable: false,// 不调节大小
		title: '<?php echo $lang['nc_list'];?>',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false// 不使用列控制      
		});
});
</script>
</div>

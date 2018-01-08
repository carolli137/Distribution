<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>分销管理</h3>
        <h5>分销规则操作</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>在这里可以设置分销佣金比例。</li>
    </ul>
  </div>
  <form method="post" enctype="multipart/form-data" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="hao_invite2">二级佣金比</label>
        </dt>
        <dd class="opt">
          <input id="hao_invite2" name="hao_invite2" value="<?php echo $output['list_setting']['hao_invite2'];?>" class="w60" type="text" /><i>%</i>
          <p class="notic">二级佣金=1级佣金*二级佣金比</p>
        </dd>
      </dl>
             <dl class="row">
        <dt class="tit">
          <label for="hao_invite3">三级佣金比</label>
        </dt>
        <dd class="opt">
          <input id="hao_invite3" name="hao_invite3" value="<?php echo $output['list_setting']['hao_invite3'];?>" class="w60" type="text" /><i>%</i>
          <p class="notic">三级佣金=1级佣金*三级佣金比</p>
        </dd>
      </dl>
       
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.form1.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
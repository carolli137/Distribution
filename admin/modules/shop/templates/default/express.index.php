<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['express_name'];?></h3>
        <h5>提供给商家可选择的物流快递公司</h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['express_index_help1'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=express&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '公司名称', name : 'e_name', width : 120, sortable : true, align: 'left'},
            {display: '公司编号', name : 'e_code', width : 120, sortable : true, align: 'left'},
            {display: '首字母', name : 'e_letter', width : 60, sortable : true, align: 'center'},
            {display: '公司网址', name : 'e_url', width : 200, sortable : true, align: 'left'},
            {display: '状态', name : 'e_state', width : 60, sortable : true, align: 'center'},
            {display: '常用', name : 'e_order', width : 60, sortable : true, align: 'center'},
            {display: '自提站配送', name : 'e_zt_state', width : 80, sortable : true, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增快递公司', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation },
            {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operation }
        ],
        searchitems : [
            {display: '公司名称', name : 'e_name'},
            {display: '公司编号', name : 'e_code'},
            {display: '首字母', name : 'e_letter'}
            ],
        sortname: "id",
        sortorder: "asc",
        title: '快递公司列表列表'
    });
});
function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=express&op=add_express';
    } else if (name == 'del') {
        if ($('.trSelected', bDiv).length == 0) {
            showError('请选择要操作的数据项！');
        }
        var itemids = new Array();
        $('.trSelected', bDiv).each(function(i){
            itemids[i] = $(this).attr('data-id');
        });
        fg_delete(itemids);
    }
}
function fg_delete(id) {
    if (typeof id == 'number') {
        var id = new Array(id.toString());
    };
    if(confirm('删除后将不能恢复，确认删除这 ' + id.length + ' 项吗？')){
        id = id.join(',');
    } else {
        return false;
    }
    location.href = 'index.php?act=express&op=del_express&del_id='+id;
}
</script>
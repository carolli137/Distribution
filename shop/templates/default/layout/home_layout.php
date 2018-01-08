<?php defined('In33hao') or exit('Access Invalid!');?>
<?php if($output['is_goods']){
    include template('layout/goods_layout');
}else{
    include template('layout/common_layout');
}
?>
<?php if(!$output['is_search']){
    include template('layout/cur_local');
}
?>
<?php require_once($tpl_file);?>
<?php require_once template('footer');?>
</body>
</html>

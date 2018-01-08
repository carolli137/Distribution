<?php defined('In33hao') or exit('Access Invalid!');?>
<div id="cms_index_content" class="cms-content">
<?php 
$indexl_file = file_get_contents(UPLOAD_SITE_URL.DS.ATTACH_CMS.DS.'index_html'.DS.'index.html');
echo $indexl_file;
?>
</div>

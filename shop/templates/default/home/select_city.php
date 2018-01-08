<?php defined('In33hao') or exit('Access Invalid!');?>
<style type="text/css">
.ncg-container { width: 1200px; margin: 0 auto 10px auto; overflow: hidden;}
  .city_wrapper {
  border:1px #ddd solid;
  box-shadow: 0 0 1px #d3ebee;
}
.city_content h2{
  color: #E4393C;
  font-weight: 700;
  padding:20px 10px 6px 30px;
  font-size: 16px;
}
.cur_city {
  border-bottom: 1px #eee solid;
  padding:16px 20px;
  font-weight: 700px;
  color: #666;
  font-size: 14px;
  background-color: #eee;
}
.city_list {
  padding: 6px 10px 6px 30px;
  overflow: hidden;
  border-bottom: 1px solid #ddd;
}
.city_list div{
  float: left;
  height: 20px;
  line-height: 20px;
  
  margin:5px 10px;
}
.province_list:hover{
  background-color: rgb(247,247,247);
}


.city_list div a{
  color: #666;
  padding: 0 2px 2px;
  font-size: 14px;
}
.city_list a:hover{
  text-decoration: none;
  background-color: #D02629;
  color: #fff;
  border-radius: 2px;
}
</style>
<div class="clear"></div>
<div class="nch-breadcrumb-layout">
    <div class="nch-breadcrumb wrapper"><i class="icon-home"></i>
            <span><a href="index.php">首页</a></span><span class="arrow">&gt;</span>
                <span>地区选择</span>
          </div>
  </div>
<div class="ncg-container">
  <div class="city_wrapper">
      <div class="cur_city">当前地区：<?php echo $output['now_city'] ?></div>
      <div class="city_content">
          <div class="province_list">
            
            <div class="city_list">
              <div><a href="<?php echo BASE_SITE_URL;?>/index.php?act=index&op=set_city&cityid=0&ref_url=<?php echo urlencode($_GET['ref_url']);?>">全国站</a></div> 
            </div>
          </div>
          <?php foreach($output['province_list'] as $key => $value):?>
          <div class="province_list">
            <h2><?php echo $value[1];?></h2> 
            <div class="city_list">
              <?php foreach($output['city_list'][$key] as $k=> $v):?>
                <?php foreach($v as $m => $n):?>
                  <div><a href="<?php echo BASE_SITE_URL;?>/index.php?act=index&op=set_city&cityid=<?php echo $n[0];?>&ref_url=<?php echo urlencode($_GET['ref_url']);?>"><?php echo $n[1];?></a> </div>
                <?php endforeach;?>
              <?php endforeach;?>
            </div>
          </div>
          <?php endforeach;?>
      </div>
  </div>    
</div>
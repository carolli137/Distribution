<?php
/**
 * 积分礼品 v5.2
 *
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.suibianlu.com)
 * @license    http://www.sui bian lu.c om
 * @link       交流群号：575710573
 * @since      好商城提供技术支持 授权请购买正版
 */

defined('In33hao') or exit('Access Invalid!');
class pointprodControl extends mobileHomeControl{
	protected $member_info = array();
	public function __construct() {
		parent::__construct();
		
		//判断系统是否开启积分兑换功能
		if (C('pointprod_isuse') != 1){
			output_error('积分兑换功能为开启');
		}
		
	}
	public function indexOp(){
	    $this->plistOp();
	}
	/**
	 * 积分服务列表
	 */
	public function plistOp(){
	   
	    $model_pointprod = Model('pointprod');
	    
	    //展示状态
	    $pgoodsshowstate_arr = $model_pointprod->getPgoodsShowState();
	    //开启状态
	    $pgoodsopenstate_arr = $model_pointprod->getPgoodsOpenState();
	    
	    $model_member = Model('member');
	    //查询会员等级
	    $membergrade_arr = $model_member->getMemberGradeArr();
	   
	    //查询兑换服务列表
	    $where = array();
	    $where['pgoods_show'] = $pgoodsshowstate_arr['show'][0];
	    $where['pgoods_state'] = $pgoodsopenstate_arr['open'][0];
		
		if (!empty($_GET['keyword'])) {
            $where['pgoods_name|pgoods_keywords'] = array('like', '%' . $_GET['keyword'] . '%');
            if ($_COOKIE['hisSearch2'] == '') {
                $his_sh_list = array();
            } else {
                $his_sh_list = explode('~', $_COOKIE['hisSearch2']);
            }
            if (strlen($_GET['keyword']) <= 20 && !in_array($_GET['keyword'],$his_sh_list)) {
                if (array_unshift($his_sh_list, $_GET['keyword']) > 8) {
                    array_pop($his_sh_list);
                }
            }
            setcookie('hisSearch2', implode('~', $his_sh_list), time()+2592000, '/', SUBDOMAIN_SUFFIX ? SUBDOMAIN_SUFFIX : '', false);

        }
	    //会员级别
	    $level_filter = array();
	    if (isset($_GET['level'])){
	        $level_filter['search'] = intval($_GET['level']);
	    }
		if (intval($_GET['isable']) == 1)
		{
			if($memberid=$this->getMemberIdIfExists())
			{
				$member_infotmp=Model('member')->getMemberInfoByID($memberid);
				//当前登录会员等级信息
            $membergrade_info = $model_member->getOneMemberGrade($member_infotmp['member_exppoints'],true);
            $this->member_info = array_merge($member_infotmp,$membergrade_info);
            
			}
			
		}
	    if (intval($_GET['isable']) == 1&&isset($this->member_info['level'])){
	        $level_filter['isable'] = intval($this->member_info['level']);
	    }
	    if (count($level_filter) > 0){
	        if (isset($level_filter['search']) && isset($level_filter['isable'])){
	            $where['pgoods_limitmgrade'] = array(array('eq',$level_filter['search']),array('elt',$level_filter['isable']),'and');
	        } elseif (isset($level_filter['search'])){
	            $where['pgoods_limitmgrade'] = $level_filter['search'];
	        } elseif (isset($level_filter['isable'])){
	            $where['pgoods_limitmgrade'] = array('elt',$level_filter['isable']);
	        } 
	    }
	    
	    
	    //查询仅我能兑换和所需积分
	    $points_filter = array();
	    if (intval($_GET['isable']) == 1&&isset($this->member_info['level'])){
	        $points_filter['isable'] = $this->member_info['member_points'];
	    }
	    if (intval($_GET['points_min']) > 0){
	        $points_filter['min'] = intval($_GET['points_min']);
	    }
	    if (intval($_GET['points_max']) > 0){
	        $points_filter['max'] = intval($_GET['points_max']);
	    }
	    if (count($points_filter) > 0){
	        asort($points_filter);
	        if (count($points_filter) > 1){
	            $points_filter = array_values($points_filter);
	            $where['pgoods_points'] = array('between',array($points_filter[0],$points_filter[1]));
	        } else {
	            if ($points_filter['min']){
	                $where['pgoods_points'] = array('egt',$points_filter['min']);
	            } elseif ($points_filter['max']) {
	                $where['pgoods_points'] = array('elt',$points_filter['max']);
	            } elseif ($points_filter['isable']) {
	                $where['pgoods_points'] = array('elt',$points_filter['isable']);
	            }
	        }
	    }
		
		
	    //排序
	    switch ($_GET['orderby']){
	    	case 'stimedesc':
	    	    $orderby = 'pgoods_starttime desc,';
	    	    break;
	    	case 'stimeasc':
	    	    $orderby = 'pgoods_starttime asc,';
	    	    break;
	    	case 'pointsdesc':
	    	    $orderby = 'pgoods_points desc,';
	    	    break;
	    	case 'pointsasc':
	    	    $orderby = 'pgoods_points asc,';
	    	    break;
	    }
	    $orderby .= 'pgoods_sort asc,pgoods_id desc';
	    $filed='pgoods_id,pgoods_name,pgoods_price,pgoods_points,pgoods_image,pgoods_tag,pgoods_serial,pgoods_storage,pgoods_commend,pgoods_keywords,pgoods_salenum,pgoods_view,pgoods_limitnum';
		$pageSize=10;
		$pointprod_list = $model_pointprod->getPointProdList($where, $filed, $orderby,'',$pageSize);
		$page_count = $model_pointprod->gettotalpage();
        
        output_data(array('goods_list' => $pointprod_list,'grade_list'=>$membergrade_arr,'ww'=>json_encode($where)), mobile_page($page_count));

	}
	/**
	 * 积分礼品详细
	 */
	public function pinfoOp() {
		$pid = intval($_GET['id']);
		if (!$pid){
			 output_error('参数错误!');
		}
		$model_pointprod = Model('pointprod');
		//查询兑换礼品详细
		$prodinfo = $model_pointprod->getOnlinePointProdInfo(array('pgoods_id'=>$pid));
		if (empty($prodinfo)){
			 output_error('商品参数错误!');
			
		}		
		
		//更新礼品浏览次数
		$tm_tm_visite_pgoods = cookie('tm_visite_pgoods');
		$tm_tm_visite_pgoods = $tm_tm_visite_pgoods?explode(',', $tm_tm_visite_pgoods):array();
		if (!in_array($pid, $tm_tm_visite_pgoods)){//如果已经浏览过该服务则不重复累计浏览次数 
		    $result = $model_pointprod->editPointProdViewnum($pid);
 		    if ($result['state'] == true){//累加成功则cookie中增加该服务ID
		        $tm_tm_visite_pgoods[] = $pid;
		        setNcCookie('tm_visite_pgoods',implode(',', $tm_tm_visite_pgoods));
		    }
		}

		//查询兑换信息
		$model_pointorder = Model('pointorder');
		$pointorderstate_arr = $model_pointorder->getPointOrderStateBySign();
		$where = array();
		$where['point_orderstate'] = array('neq',$pointorderstate_arr['canceled'][0]);
		$where['point_goodsid'] = $pid;
		$orderprod_list = $model_pointorder->getPointOrderAndGoodsList($where, '*', 0, 4,'points_ordergoods.point_recid desc');
		if ($orderprod_list){
		    $buyerid_arr = array();
			foreach ($orderprod_list as $k=>$v){
			    $buyerid_arr[] = $v['point_buyerid'];
			}
			$memberlist_tmp = Model('member')->getMemberList(array('member_id'=>array('in',$buyerid_arr)),'member_id,member_avatar');
			$memberlist = array();
			if ($memberlist_tmp){
				foreach ($memberlist_tmp as $v){
				    $memberlist[$v['member_id']] = $v;
				}
			}
			foreach ($orderprod_list as $k=>$v){
				$v['member_avatar'] = ($t = $memberlist[$v['point_buyerid']]['member_avatar'])?UPLOAD_SITE_URL.DS.ATTACH_AVATAR.DS.$t : UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('default_user_portrait');
				$orderprod_list[$k] = $v;
			}
		}
		
		//热门积分兑换服务
		$recommend_pointsprod = $model_pointprod->getRecommendPointProd(5);
		
		output_data(array('goods_commend_list'=>$orderprod_list,'goods_info'=>$prodinfo));
		
	}
}

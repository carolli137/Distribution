<?php
/**
 * 积分兑换信
 *
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.suibianlu.com)
 * @license    http://www.sui bian lu.c om
 * @link       交流群号：575710573
 * @since      好商城提供技术支持 授权请购买正版
 */


defined('In33hao') or exit('Access Invalid!');

class member_pointorderControl extends mobileMemberControl{
	public function __construct() {
		parent::__construct();
		
		//判断系统是否开启积分和积分兑换功能
		if (C('points_isuse') != 1 || C('pointprod_isuse') != 1){
			output_error('未开启积分兑换功能!');die();
		}
		
	}
	public function indexOp() {
		$this->orderlistOp();
	}
	/**
	 * 兑换信息列表
	 */
	public function orderlistOp() {
	    //兑换信息列表
		$where = array();
		$where['point_buyerid'] = $this->member_info['member_id'];
		
		$model_pointorder = Model('pointorder');
		$order_list = $model_pointorder->getPointOrderList($where, '*', 10, 0, 'point_orderid desc');
		$order_idarr = array();
		$order_listnew = array();
		if (is_array($order_list) && count($order_list)>0){
			foreach ($order_list as $k => $v){
				$v['state_desc']='';
				switch($v['point_orderstate'])
				{
					case '20';$v['state_desc']='待发货';break;
					case '30';$v['state_desc']='已发货';break;
					case '40';$v['state_desc']='已收货';break;
					case '50';$v['state_desc']='已完成';break;
					case '2';$v['state_desc']='已取消';break;
				}
				$order_listnew[$v['point_orderid']] = $v;
				$order_idarr[] = $v['point_orderid'];
			}
		}
		$order_listnew1=array();
		//查询兑换商品
		if (is_array($order_idarr) && count($order_idarr)>0){
		    $prod_list = $model_pointorder->getPointOrderGoodsList(array('point_orderid'=>array('in',$order_idarr)));
		    if (is_array($prod_list) && count($prod_list)>0){
		        foreach ($prod_list as $v){
		            if (isset($order_listnew[$v['point_orderid']])){
		                $order_listnew[$v['point_orderid']]['prodlist'][] = $v;
		            }
		        }
				
				foreach ($order_listnew as $k => $v){
					$order_listnew1[] = $v;
				}
		    }
		}
		output_data(array('order_list'=>$order_listnew1));		
		
	}
	/**
	 * 	取消兑换
	 */
	public function cancel_orderOp(){
		$model_pointorder = Model('pointorder');
		//取消订单
		$data = $model_pointorder->cancelPointOrder($_POST['order_id'],$this->member_info['member_id']);
		if ($data['state']){
			output_data('1');
		}else {
			output_error('取消失败!');die();
		}
	}
	/**
	 * 确认收货
	 */
	public function receiving_orderOp(){
	    $data = Model('pointorder')->receivingPointOrder($_POST['order_id']);
		if ($data['state']){
			output_data('1');
		}else {
			output_error('处理失败!');die();
		}
	}
	/**
	 * 兑换信息详细
	 */
	public function order_infoOp(){
		$order_id = intval($_GET['order_id']);
		if ($order_id <= 0){
			output_error('订单不正确!');die();
		}
		$model_pointorder = Model('pointorder');
		//查询兑换订单信息
		$where = array();
		$where['point_orderid'] = $order_id;
		$where['point_buyerid'] = $this->member_info['member_id'];
		$order_info = $model_pointorder->getPointOrderInfo($where);
		if (!$order_info){
			output_error('订单不存在!');die();
		}
		if($order_info['point_addtime'])
		{
			$order_info['point_addtime']=date('Y-m-d H:i:s',$order_info['point_addtime']); 
		}else{
			$order_info['point_addtime'] = '';
		}
		if($order_info['point_shippingtime'])
		{
			$order_info['point_shippingtime']=date('Y-m-d H:i:s',$order_info['point_shippingtime']); 
		}else{
			$order_info['point_shippingtime'] = '';
		}
		if($order_info['point_finnshedtime'])
		{
			$order_info['point_finnshedtime']=date('Y-m-d H:i:s',$order_info['point_finnshedtime']); 
		}else{
			$order_info['point_finnshedtime'] = '';
		}
		//获取订单状态
		$pointorderstate_arr = $model_pointorder->getPointOrderStateBySign();
		
		//查询兑换订单收货人地址
		$orderaddress_info = $model_pointorder->getPointOrderAddressInfo(array('point_orderid'=>$order_id));
		
		//兑换商品信息
		$prod_list = $model_pointorder->getPointOrderGoodsList(array('point_orderid'=>$order_id));
		
		//物流公司信息
		if ($order_info['point_shipping_ecode'] != ''){
		    $data = Model('express')->getExpressInfoByECode($order_info['point_shipping_ecode']);
		    if ($data['state']){
		        $express_info = $data['data']['express_info'];
		    }
		}
		output_data(array('order_info'=>$order_info,'express_info'=>$express_info,'prod_list'=>$prod_list,'orderaddress_info'=>$orderaddress_info,'pointorderstate_arr'=>$pointorderstate_arr));
		
	}

}

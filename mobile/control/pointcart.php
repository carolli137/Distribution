<?php
/**
 * 积分礼品购物车操作 v5.2
 *
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.suibianlu.com)
 * @license    http://www.sui bian lu.c om
 * @link       交流群号：575710573
 * @since      好商城提供技术支持 授权请购买正版
 */

defined('In33hao') or exit('Access Invalid!');
class pointcartControl extends mobileMemberControl {
	public function __construct() {
		parent::__construct();
		
		//判断系统是否开启积分和积分兑换功能
		if (C('pointprod_isuse') != 1){			
			output_error('未开启积分兑换功能!');
		}
	
	}

	
	/**
	 * 购物车添加礼品
	 */
	public function addOp() {
		$pgid	= intval($_POST['pgid']);
		$quantity	= intval($_POST['quantity']);
		if($pgid <= 0 || $quantity <= 0) {
			output_error(' 参数错误!!'); die;
		}
		
		//验证积分礼品是否存在购物车中
		$model_pointcart = Model('pointcart');
		$model_pointcart->delPointCart(array('pmember_id'=>$this->member_info['member_id']));
		$check_cart	= $model_pointcart->getPointCartInfo(array('pgoods_id'=>$pgid,'pmember_id'=>$this->member_info['member_id']));
		if(!empty($check_cart)) {
			output_data(array('done' => 'ok1')); die;
		}
		//验证是否能兑换
		$data = $model_pointcart->checkExchange($pgid, $quantity, $this->member_info['member_id']);
		if (!$data['state']){
			output_error($data['msg']); die;
		 
		}
		$prod_info = $data['data']['prod_info'];
		
		$insert_arr	= array();
		$insert_arr['pmember_id']		= $this->member_info['member_id'];
		$insert_arr['pgoods_id']		= $prod_info['pgoods_id'];
		$insert_arr['pgoods_name']		= $prod_info['pgoods_name'];
		$insert_arr['pgoods_points']	= $prod_info['pgoods_points'];
		$insert_arr['pgoods_choosenum']	= $prod_info['quantity'];
		$insert_arr['pgoods_image']		= $prod_info['pgoods_image_old'];
		$cart_state = $model_pointcart->addPointCart($insert_arr);
		output_data(array('done' => 'ok'));
		die;
	}
	
	
	
	/**
	 * 兑换订单流程第一步
	 */
	public function step1Op(){
		//获取符合条件的兑换礼品和总积分
		$data = Model('pointcart')->getCartGoodsList($this->member_info['member_id']);
		if (!$data['state']){
		    output_error($data['msg']);die();
		}

		//实例化收货地址模型（不显示自提点地址）
		$address_list = Model('address')->getAddressList(array('member_id'=>$this->member_info['member_id'],'dlyp_id'=>0), 'is_default desc,address_id desc');
		
		output_data(array('pointprod_arr'=>$data['data'],'address_info'=>$address_list[0]));
	}
	/**
	 * 兑换订单流程第二步
	 */
	public function step2Op() {
	    $model_pointcart = Model('pointcart');
		//获取符合条件的兑换礼品和总积分
		$data = $model_pointcart->getCartGoodsList($this->member_info['member_id']);
		if (!$data['state']){
		   output_error($data['msg']);die();
		}
		$pointprod_arr = $data['data'];
		unset($data);
		
		//验证积分数是否足够
		$data = $model_pointcart->checkPointEnough($pointprod_arr['pgoods_pointall'], $this->member_info['member_id']);
		if (!$data['state']){
		    output_error($data['msg']);die();
		}
		unset($data);
		
		//创建兑换订单
		$data = Model('pointorder')->createOrder($_POST, $pointprod_arr, array('member_id'=>$this->member_info['member_id'],'member_name'=>$this->member_info['member_name'],'member_email'=>$this->member_info['member_email']));
		if (!$data['state']){
		    output_error($data['msg']);die();
		}
		$order_id = $data['data']['order_id'];
		output_data(array('pointprod_arr'=>$order_id));
	}
	
}
<?php
/**
 * 商家注销
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.suibianlu.com)
 * @license    http://www.sui bian lu.c om
 * @link       交流群号：575710573
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class seller_expresscontrol extends mobileSellerControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 物流列表
     */
    public function get_listOp() {
        $express_list  = rkcache('express',true);
        $express_array = array();
        //快递公司
        $my_express_list = Model()->table('store_extend')->getfby_store_id($this->store_info['store_id'],'express');
        if (!empty($my_express_list)){
            $my_express_list = explode(',',$my_express_list);
            foreach ($my_express_list as $val) {
                $express_array[$val] = $express_list[$val];
				$express_list[$val]['is_check']='1';
            }
        }
		

        output_data(array('express_array' =>$express_list));
    }
	
	public function  get_defaultexpressOp(){
		 $order_id = intval($_POST['order_id']);
        if ($order_id <= 0){
            output_error('参数错误');

        }
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $this->store_info['store_id'];

        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));

        $if_allow_send = intval($order_info['lock_state']) || !in_array($order_info['order_state'],array(ORDER_STATE_PAY,ORDER_STATE_SEND));

        if ($if_allow_send) {
            output_error('参数错误');
        }

				 //取发货地址
        $model_daddress = Model('daddress');
        if ($order_info['extend_order_common']['daddress_id'] > 0 ){
            $daddress_info = $model_daddress->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));

        }else{
            //取默认地址
            $daddress_info = $model_daddress->getAddressList(array('store_id'=>$this->store_info['store_id']),'*','is_default desc',1);
            $daddress_info = $daddress_info[0];
        }
		 output_data(array('daddress_info'=>$daddress_info,'orderinfo'=>$order_info));
	}
	public function get_mylistOp() {
        //$express_list  = rkcache('express',true);
       // $express_array = array();
        //快递公司
 //       $my_express_list = Model()->table('store_extend')->getfby_store_id($this->store_info['store_id'],'express');
//      if (!empty($my_express_list)){
//          $my_express_list = explode(',',$my_express_list);
//          foreach ($my_express_list as $val) {
//              $express_array[$val] = $express_list[$val];
//				$express_list[$val]['is_check']='1';
//          }
//      }

		
		$order_id = intval($_POST['order_id']);
        if ($order_id <= 0){
            output_error('参数错误');

        }
        $express_list  = rkcache('express',true);
        //如果是自提订单，只保留自提快递公司
        if ($order_info['extend_order_common']['reciver_info']['dlyp'] != '') {
            foreach ($express_list as $k => $v) {
                if ($v['e_zt_state'] == '0') unset($express_list[$k]);
            }
            $my_express_list = array_keys($express_list);
        } else {
            //快递公司
            $my_express_list = Model()->table('store_extend')->getfby_store_id($this->store_info['store_id'],'express');
	        if (!empty($my_express_list)){
	            $my_express_list = explode(',',$my_express_list);
	            foreach ($my_express_list as $val) {
	                $express_array[$val] = $express_list[$val];
	            }
	        }
        }
		//订单信息
		$model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $this->store_info['store_id'];

        $order_info = $model_order->getOrderInfo($condition, array('order_common','order_goods','member'));
        if (empty($order_info)) {
            output_error('订单信息不存在');
        }
		
		foreach ($order_info['extend_order_goods'] as $value) {
            $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
            $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
            $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
            $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
           
            $order_info['zengpin_list'] = array();
            if ($value['goods_type'] == 5) {
                $order_info['zengpin_list'][] = $value;
            } else {
                $order_info['goods_list'][] = $value;
            }
        }

        output_data(array('orderinfo'=>$order_info,'express_array' =>$express_array));
    }
    
    /**
     * 自提物流列表
     */
    public function get_zt_listOp() {
        $express_list  = rkcache('express',true);
        foreach ($express_list as $k => $v) {
            if ($v['e_zt_state'] == '0') unset($express_list[$k]);
        }
        output_data(array('express_array' =>$express_list));
    }
	
	/**
     * 物流保存
     */
    public function savedefaultOp() {
//  	$address_id = intval($_POST['expresslists']);
//    // if ($address_id <=  0) return false;
//     $condition = array();
//     $condition['store_id'] = $this->store_info['store_id'];
//     $update = Model('daddress')->editAddress(array('is_default'=>0),$condition);
//     $condition['address_id'] = $address_id;
//     $update = Model('daddress')->editAddress(array('is_default'=>1),$condition);
		
        $model = Model('store_extend');
  		$data['store_id'] = $this->store_info['store_id'];
       
        $data['express'] = $_POST['expresslists'];
        
        if (!$model->getby_store_id($this->store_info['store_id'])){
            $result = $model->insert($data);
        }else{
            $result = $model->where(array('store_id'=>$this->store_info['store_id']))->update($data);
        }
        if ($result){
            output_data('succ');
        }else{
            output_error('error');
        }

    }
	
}

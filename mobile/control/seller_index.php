<?php
/**
 * 商家首页
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.suibianlu.com)
 * @license    http://www.sui bian lu.c om
 * @link       交流群号：575710573
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class seller_indexControl extends mobileSellerControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 商家中心
     */
    public function indexOp() {
        $seller_info = array();
        $seller_info = $this->seller_info;
        $store_info = $this->store_info;

        //最后登陆
        $seller_info['last_login_time_fmt'] = date('Y-m-d H:i:s', $seller_info['last_login_time']);
        $model_order = Model('order');
        // 待付款
        $seller_info['order_nopay_count'] = $model_order->getOrderCountByID('store',$store_info['store_id'],'NewCount');
        // 待发货
        $seller_info['order_noreceipt_count'] = $model_order->getOrderCountByID('store',$store_info['store_id'],'PayCount');

		
		//店铺头像
        $store_info['store_avatar'] = getStoreLogo($store_info['store_avatar'], 'store_avatar');
        //店铺标志
        $store_info['store_label'] = getStoreLogo($store_info['store_label'], 'store_logo');
        //等级信息
        $store_info['grade_name'] = $this->store_grade['sg_name'];
        //商品数量限制
        $store_info['grade_goodslimit'] = $this->store_grade['sg_goods_limit'];
        //图片空间数量限制
        $store_info['grade_albumlimit'] = $this->store_grade['sg_album_limit'];

        /**
         * 销售情况统计
         */
        $field = 'COUNT(*) as ordernum,SUM(order_amount) as orderamount';
        $where = array();
        $where['store_id'] = $this->store_info['store_id'];
        //有效订单
        $where['order_isvalid'] = 1;
        //昨日销量
        $where['order_add_time'] = array('between',array(strtotime(date('Y-m-d',(time()-3600*24))),strtotime(date('Y-m-d',time()))-1));
        $daily_sales = Model('stat')->getoneByStatorder($where, $field);
        //月销量
        $where['order_add_time'] = array('gt',strtotime(date('Y-m',time())));
        $monthly_sales = Model('stat')->getoneByStatorder($where, $field);
        $store_info['daily_sales'] = $daily_sales;
        $store_info['monthly_sales'] = $monthly_sales;

        //统计
        $statics = $this->getStatics();

        output_data(array('seller_info' => $seller_info, 'store_info' => $store_info, 'statics' => $statics));
    }

    public function order_listOp() {
        $model_order = Model('order');

        $order_list = $model_order->getStoreOrderList(
            $this->store_info['store_id'],
            $_POST['order_sn'],
            $_POST['buyer_name'],
            $_POST['state_type'],
            $_POST['query_start_date'],
            $_POST['query_end_date'],
            $_POST['skip_off'],
            '*',
            array('order_goods')
        );

        $page_count = $model_order->gettotalpage();

        output_data(array('order_list' => $order_list), mobile_page($page_count));
    }

    /**
     * 取消订单
     */
    public function order_cancelOp() {
        $order_id = intval($_POST['order_id']);
        $reason = $_POST['reason'];
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $this->store_info['store_id'];
        $order_info = $model_order->getOrderInfo($condition);

        $if_allow = $model_order->getOrderOperateState('store_cancel',$order_info);
        if (!$if_allow) {
            output_error('无权操作');
        }

        if (TIMESTAMP - 86400 < $order_info['api_pay_time']) {
            $_hour = ceil(($order_info['api_pay_time']+86400-TIMESTAMP)/3600);
            output_error('该订单曾尝试使用第三方支付平台支付，须在'.$_hour.'小时以后才可取消');
        }

        if ($order_info['order_type'] == 2) {
            //预定订单
            $result = Logic('order_book')->changeOrderStateCancel($order_info,'seller',$this->seller_info['seller_name'], $reason);
        } else {
            $cancel_condition = array();
            if ($order_info['payment_code'] != 'offline') {
                $cancel_condition['order_state'] = ORDER_STATE_NEW;
            }
            $result = Logic('order')->changeOrderStateCancel($order_info,'seller',$this->seller_info['seller_name'], $reason, true, $cancel_condition);
        }

        if (!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }
    
    /**
     * 修改运费
     */
    public function order_ship_priceOp() {
        $order_id = intval($_POST['order_id']);
        $shipping_fee = ncPriceFormat($_POST['shipping_fee']);
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $this->store_info['store_id'];
        $order_info = $model_order->getOrderInfo($condition);

        $if_allow = $model_order->getOrderOperateState('modify_price',$order_info);
        if (!$if_allow) {
            output_error('无权操作');
        }
        $result = Logic('order')->changeOrderShipPrice($order_info, 'seller', $this->seller_info['seller_name'], $shipping_fee);

        if (!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }
    
    /**
     * 发货
     */
    public function order_deliver_sendOp() {
        $order_id = intval($_POST['order_id']);
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $this->store_info['store_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
        $if_allow_send = intval($order_info['lock_state']) || !in_array($order_info['order_state'],array(ORDER_STATE_PAY,ORDER_STATE_SEND));
        if ($if_allow_send) {
            output_error('无权操作');
        }

        $result = Logic('order')->changeOrderSend($order_info, 'seller', $this->seller_info['seller_name'], $_POST);
        if (!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }

    /**
     * 取得卖家统计类信息
     *
     */
    private function getStatics() {
        $add_time_to = strtotime(date("Y-m-d")+60*60*24);   //当前日期 ,从零点来时
        $add_time_from = strtotime(date("Y-m-d",(strtotime(date("Y-m-d"))-60*60*24*30)));   //30天前
        $goods_online = 0;      // 出售中商品
        $goods_waitverify = 0;  // 等待审核
        $goods_verifyfail = 0;  // 审核失败
        $goods_offline = 0;     // 仓库待上架商品
        $goods_lockup = 0;      // 违规下架商品
        $consult = 0;           // 待回复商品咨询
        $no_payment = 0;        // 待付款
        $no_delivery = 0;       // 待发货
        $no_receipt = 0;        // 待收货
        $refund_lock  = 0;      // 售前退款
        $refund = 0;            // 售后退款
        $return_lock  = 0;      // 售前退货
        $return = 0;            // 售后退货
        $complain = 0;          //进行中投诉

        $model_goods = Model('goods');
        // 全部商品数
        $goodscount = $model_goods->getGoodsCommonCount(array('store_id' => $this->store_info['store_id']));
        // 出售中的商品
        $goods_online = $model_goods->getGoodsCommonOnlineCount(array('store_id' => $this->store_info['store_id']));
        if (C('goods_verify')) {
            // 等待审核的商品
            $goods_waitverify = $model_goods->getGoodsCommonWaitVerifyCount(array('store_id' => $this->store_info['store_id']));
            // 审核失败的商品
            $goods_verifyfail = $model_goods->getGoodsCommonVerifyFailCount(array('store_id' => $this->store_info['store_id']));
        }
        // 仓库待上架的商品
        $goods_offline = $model_goods->getGoodsCommonOfflineCount(array('store_id' => $this->store_info['store_id']));
        // 违规下架的商品
        $goods_lockup = $model_goods->getGoodsCommonLockUpCount(array('store_id' => $this->store_info['store_id']));
        // 等待回复商品咨询
        if (C('dbdriver') == 'mysql') {
            $consult = Model('consult')->getConsultCount(array('store_id' => $this->store_info['store_id'], 'consult_reply' => ''));
        } else {
            $consult = Model('consult')->getConsultCount(array('store_id' => $this->store_info['store_id'], 'consult_reply' => array('exp', 'consult_reply IS NULL')));
        }

        // 商品图片数量
        $imagecount = Model('album')->getAlbumPicCount(array('store_id' => $this->store_info['store_id']));

        $model_order = Model('order');
        // 交易中的订单
        $progressing = $model_order->getOrderCountByID('store',$this->store_info['store_id'],'TradeCount');
        // 待付款
        $no_payment = $model_order->getOrderCountByID('store',$this->store_info['store_id'],'NewCount');
        // 待发货
        $no_delivery = $model_order->getOrderCountByID('store',$this->store_info['store_id'],'PayCount');

        $model_refund_return = Model('refund_return');
        // 售前退款
        $condition = array();
        $condition['store_id'] = $this->store_info['store_id'];
        $condition['refund_type'] = 1;
        $condition['order_lock'] = 2;
        $condition['refund_state'] = array('lt', 3);
        $refund_lock = $model_refund_return->getRefundReturnCount($condition);
        // 售后退款
        $condition = array();
        $condition['store_id'] = $this->store_info['store_id'];
        $condition['refund_type'] = 1;
        $condition['order_lock'] = 1;
        $condition['refund_state'] = array('lt', 3);
        $refund = $model_refund_return->getRefundReturnCount($condition);
        // 售前退货
        $condition = array();
        $condition['store_id'] = $this->store_info['store_id'];
        $condition['refund_type'] = 2;
        $condition['order_lock'] = 2;
        $condition['refund_state'] = array('lt', 3);
        $return_lock = $model_refund_return->getRefundReturnCount($condition);
        // 售后退货
        $condition = array();
        $condition['store_id'] = $this->store_info['store_id'];
        $condition['refund_type'] = 2;
        $condition['order_lock'] = 1;
        $condition['refund_state'] = array('lt', 3);
        $return = $model_refund_return->getRefundReturnCount($condition);

        $condition = array();
        $condition['accused_id'] = $this->store_info['store_id'];
        $condition['complain_state'] = array(array('gt',10),array('lt',90),'and');
        $complain = Model()->table('complain')->where($condition)->count();

        //待确认的结算账单
        $model_bill = Model('bill');
        $condition = array();
        $condition['ob_store_id'] = $this->store_info['store_id'];
        $condition['ob_state'] = BILL_STATE_CREATE;
        $bill_confirm_count = $model_bill->getOrderBillCount($condition);

        //统计数组
        $statistics = array(
            'goodscount' => $goodscount,
            'online' => $goods_online,
            'waitverify' => $goods_waitverify,
            'verifyfail' => $goods_verifyfail,
            'offline' => $goods_offline,
            'lockup' => $goods_lockup,
            'imagecount' => $imagecount,
            'consult' => $consult,
            'progressing' => $progressing,
            'payment' => $no_payment,
            'delivery' => $no_delivery,
            'refund_lock' => $refund_lock,
            'refund' => $refund,
            'return_lock' => $return_lock,
            'return' => $return,
            'complain' => $complain,
            'bill_confirm' => $bill_confirm_count
        );
    
        return $statistics;
    }
}

<?php
/**
 * 我的地址
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.suibianlu.com)
 * @license    http://www.sui bian lu.c om
 * @link       交流群号：575710573
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class seller_addressControl extends mobileSellerControl {

    public function __construct() {
        parent::__construct();
        $this->model_address = Model('daddress');
    }

    /**
     * 地址列表
     */
    public function address_listOp() {
//      $address_list = $this->model_address->getAddressList(array('member_id'=>$this->member_info['member_id']));
//      output_data(array('address_list' => $address_list));


       $model_daddress = Model('daddress');
       $condition = array();
       $condition['store_id'] = $this->store_info['store_id'];
       $address_list = $model_daddress->getAddressList($condition,'*','',20);
       
        output_data(array('address_list' => $address_list));



    }

    /**
     * 地址详细信息
     */
    public function address_infoOp() {
        $address_id = intval($_POST['address_id']);
        if ($address_id <=  0) {
            output_error('地址参数错误！');
        }
        $condition = array();
        $condition['address_id'] = $address_id;
        $address_info = $this->model_address->getAddressInfo($condition);
        if(!empty($address_id) && $address_info['store_id'] ==$this->store_info['store_id']) {
            output_data(array('address_info' => $address_info));
        } else {
            output_error('地址不存在');
        }
    }

    /**
     * 删除地址
     */
    public function address_delOp() {
//      $address_id = intval($_POST['address_id']);
//
//      $condition = array();
//      $condition['address_id'] = $address_id;
//      $condition['member_id'] = $this->member_info['member_id'];
//      $this->model_address->delAddress($condition);
//      output_data('1');
//		

		$address_id = intval($_POST['address_id']);
        if ($address_id <=  0) {
            output_error('删除地址失败！');
        }
        $condition = array();
        $condition['address_id'] = $address_id;
        $condition['store_id'] = $this->store_info['store_id'];
        $delete = Model('daddress')->delAddress($condition);
        if ($delete){
            output_data('删除地址成功！');
        }else {
            output_error('删除地址失败！');
        }
		
		
		
		
		
    }

    /**
     * 新增地址
     */
    public function address_addOp() {
//      $address_info = $this->_address_valid();
//
//      $result = $this->model_address->addAddress($address_info);
//      if($result) {
//          output_data(array('address_id' => $result));
//      } else {
//          output_error('保存失败');
//      }
//		Language::read('member_member_index');
//      $lang   = Language::getLangContent();
        $model_daddress = Model('daddress');
 //保存 新增/编辑 表单
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["seller_name"],"require"=>"true","message"=>'收件人不能为空'),
                array("input"=>$_POST["area_id"],"require"=>"true","validator"=>"Number","message"=>'请选择地址'),
                array("input"=>$_POST["city_id"],"require"=>"true","validator"=>"Number","message"=>'请选择地址'),
                array("input"=>$_POST["area_info"],"require"=>"true","message"=>'请选择地址'),
                array("input"=>$_POST["address"],"require"=>"true","message"=>'详细地址不能为空！'),
                array("input"=>$_POST['telphone'],'require'=>'true','message'=>'电话不能为空')
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                 output_error($error);
            }
            $data = array(
                'store_id' => $this->store_info['store_id'],
                'seller_name' => $_POST['seller_name'],
                'area_id' => $_POST['area_id'],
                'city_id' => $_POST['city_id'],
                'area_info' => $_POST['area_info'],
                'address' => $_POST['address'],
                'telphone' => $_POST['telphone'],
                'company' => '',
                'is_default'=>intval($_POST['is_default'])
            );
            $address_id = intval($_POST['address_id']);
            if ($address_id > 0){
                $condition = array();
                $condition['address_id'] = $address_id;
                $condition['store_id'] = $this->store_info['store_id'];
                $update = $model_daddress->editAddress($data,$condition);
                if (!$update){
                     output_error('修改地址失败！');
                }
				$is_default=intval($_POST['is_default']);
				if($is_default==1)
				{
					$condition = array();
	                $condition['address_id'] = array('neq',$address_id);
	                $condition['store_id'] = $this->store_info['store_id'];
	                $update = $model_daddress->editAddress(array('is_default'=>0),$condition);
				}
            } else {
                $insert = $model_daddress->addAddress($data);
                if (!$insert){
                    output_error('增加地址失败！');
                }
				$is_default=intval($_POST['is_default']);
				if($is_default==1)
				{
					$condition = array();
	                $condition['address_id'] = array('neq',$insert);
	                $condition['store_id'] = $this->store_info['store_id'];
	                $update = $model_daddress->editAddress(array('is_default'=>0),$condition);
				}
            }
             output_data('操作成功！');
    }

    /**
     * 编辑地址
     */
//  public function address_editOp() {
//      $address_id = intval($_POST['address_id']);
//
//      //验证地址是否为本人
//      $address_info = $this->model_address->getOneAddress($address_id);
//      if ($address_info['member_id'] != $this->member_info['member_id']) {
//          output_error('参数错误');
//      }
//
//      $address_info = $this->_address_valid();
//      if ($_POST['is_default']) {
//          $this->model_address->editAddress(array('is_default'=>0),array('member_id'=>$this->member_info['member_id'],'is_default'=>1));
//      }
//      $result = $this->model_address->editAddress($address_info, array('address_id'=>$address_id,'member_id'=>$this->member_info['member_id']));
//      if($result) {
//          output_data('1');
//      } else {
//          output_error('保存失败');
//      }
//  }

    /**
     * 验证地址数据
     */
//  private function _address_valid() {
//      $obj_validate = new Validate();
//      $obj_validate->validateparam = array(
//          array("input"=>$_POST["true_name"],"require"=>"true","message"=>'姓名不能为空'),
//          array("input"=>$_POST["area_info"],"require"=>"true","message"=>'地区不能为空'),
//          array("input"=>$_POST["address"],"require"=>"true","message"=>'地址不能为空'),
//          array("input"=>$_POST['tel_phone'].$_POST['mob_phone'],'require'=>'true','message'=>'联系方式不能为空')
//      );
//      $error = $obj_validate->validate();
//      if ($error != ''){
//          output_error($error);
//      }
//
//      $data = array();
//      $data['member_id'] = $this->member_info['member_id'];
//      $data['true_name'] = $_POST['true_name'];
//      $data['area_id'] = intval($_POST['area_id']);
//      $data['city_id'] = intval($_POST['city_id']);
//      $data['area_info'] = $_POST['area_info'];
//      $data['address'] = $_POST['address'];
//      $data['tel_phone'] = $_POST['tel_phone'];
//      $data['mob_phone'] = $_POST['mob_phone'];
//      $data['is_default'] = $_POST['is_default'] ? 1 : 0;
//      if ($_POST['is_default']) {
//          $this->model_address->editAddress(array('is_default'=>0),array('member_id'=>$this->member_info['member_id'],'is_default'=>1));
//      }
//      return $data;
//  }

}

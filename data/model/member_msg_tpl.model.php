<?php
/**
 * 用户消息模板模型
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.suibianlu.com)
 * @license    http://www.sui bian lu.c om
 * @link       交流群号：575710573
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');
class member_msg_tplModel extends Model{
    public function __construct() {
        parent::__construct('member_msg_tpl');
    }

    /**
     * 用户消息模板列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getMemberMsgTplList($condition, $field = '*', $page = 0, $order = 'mmt_code asc') {
        return $this->field($field)->where($condition)->order($order)->page($page)->select();
    }

    /**
     * 用户消息模板详细信息
     * @param array $condition
     * @param string $field
     */
    public function getMemberMsgTplInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 编辑用户消息模板
     * @param array $condition
     * @param unknown $update
     */
    public function editMemberMsgTpl($condition, $update) {
        return $this->where($condition)->update($update);
    }
}

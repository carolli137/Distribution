<?php
/**
 * 快递公司
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.suibianlu.com
 * @link       交流群号：575710573
 */



defined('In33hao') or exit('Access Invalid!');
class expressControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('express');
    }

    public function indexOp(){
						
		Tpl::setDirquna('shop');
        Tpl::showpage('express.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model = Model();
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('id', 'e_name', 'e_code', 'e_letter', 'e_url', 'e_order', 'e_state', 'e_zt_state');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $list = $model->table('express')->where($condition)->page($page)->order($order)->select();

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        foreach ($list as $value) {
            $param = array();
            $operation = "<a class='btn red' href='javascript:void(0);' onclick='fg_delete(".$value['id'].");'><i class='fa fa-trash-o'></i>删除</a>";
            $operation .= "<span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul>";
            $operation .= "<li><a href='index.php?act=express&op=edit_express&id=".$value['id']."'>编辑快递公司</a></li>";
            $operation .= "<li><a href='javascript:void(0);' onclick='ajaxget(\"" . urlAdminShop('express','ajax',array('id'=> $value['id'], 'column' => 'e_state', 'value' => ($value['e_state'] == 1 ? 0 : 1))) . "\")'>".($value['e_state'] == 1 ? '禁用快递公司' : '启用快递公司')."</a></li>";
            $operation .= "<li><a href='javascript:void(0);' onclick='ajaxget(\"" . urlAdminShop('express','ajax',array('id'=> $value['id'], 'column' => 'e_order', 'value' => ($value['e_order'] == 1 ? 0 : 1))) . "\")'>".($value['e_order'] == 1 ? '取消常用快递' : '设为常用快递')."</a></li>";
            $operation .= "<li><a href='javascript:void(0);' onclick='ajaxget(\"" . urlAdminShop('express','ajax',array('id'=> $value['id'], 'column' => 'e_zt_state', 'value' => ($value['e_zt_state'] == 1 ? 0 : 1))) . "\")'>".($value['e_zt_state'] == 1 ? '取消自提配送' : '设为自提配送')."</a></li>";
            $operation .= "</ul></span>";
            $param['operation'] = $operation;
            $param['e_name'] = $value['e_name'];
            $param['e_code'] = $value['e_code'];
            if (C('express_api') == '2') $param['e_code'] = $value['e_code_kdniao'];//v5. 5 b y 33h ao.com
            $param['e_letter'] = $value['e_letter'];
            $param['e_url'] = $value['e_url'];
            $param['e_state'] = $value['e_state'] == 1 ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $param['e_order'] = $value['e_order'] == 1 ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $param['e_zt_state'] = $value['e_zt_state'] == 1 ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * ajax操作
     */
    public function ajaxOp(){
        switch ($_GET['column']){
            case 'e_state':
                $model_brand = Model('express');
                $update_array = array();
                $update_array['e_state'] = trim($_GET['value']);
                $model_brand->where(array('id'=>intval($_GET['id'])))->update($update_array);
                dkcache('express');
                $this->log(L('nc_edit,express_name,express_state').'[ID:'.intval($_GET['id']).']',1);
                showDialog(L('nc_common_op_succ'), '', 'succ', '$("#flexigrid").flexReload();');
                break;
            case 'e_order':
                $_GET['value'] = $_GET['value'] == 0? 2:1;
                $model_brand = Model('express');
                $update_array = array();
                $update_array['e_order'] = trim($_GET['value']);
                $model_brand->where(array('id'=>intval($_GET['id'])))->update($update_array);
                dkcache('express');
                $this->log(L('nc_edit,express_name,express_state').'[ID:'.intval($_GET['id']).']',1);
                showDialog(L('nc_common_op_succ'), '', 'succ', '$("#flexigrid").flexReload();');
                break;
            case 'e_zt_state':
                $model_brand = Model('express');
                $update_array = array();
                $update_array['e_zt_state'] = trim($_GET['value']);
                $model_brand->where(array('id'=>intval($_GET['id'])))->update($update_array);
                dkcache('express');
                $this->log(L('nc_edit,express_name,express_state').'[ID:'.intval($_GET['id']).']',1);
                showDialog(L('nc_common_op_succ'), '', 'succ', '$("#flexigrid").flexReload();');
                break;
        }
        dkcache('express');
    }

    /**
     * 新增快递公司 3 3 H  A  O  v 5 .2
     */
    function add_expressOp(){
        if(chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["e_name"], "require"=>"true", "message"=>"快递公司名称不能为空"),
                array("input"=>$_POST["e_code"], "require"=>"true", "message"=>"快递公司编号不能为空"),
		array("input"=>$_POST["e_code_kdniao"], "require"=>"true", "message"=>"快递鸟快递公司代码不能为空"),
                array("input"=>$_POST["e_letter"], "require"=>"true", "message"=>"快递公司首字母不能为空")
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            } else {
                $param  = array();
                $param['e_name'] = trim($_POST['e_name']);
                $param['e_code'] = trim($_POST['e_code']);
		$param['e_code_kdniao'] = trim($_POST['e_code_kdniao']);
                $param['e_letter'] = trim($_POST['e_letter']);
                $param['e_url'] = trim($_POST['e_url']);
                $param['e_state'] = trim($_POST['e_state']);
                $param['e_order'] = trim($_POST['e_order']);
                $param['e_zt_state'] = trim($_POST['e_zt_state']);
                $express_model = Model('express');
                $result = $express_model->addExpress($param);
                if ($result){
                    $url = array(
                        array(
                            'url'=>'index.php?act=express&op=index',
                            'msg'=>'返回快递公司列表',
                        ),
                        array(
                            'url'=>'index.php?act=express&op=add_express',
                            'msg'=>'继续新增快递公司',
                        ),
                    );
                    dkcache('express');
                    $this->log('新增快递公司'.'['.$_POST['e_name'].']',null);
                    showMessage("新增快递公司成功",$url);
                }else{
                    showMessage("新增快递公司失败");
                }
            }
        }
        Tpl::setDirquna('shop');
        Tpl::showpage('express.add');
    }

    /**
     * 编辑快递公司
     */
    function edit_expressOp(){
        if(chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["e_name"], "require"=>"true", "message"=>"快递公司名称不能为空"),
                array("input"=>$_POST["e_code"], "require"=>"true", "message"=>"快递公司编号不能为空"),
		array("input"=>$_POST["e_code_kdniao"], "require"=>"true", "message"=>"快递鸟快递公司代码不能为空"),
                array("input"=>$_POST["e_letter"], "require"=>"true", "message"=>"快递公司首字母不能为空")
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            } else {
                $param  = array();
                $param['e_name'] = trim($_POST['e_name']);
                $param['e_code'] = trim($_POST['e_code']);
		$param['e_code_kdniao'] = trim($_POST['e_code_kdniao']);
                $param['e_letter'] = trim($_POST['e_letter']);
                $param['e_url'] = trim($_POST['e_url']);
                $param['e_state'] = trim($_POST['e_state']);
                $param['e_order'] = trim($_POST['e_order']);
                $param['e_zt_state'] = trim($_POST['e_zt_state']);
                $express_model = Model('express');
                $result = $express_model->updateExpress($param,array('id'=>intval($_POST['id'])));
                if ($result){
                    $url = array(
                        array(
                            'url'=>'index.php?act=express&op=index',
                            'msg'=>'返回快递公司列表',
                        ),
                        array(
                            'url'=>'index.php?act=express&op=edit_express&id='.intval($_POST['id']),
                            'msg'=>'重新编辑该快递公司',
                        ),
                    );
                    dkcache('express');
                    $this->log('编辑快递公司'.'['.$_POST['e_name'].']',null);
                    showMessage("编辑快递公司成功",$url);
                }else{
                    showMessage("编辑快递公司失败");
                }
            }
        }
        $express_id = trim($_REQUEST['id']);
        $express_model = Model('express');
        $express_array = $express_model->getOne($express_id);
        if (empty($express_array)){
            showMessage('参数错误');
        }
        Tpl::output('express_array',$express_array);
        Tpl::setDirquna('shop');
        Tpl::showpage('express.edit');
    }

    /**
     * 删除快递公司
     */
    function del_expressOp(){
        $express_id = trim($_REQUEST['del_id']);
        $express_model = Model('express');
        $condition = array();
        $condition['id'] = array('in',$express_id);
        $result = $express_model->delExpress($condition);
        if($result) {
            dkcache('express');
            $this->log('删除快递公司['.$express_id.']', 1);
            showMessage('删除快递公司成功','');
        } else {
            showMessage('删除快递公司失败','','','error');
        }

    }
}

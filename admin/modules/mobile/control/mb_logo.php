<?php
/**
 * 手机端LOGO图片设置
 *
 * by suibianlu.com  V5 .3
 *
 *
 */



defined('In33hao') or exit('Access Invalid!');
class mb_logoControl extends SystemControl{
    public function __construct(){
        parent::__construct();
//         Language::read('mobile');
    }
	
	
    public function indexOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            if ($_FILES['mobile_logo']['tmp_name'] != ''){
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_COMMON);
				$upload->file_name='home_logo.png';
                $result = $upload->upfile('mobile_logo');
                if ($result){
                    $_POST['mobile_logo'] = $upload->file_name;
                }else {
                    showMessage($upload->error);
                }
            }
            $update_array = array();
            if (!empty($_POST['mobile_logo'])){
				$update_array['mobile_logo'] = $_POST['mobile_logo'];
			}
            $result = $model_setting->updateSetting($update_array);
            if ($result){
                if (!empty($mobile_logo)){
                    @unlink(BASE_ROOT_PATH.DS.DIR_UPLOAD.DS.ATTACH_COMMON.'/'.'home_logo.png');
                }
                showMessage(Language::get('nc_common_save_succ'));
            }else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        Tpl::output('mobile_logo',$mobile_logo);
        Tpl::setDirquna('mobile');
Tpl::showpage('mb_logo.index');
    }
}

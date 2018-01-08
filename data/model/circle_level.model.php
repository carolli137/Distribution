<?php
/**
 * Circle Level
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.suibianlu.com)
 * @license    http://www.sui bian lu.c om
 * @link       交流群号：575710573
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');

class circle_levelModel extends Model {
    public function __construct(){
        parent::__construct();
    }
    /**
     * insert
     * @param array $insert
     * @param bool $replace
     */
    public function levelInsert($insert, $replace){
        $this->table('circle_ml')->insert($insert, $replace);
        return $this->updateLevelName($insert);
    }

    /**
     * update level name
     * @param array $insert
     */
    private function updateLevelName($insert){
        $str = '( case cm_level ';
        for ($i=1; $i<=16; $i++){
            $str .= ' when '.$i.' then \''.$insert['ml_'.$i].'\'';
        }
        $str .= ' else cm_levelname end)';

        $update = array();
        $update['cm_levelname'] = array('exp',$str);

        $where = array();
        $where['circle_id'] = $insert['circle_id'];
        return $this->table('circle_member')->where($where)->update($update);
    }
}

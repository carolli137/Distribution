<?php
/**
 * 验证码
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.suibianlu.com)
 * @license    http://www.sui bian lu.c om
 * @link       交流群号：575710573
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');


class seccodeControl{
    /**
     * 生成验证码标识
     */
    public function makecodekeyOp(){
        $key = $this->makeApiSeccodeKey();
        output_data(array('codekey' => $key));
    }

    /**
     * 产生验证码
     */
    public function makecodeOp(){
        $param = $_GET;
        $key = $param['k']?trim($param['k']):'';
        if (!$key) {
            die(false);
        }
        $seccode = $this->makeApiSeccode();
        $result = Model('apiseccode')->addApiSeccode($key,$seccode);
        if (!$result) {
            die(false);
        }
        @header("Expires: -1");
        @header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
        @header("Pragma: no-cache");
        $width = 120;
        $height = 50;
        if ($_GET['type']) {
            $param = explode(',', $_GET['type']);
            $width = intval($param[1]);
            $height = intval($param[0]);
        }
		
		$code = new seccode();
		$code->code = $seccode;
		$code->width = $width;
		$code->height = $height;
		$code->background = 1;
		$code->adulterate = 1;
		$code->scatter = '';
		$code->color = 1;
		$code->size = 0;
		$code->shadow = 1;
		$code->animator = 0;
		$code->datapath =  BASE_DATA_PATH.'/resource/seccode/';
		$code->display();
    }
    /**
     * 产生验证码名称标识
     *
     * @param string $nchash 哈希数
     * @return string
     */
    private function makeApiSeccodeKey(){
        return md5(uniqid(mt_rand(), true));
    }
    /**
     * 产生验证码
     *
     * @param string $nchash 哈希数
     * @return string
     */
    private function makeApiSeccode(){
        $seccode = random(6, 1);
        $seccodeunits = '';

        $s = sprintf('%04s', base_convert($seccode, 10, 23));
        $seccodeunits = 'ABCEFGHJKMPRTVXY2346789';
        if($seccodeunits) {
            $seccode = '';
            for($i = 0; $i < 4; $i++) {
                $unit = ord($s{$i});
                $seccode .= ($unit >= 0x30 && $unit <= 0x39) ? $seccodeunits[$unit - 0x30] : $seccodeunits[$unit - 0x57];
            }
        }
        return $seccode;
    }
}

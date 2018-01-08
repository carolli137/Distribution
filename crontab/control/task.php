<?php
/**
 * 计划任务 v5.3 by suibianlu.com
 *
 **/
defined('In33hao') or exit('Access Invalid!');

class taskControl extends BaseCronControl
{
    public function indexOp()
    {
    //验证客户端工具密码 调试用
	$task_pwd = '33hao';
	//$task_pwd = md5(trim($task_pwd));
        if ($_GET['run'] == 'js') {
            $run = 'js';
        } elseif ($task_pwd == $_GET['runpwd']) {
            $run = '';
        } elseif (!empty($_GET['runpwd']) && $task_pwd != $_GET['runpwd']) {
            exit('Password Error!');
        } elseif (isset($_GET['runpwd'])) {
            exit('Access Invalid!');
        } else {
            exit('Access Invalid!');
        }
        //取当前时间值
        $ntime = time();
		$task  = Model()->table('task')->where(array('islock'=>0))->select();
        while (list($key, $arr) = each($task)) {
            $starttime = $arr['starttime'];
            $endtime   = $arr['endtime'];
            //跳过一次性运行，并且已经运行的任务
            if ($arr['runtype'] == 1 && $arr['lastrun'] > $starttime)
                continue;
            //超过了设定的任务结束时间
            if ($endtime != 0 && $endtime < $ntime)
                continue;
            //未达到任务开始时间的任务
            if ($starttime != 0 && $ntime < $starttime)
                continue;
            $limittime = 60 * $arr['freq'];
            $isplay    = false;
            //判断符合执行条件的任务
            list($rh, $ri, $rs) = explode(':', $arr['runtime']);
            list($ly, $lm, $ld) = explode('-', date('Y-m-d', $arr['lastrun']));
            $rh        = intval($rh);
            $ri        = intval($ri);
            $rs        = intval($rs);
            $ly        = intval($ly);
            $lm        = intval($lm);
            $ld        = intval($ld);
            $lastrun   = strtotime($ly . '-' . $lm . '-' . $ld . ' ' . $rh . ':' . $ri . ':' . $rs);
            $riTotal   = 60 * $ri;
            $riDiff    = $ntime - $arr['lastrun'] - $rs;
            $rhTotal   = 3600 * $rh;
            $rhDiff    = $ntime - $arr['lastrun'] - 60 * $ri - $rs;
            $tianTotal = 86400;
            $tianDiff  = $lastrun + 86400 - $ntime;
            $zhouTotal = 604800;
            $zhouDiff  = $lastrun + 604800 - $ntime;
            $yueTotal  = 2592000;
            $yueDiff   = $lastrun + 2592000 - $ntime;
            if ($arr['lastrun'] == 0) {
                $isplay = true;
            } elseif ($arr['freq'] == 1 && $riDiff > $riTotal) {
                $isplay = true;
            } elseif ($arr['freq'] == 60 && $rhDiff > $rhTotal) {
                $isplay = true;
            } elseif ($arr['freq'] == 1440 && $tianDiff < 0) {
                $isplay = true;
            } elseif ($arr['freq'] == 10080 && $zhouDiff < 0) {
                $isplay = true;
            } elseif ($arr['freq'] == 43200 && $yueDiff < 0) {
                $isplay = true;
            }
            //符合需执行条件的任务
            if ($isplay) {
                if (!file_exists("../crontab/" . $dourl)) {
                    exit('Task file does not exist!');
                } else {
                    $getString = trim($arr['parameter']);
					Model()->table('task')->where(array('id'=> $arr['id']))->update(array('lastrun'=>$ntime,'sta'=>'运行'));
                    if ($getString != '') {
                        $dourl = BASE_SITE_URL . '/crontab/cj_index.php' . $dourl . '?' . $getString;
                    } else {
                        $dourl = BASE_SITE_URL . '/crontab/cj_index.php' . $dourl;
                    }
                    if ($run == 'js') {
                        echo '<iframe src="' . $dourl . '" width="0" height="0" frameborder="0" style="display:none;"></iframe>';
                    } else {
		    	header('Location: ' . $dourl);
                    }
                }
            }
        }
    }
}

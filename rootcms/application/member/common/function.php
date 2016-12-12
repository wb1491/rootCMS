<?php

/**
 * 会员中心相关自定义函数
 * Some rights reserved：abc3210.com
 * Contact email:admin@abc3210.com
 */

/**
 * 会员空间个性URL地址组装 支持不同URL模式
 * @param string $url URL表达式，格式：'[分组/模块/操作#锚点@域名]?参数1=值1&参数2=值2...'
 * @param string|array $vars 传入的参数，支持数组和字符串
 * @param string $suffix 伪静态后缀，默认为true表示获取配置值
 * @param boolean $redirect 是否跳转，如果设置为true则表示跳转到该URL地址
 * @param boolean $domain 是否显示域名
 * @return string
 */
function UM($url = '', $vars = '', $suffix = true, $redirect = false, $domain = true) {
    return  url($url, $vars, $suffix, $redirect, $domain);
}

/**
 * 友好的时间显示
 *
 * @param int    $sTime 待显示的时间
 * @param string $type  类型. normal | mohu | full | ymd | other
 * @return string
 */
function format_date($sTime, $type = 'mohu') {
    //sTime=源时间，cTime=当前时间，dTime=时间差
    $cTime = time();
    $dTime = $cTime - $sTime;
    $dDay = intval(date("z", $cTime)) - intval(date("z", $sTime));
    //$dDay        =    intval($dTime/3600/24);
    $dYear = intval(date("Y", $cTime)) - intval(date("Y", $sTime));
    //normal：n秒前，n分钟前，n小时前，日期
    if ($type == 'normal') {
        if ($dTime < 60) {
            return $dTime ? $dTime . "秒前" : '刚刚';
        } elseif ($dTime < 3600) {
            return intval($dTime / 60) . "分钟前";
            //今天的数据.年份相同.日期相同.
        } elseif ($dYear == 0 && $dDay == 0) {
            //return intval($dTime/3600)."小时前";
            return '今天' . date('H:i', $sTime);
        } elseif ($dYear == 0) {
            return date("m月d日 H:i", $sTime);
        } else {
            return date("Y-m-d H:i", $sTime);
        }
    } elseif ($type == 'mohu') {
        if ($dTime < 60) {
            return $dTime ? $dTime . "秒前" : '刚刚';
        } elseif ($dTime < 3600) {
            return intval($dTime / 60) . "分钟前";
        } elseif ($dTime >= 3600 && $dDay == 0) {
            return intval($dTime / 3600) . "小时前";
        } elseif ($dDay > 0 && $dDay <= 7) {
            return intval($dDay) . "天前";
        } elseif ($dDay > 7 && $dDay <= 30) {
            return intval($dDay / 7) . '周前';
        } elseif ($dDay > 30) {
            return intval($dDay / 30) . '个月前';
        } else {
            return date("Y-m-d H:i", $sTime);
        }
        //full: Y-m-d , H:i:s
    } elseif ($type == 'full') {
        return date("Y-m-d , H:i:s", $sTime);
    } elseif ($type == 'ymd') {
        return date("Y-m-d", $sTime);
    } else {
        if ($dTime < 60) {
            return $dTime ? $dTime . "秒前" : '刚刚';
        } elseif ($dTime < 3600) {
            return intval($dTime / 60) . "分钟前";
        } elseif ($dTime >= 3600 && $dDay == 0) {
            return intval($dTime / 3600) . "小时前";
        } elseif ($dYear == 0) {
            return date("Y-m-d H:i:s", $sTime);
        } else {
            return date("Y-m-d H:i:s", $sTime);
        }
    }
}

/**
 *  匹配字符串中 at 的用户信息 ，被匹配的格式为：@水平凡[2]
 * @param type $str 字符串
 * @return boolean 返回匹配成功后的数组
 */
function matchAtUser($str) {
    if (empty($str)) {
        return false;
    }
    preg_match_all("/@(.*?)\[([0-9]+)\]/i", $str, $matches);
    if ($matches) {
        $atUser = array();
        foreach ($matches[0] as $k => $v) {
            $atUser[$k] = array(
                'matches' => $v,
                'username' => $matches[1][$k],
                'userid' => $matches[2][$k],
            );
        }
        return $atUser;
    }
    return false;
}
/**
 * 获取学员报名时的课题类型名称
 * @param type $id
 * @param type $field
 * @param array $data
 */
function getStuCate($id,$field='catename',$data=array()){
    if(empty($data)){
        $data =  model("member/Stucate")->getCateData();
    }
    if($id>0 && isset($data[$id]) && isset($data[$id][$field])){
        return $data[$id][$field];
    }
    return '';
}

function getStartTime($status=NULL){
    $starttime =array(
            "不限制",
            "1个月",
            "2个月",
            "3个月",
            "半年",
            "1年"
    );
    if(!is_null($status)){
        return isset($starttime[$status])?$starttime[$status]:0;
    }else{
        return $starttime;
    }
}

function getVipType($vipid,$field='',$data=array()){
    if(empty($data)){
        $tmpdata = M("member_viptype")->select();
        foreach($tmpdata as $v){
            $data[$v['id']] = $v;
        }
    }
    if($vipid>0 && isset($data[$vipid]) && isset($data[$vipid][$field])){
        return $data[$vipid][$field];
    }elseif(empty($field)){
        return $data;
    }
    
    return '';
}
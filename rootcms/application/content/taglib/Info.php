<?php

// +----------------------------------------------------------------------
// | rootCMS 推荐位解析标签
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wb23 <wb1491@gmail.com>
// +----------------------------------------------------------------------

namespace app\content\taglib;
use Content\Model\FlightInfoInModel;
use Content\Model\FlightInfoOutModel;

class Info {

    /**
     * 航班信息数据获取
     * 参数名	 是否必须	 默认值	 说明
     * type      否      null    信息类型，是出港航班还是入港航班
     * order	 否	 null	 排序类型
     * num	 是	 null	 数据调用数量
     * where     否      null    查询条件
     * @param type $data 
     */
    public function info($data) {
        $dbIn = new FlightInfoInModel();
        $dbOut = new FlightInfoOutModel();

        $type = empty($data['type']) ? "in":strtolower($data['type']);
        $order = empty($data['order']) ? array("TKFTM" => "ASC") : $data['order'];
        $num = (int) $data['num'];
        
        $where = array();
        //设置SQL where 部分
        if (isset($data['where']) && $data['where']) {
            $where['_string'] = $data['where'];
        }
        if ($type=='in'){
            $data = $dbIn->field("CLNAME,AIRCORP,FLTNO,TKFPNAME,PASS1NAME,ARRPNAME,FSTATUSNAME,"
                    . "to_char(ARRTM,'hh24:mi:ss') as ARRTM,to_char(TKFTM, 'hh24:mi:ss') as TKFTM,"
                    . "to_char(RTKFTM,'hh24:mi:ss') as RTKFTM,to_char(RARRTM,'hh24:mi:ss') as RARRTM")
                          ->where($where)->order($order)->limit($num)->select();
        }else{
            $data = $dbOut->field("CLNAME,AIRCORP,FLTNO,TKFPNAME,PASS1NAME,ARRPNAME,FSTATUSNAME,"
                    . "to_char(ARRTM,'hh24:mi:ss') as ARRTM,to_char(TKFTM, 'hh24:mi:ss') as TKFTM,"
                    . "to_char(RTKFTM,'hh24:mi:ss') as RTKFTM,to_char(RARRTM,'hh24:mi:ss') as RARRTM")
                          ->where($where)->order($order)->limit($num)->select();
        }
        return $data;
    }

}

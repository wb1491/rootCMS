<?php

// +----------------------------------------------------------------------
// | rootCMS URL规则模型
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\content\model;

use \think\Model;

class Urlrule extends Model {

    //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
    protected $_validate = array(
        array('file', 'require', 'URL规则名称必须填写！'),
        array('module', 'require', '模块名称必须填写！'),
        array('ishtml', 'require', '是否生成静态必须填写！'),
        array('example', 'require', 'URL示例必须填写！'),
        array('urlrule', 'require', 'URL规则必须填写！'),
    );

    /**
     * 更新URL规则 缓存
     */
    public function urlrule_cache() {
        //完整数据缓存
        $data = array();
        foreach ($this->select() as $roleid => $r) {
            $data[$r['urlruleid']] = $r;
        }
        sys_cache('Urlrules', $data);
        return $data;
    }

}

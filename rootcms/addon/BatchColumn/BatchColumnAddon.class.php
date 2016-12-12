<?php

// +----------------------------------------------------------------------
// | rootCMS 批量栏目设置 插件
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace Addon\BatchColumn;

use \Addons\Util\Addon;

class BatchColumnAddon extends Addon {

    //插件信息
    public $info = array(
        'name' => 'BatchColumn',
        'title' => '批量栏目设置',
        'description' => '对ShuipFCMS栏目进行相关的批量设置！',
        'status' => 1,
        'author' => '水平凡',
        'version' => '1.0.0',
        'has_adminlist' => 1,
    );
    //有开启插件后台情况下，添加对应的控制器方法
    //也就是插件目录下 Action/AdminAction.class.php中，public属性的方法！
    //每个方法都是一个数组形式，删除，修改类需要具体参数的，建议隐藏！
    public $adminlist = array(
        
    );

    //安装
    public function install() {
        return true;
    }

    //卸载
    public function uninstall() {
        return true;
    }

}

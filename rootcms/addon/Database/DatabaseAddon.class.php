<?php

// +----------------------------------------------------------------------
// | rootCMS 数据库备份 插件
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace Addon\Database;

use \Addons\Util\Addon;

class DatabaseAddon extends Addon {

    //插件信息
    public $info = array(
        'name' => 'Database',
        'title' => '数据库备份',
        'description' => '简单的数据库备份',
        'status' => 1,
        'author' => '水平凡',
        'version' => '1.0.0',
        'has_adminlist' => 1,
    );
    //有开启插件后台情况下，添加对应的控制器方法
    //也就是插件目录下 Action/AdminAction.class.php中，public属性的方法！
    //每个方法都是一个数组形式，删除，修改类需要具体参数的，建议隐藏！
    public $adminlist = array(
        array(
            //方法名称
            "action" => "index",
            //附加参数 例如：a=12&id=777
            "data" => "",
            //类型，1：权限认证+菜单，0：只作为菜单
            "type" => 1,
            //状态，1是显示，2是不显示
            "status" => 1,
            //名称
            "name" => "数据库备份",
            //备注
            "remark" => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action" => "restore",
            //附加参数 例如：a=12&id=777
            "data" => "",
            //类型，1：权限认证+菜单，0：只作为菜单
            "type" => 1,
            //状态，1是显示，2是不显示
            "status" => 1,
            //名称
            "name" => "备份还原",
            //备注
            "remark" => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action" => "del",
            //附加参数 例如：a=12&id=777
            "data" => "",
            //类型，1：权限认证+菜单，0：只作为菜单
            "type" => 1,
            //状态，1是显示，2是不显示
            "status" => 0,
            //名称
            "name" => "删除备份",
            //备注
            "remark" => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action" => "repair",
            //附加参数 例如：a=12&id=777
            "data" => "",
            //类型，1：权限认证+菜单，0：只作为菜单
            "type" => 1,
            //状态，1是显示，2是不显示
            "status" => 0,
            //名称
            "name" => "修复表",
            //备注
            "remark" => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action" => "optimization",
            //附加参数 例如：a=12&id=777
            "data" => "",
            //类型，1：权限认证+菜单，0：只作为菜单
            "type" => 1,
            //状态，1是显示，2是不显示
            "status" => 0,
            //名称
            "name" => "优化表",
            //备注
            "remark" => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action" => "import",
            //附加参数 例如：a=12&id=777
            "data" => "",
            //类型，1：权限认证+菜单，0：只作为菜单
            "type" => 1,
            //状态，1是显示，2是不显示
            "status" => 0,
            //名称
            "name" => "还原表",
            //备注
            "remark" => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action" => "export",
            //附加参数 例如：a=12&id=777
            "data" => "",
            //类型，1：权限认证+菜单，0：只作为菜单
            "type" => 1,
            //状态，1是显示，2是不显示
            "status" => 0,
            //名称
            "name" => "备份数据库",
            //备注
            "remark" => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action" => "download",
            //附加参数 例如：a=12&id=777
            "data" => "",
            //类型，1：权限认证+菜单，0：只作为菜单
            "type" => 1,
            //状态，1是显示，2是不显示
            "status" => 0,
            //名称
            "name" => "备份数据库下载",
            //备注
            "remark" => "",
            //排序
            "listorder" => 0,
        ),
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

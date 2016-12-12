<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Addon\Wxpay;

use \Addons\Util\Addon;

class WxpayAddon extends Addon {
    //插件信息
    public $info = array(
        'name' => 'Wxpay',
        'title' => '微信支付插件',
        'description' => '这是一个微信支付的接口插件。',
        'status' => 1,
        'author' => 'wb23',
        'version' => '1.0.0',
        'has_adminlist' => 1,
        'sign' => 'effda0392ce9182ff45fa2c0f3db5120',
    );
    //有开启插件后台情况下，添加对应的控制器方法
    //也就是插件目录下 Action/AdminController.class.php中，public属性的方法！
    //每个方法都是一个数组形式，删除，修改类需要具体参数的，建议隐藏！
    public $adminlist = array(
        array(
            //方法名称
            "action" => "index",
            //附加参数 例如：a=12&id=777
            "data" => "isadmin=1",
            //类型，1：权限认证+菜单，0：只作为菜单
            "type" => 1,
            //状态，1是显示，0是不显示
            "status" => 1,
            //名称
            "name" => "微信支付插件",
            //备注
            "remark" => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action" => "orderlist",
            //附加参数 例如：a=12&id=777
            "data" => "isadmin=1",
            //类型，1：权限认证+菜单，0：只作为菜单
            "type" => 1,
            //状态，1是显示，0是不显示
            "status" => 1,
            //名称
            "name" => "订单管理",
            //备注
            "remark" => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action" => "amontlog",
            //附加参数 例如：a=12&id=777
            "data" => "isadmin=1",
            //类型，1：权限认证+菜单，0：只作为菜单
            "type" => 1,
            //状态，1是显示，0是不显示
            "status" => 1,
            //名称
            "name" => "资金日志",
            //备注
            "remark" => "",
            //排序
            "listorder" => 0,
        )
    );
    
    //安装
    public function install() {
        if (!file_exists(SITE_PATH . '/statics/addons/wxpay/')) {
            //创建目录
            if (mkdir(SITE_PATH . '/statics/addons/wxpay/', 0777, true) == false) {
                $this->error = '创建目录 [statics/addons/wxpay] 失败！';
                return false;
            }
        }
        //移动文件
        ShuipFCMS()->Dir->copyDir($this->addonPath . "statics/", SITE_PATH . '/statics/addons/');
       
        return true;
    }

    //卸载
    public function uninstall() {
        ShuipFCMS()->Dir->delDir(SITE_PATH . '/statics/addons/wxpay/');
        return true;
    }
}
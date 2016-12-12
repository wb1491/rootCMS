<?php

/**
 * 支付宝支付插件 插件
 * Some rights reserved：abc3210.com
 * Contact email:admin@abc3210.com
 */

namespace Addon\Alipay;

use \Addons\Util\Addon;

class AlipayAddon extends Addon {

    //插件信息
    public $info = array(
        'name' => 'Alipay',
        'title' => '支付宝支付插件',
        'description' => '这是一个支付宝双功能的支付插件。',
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
            "action" => "config",
            //附加参数 例如：a=12&id=777
            "data" => "",
            //类型，1：权限认证+菜单，0：只作为菜单
            "type" => 1,
            //状态，1是显示，0是不显示
            "status" => 1,
            //名称
            "name" => "微信支付接口",
            //备注
            "remark" => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action" => "type",
            //附加参数 例如：a=12&id=777
            "data" => "",
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
            "action" => "log",
            //附加参数 例如：a=12&id=777
            "data" => "",
            //类型，1：权限认证+菜单，0：只作为菜单
            "type" => 1,
            //状态，1是显示，0是不显示
            "status" => 1,
            //名称
            "name" => "订单日志",
            //备注
            "remark" => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action" => "add",
            //附加参数 例如：a=12&id=777
            "data" => "",
            //类型，1：权限认证+菜单，0：只作为菜单
            "type" => 1,
            //状态，1是显示，0是不显示
            "status" => 0,
            //名称
            "name" => "添加订单类型",
            //备注
            "remark" => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action" => "delete",
            //附加参数 例如：a=12&id=777
            "data" => "",
            //类型，1：权限认证+菜单，0：只作为菜单
            "type" => 1,
            //状态，1是显示，0是不显示
            "status" => 0,
            //名称
            "name" => "删除订单类型",
            //备注
            "remark" => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action" => "edit",
            //附加参数 例如：a=12&id=777
            "data" => "",
            //类型，1：权限认证+菜单，0：只作为菜单
            "type" => 1,
            //状态，1是显示，0是不显示
            "status" => 0,
            //名称
            "name" => "修改订单类型",
            //备注
            "remark" => "",
            //排序
            "listorder" => 0,
        ),
    );

    //安装
    public function install() {
        if (!file_exists(SITE_PATH . '/statics/addons/alipay/')) {
            //创建目录
            if (mkdir(SITE_PATH . '/statics/addons/alipay/', 0777, true) == false) {
                $this->error = '创建目录 [statics/addons/alipay] 失败！';
                return false;
            }
        }
        //移动文件
        ShuipFCMS()->Dir->copyDir($this->addonPath . "statics/", SITE_PATH . '/statics/addons/');
        copy($this->addonPath . 'alipay.php', SITE_PATH . 'alipay.php');
        $slq = $this->addonPath . 'Data/alipay.sql';
        $sql = file_get_contents($slq);
        $sql = $this->sqlSplit($sql, config("DB_PREFIX"));
        if (!empty($sql) && is_array($sql)) {
            foreach ($sql as $sql_split) {
                M()->execute($sql_split);
            }
        }
        return true;
    }

    //卸载
    public function uninstall() {
        M()->query("DROP TABLE " . config("DB_PREFIX") . 'alipay');
        M()->query("DROP TABLE " . config("DB_PREFIX") . 'alipay_log');
        M()->query("DROP TABLE " . config("DB_PREFIX") . 'alipay_type');
        unlink(SITE_PATH . 'alipay.php');
        ShuipFCMS()->Dir->delDir(SITE_PATH . '/statics/addons/alipay/');
        return true;
    }

    /**
     * 分析处理sql语句，执行替换前缀都功能。
     * @param string $sql 原始的sql
     * @param string $tablepre 表前缀
     */
    private function sqlSplit($sql, $tablepre) {
        if ($tablepre != "shuipfcms_")
            $sql = str_replace("shuipfcms_", $tablepre, $sql);
        $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8", $sql);
        if ($r_tablepre != $s_tablepre)
            $sql = str_replace($s_tablepre, $r_tablepre, $sql);
        $sql = str_replace("\r", "\n", $sql);
        $ret = array();
        $num = 0;
        $queriesarray = explode(";\n", trim($sql));
        unset($sql);
        foreach ($queriesarray as $query) {
            $ret[$num] = '';
            $queries = explode("\n", trim($query));
            $queries = array_filter($queries);
            foreach ($queries as $query) {
                $str1 = substr($query, 0, 1);
                if ($str1 != '#' && $str1 != '-')
                    $ret[$num] .= $query;
            }
            $num++;
        }
        return $ret;
    }

}

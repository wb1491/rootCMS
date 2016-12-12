<?php

// +----------------------------------------------------------------------
// | rootCMS 关键词内联 插件
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace Addon\Keylink;

use \Addons\Util\Addon;

class KeylinkAddon extends Addon {

    //插件信息
    public $info = array(
        'name' => 'Keylink',
        'title' => '关键词内联',
        'description' => '内容关键词内联插件',
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
            "action" => "delete",
            //附加参数 例如：a=12&id=777
            "data" => "",
            //类型，1：权限认证+菜单，0：只作为菜单
            "type" => 1,
            //状态，1是显示，0是不显示
            "status" => 0,
            //名称
            "name" => "删除关键字",
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
            "status" => 1,
            //名称
            "name" => "添加关键字",
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
            "name" => "编辑关键字",
            //备注
            "remark" => "",
            //排序
            "listorder" => 0,
        ),
    );

    //安装
    public function install() {
        M()->execute("DROP TABLE IF EXISTS `" . config('DB_PREFIX') . "keylink`;");
        M()->execute("CREATE TABLE `" . config('DB_PREFIX') . "keylink` (
  `keylinkid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `word` char(40) NOT NULL COMMENT '关键字',
  `url` char(100) NOT NULL COMMENT '链接地址',
  `frequency` tinyint(255) DEFAULT '0' COMMENT '替换次数',
  PRIMARY KEY (`keylinkid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        //检查行为是否有添加
        $Behavior =  model('Common/Behavior');
        config('TOKEN_ON', false);
        if ($Behavior->where(array('name' => 'content_add_begin'))->count() == 0) {
            $Behavior->addBehavior(array(
                'name' => 'content_add_begin',
                'title' => '内容添加前行为调用',
                'type' => 1,
            ));
        }
        if ($Behavior->where(array('name' => 'content_edit_begin'))->count() == 0) {
            $Behavior->addBehavior(array(
                'name' => 'content_edit_begin',
                'title' => '内容编辑前前行为调用',
                'type' => 1,
            ));
        }
        return true;
    }

    //卸载
    public function uninstall() {
        M()->execute("DROP TABLE IF EXISTS `" . config('DB_PREFIX') . "keylink`;");
        return true;
    }

    //实现行为 content_add_begin
    public function content_add_begin(&$data) {
        return $this->keylink($data);
    }

    //实现行为 content_edit_begin
    public function content_edit_begin(&$data) {
        return $this->keylink($data);
    }

    protected function keylink(&$data) {
        //可以在编辑器中加个开关，下标 enablekeylink  = 1 关闭关键词替换
        if (isset($_POST['enablekeylink']) && $_POST['enablekeylink']) {
            return false;
        }
        if (empty($data)) {
            return false;
        }
        $cache = cache('keylink_cache');
        if (empty($cache)) {
            import('KeylinkModel', $this->addonPath);
            $cache = \Think\Think::instance('\Addon\Keylink\KeylinkModel')->keylink_cache();
        }
        if (empty($cache)) {
            return false;
        }
        $txt = $data['content'];
        if ($cache) {
            $word = $replacement = array();
            foreach ($cache as $v) {
                $word1 = '/(?!(<a.*?))' . preg_quote($v['word'], '/') . '(?!.*<\/a>)/s';
                $word2 = $v['word'];
                $replacement = '<a href="' . $v['url'] . '" target="_blank" class="keylink">' . $v['word'] . '</a>';
                //替换次数
                if ($v['frequency']) {
                    $txt = preg_replace($word1, $replacement, $txt, $v['frequency']);
                } else {
                    $txt = preg_replace($word1, $replacement, $txt);
                }
            }
        }
        $data['content'] = $txt;
        return true;
    }

}

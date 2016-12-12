<?php

// +----------------------------------------------------------------------
// | rootCMS 插件
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace Addons\Controller;

class EmptyController {

    //插件标识
    public $addonName = NULL;
    //插件路径
    protected $addonPath = NULL;

    public function __construct() {
        $this->addonName = CONTROLLER_NAME;
        $this->addonPath =  model('Addons/Addons')->getAddonsPath() . $this->addonName . '/';
    }

    //魔术方法
    public function __call($method, $args) {
        $isAdmin = input('get.isadmin');
        $class = $isAdmin ? 'Admin' : 'Index';
        if (!require_cache("{$this->addonPath}Controller/{$class}Controller.class.php")) {
            exception("插件{$this->addonName}实例化错误！");
        }
        define('ADDON_MODULE_NAME', $class);
        $object = \Think\Think::instance("\\Addon\\".$this->addonName."\\Controller\\{$class}Controller");
        return $object->$method($args);
    }

}

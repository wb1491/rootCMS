<?php

// +----------------------------------------------------------------------
// | rootCMS 前台Controller
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\common\controller;

class Base extends CMS {

    //初始化
    protected function _initialize() {
        parent::_initialize();
        //静态资源路径
        $this->assign('model_extresdir', self::$Cache['Config']['siteurl'] . MODULE_EXTRESDIR);
    }

    /**
     * 模板显示 调用内置的模板引擎显示方法，
     * @access protected
     * @param string $templateFile 指定要调用的模板文件
     * 默认为空 由系统自动定位模板文件
     * @param array     $vars     模板输出变量
     * @param array     $replace     模板替换
     * @param array     $config     模板参数
     * @return void
     */
    protected function display($templateFile = '', $vars = [], $replace = [], $config = []) {
        return $this->view->fetch(parseTemplateFile($templateFile), $vars, $replace, $config);
    }

    /**
     * 前台操作错误跳转的快捷方法
     * @access protected
     * @param string $message 错误信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $data 返回数据
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    final public function error($message = '', $jumpUrl = '', $data=array(), $ajax = false) {
        //模板路径
        $TemplatePath = TEMPLATE_PATH ."index".DS;
        //模板主题
        $Theme = empty(parent::$Cache["Config"]['theme']) ? 'default' : parent::$Cache["Config"]['theme'];

        $errorTemplate = $TemplatePath.$Theme.DS."error.php";
        if(file_exists($errorTemplate)){
            config('dispatch_error_tmpl',$errorTemplate);
            
        }
        parent::error($message, $jumpUrl, $data, $ajax);
    }

    /**
     * 前台操作成功跳转的快捷方法
     * @access protected
     * @param string $message 提示信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $data 数据
     * @param integer $wait 指定跳转时间
     * @return void
     */
    final public function success($message = '', $jumpUrl = '', $data=array(), $wait = 3) {
        //模板路径
        $TemplatePath = TEMPLATE_PATH ."index".DS;
        //模板主题
        $Theme = empty(parent::$Cache["Config"]['theme']) ? 'default' : parent::$Cache["Config"]['theme'];

        $successTemplate = $TemplatePath.$Theme.DS."success.php";
        if(file_exists($successTemplate)){
            config('dispatch_success_tmpl',$successTemplate);
        }
        parent::success($message, $jumpUrl, $data, $wait);
    }
}

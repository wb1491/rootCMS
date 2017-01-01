<?php

// +----------------------------------------------------------------------
// | rootCMS 后台Controller
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\common\controller;

use app\admin\service\User;
use system\RBAC;

//定义是后台
define('IN_ADMIN', true);

class AdminBase extends CMS {
    
    private $themePath = '';

    //初始化
    protected function _initialize() {
        config(array(
            "USER_AUTH_ON" => true, //是否开启权限认证
            "USER_AUTH_TYPE" => 1, //默认认证类型 1 登录认证 2 实时认证
            "REQUIRE_AUTH_MODULE" => "", //需要认证模块
            "NOT_AUTH_MODULE" => "Publics", //无需认证模块
            "USER_AUTH_GATEWAY" =>  url("admin/Publics/login"), //登录地址
        ));
        
        //模板路径
        $TemplatePath = TEMPLATE_PATH ."admin".DS;
        //模板主题
        $Theme = empty(parent::$Cache["Config"]['theme']) ? 'default' : parent::$Cache["Config"]['theme'];
        
        $this->themePath = $TemplatePath.$Theme .DS;

        //初始化父级对象
        parent::_initialize();
        if (false == RBAC::AccessDecision(MODULE_NAME)) {
            //检查是否登录
            if (false == RBAC::checkLogin()) {
                //跳转到登录界面
                $this->redirect(config("USER_AUTH_GATEWAY"));
            }
            //没有操作权限
            $this->error('您没有操作此项的权限！');
        }
        //验证登录
        $this->competence();
        
    }

    /**
     * 验证登录
     * @return boolean
     */
    private function competence() {
        //检查是否登录
        $uid = (int) User::getInstance()->isLogin();
        if (empty($uid)) {
            return false;
        }
        //获取当前登录用户信息
        $userInfo = User::getInstance()->getInfo();
        if (empty($userInfo)) {
            User::getInstance()->logout();
            return false;
        }
        //是否锁定
        if (!$userInfo['status']) {
            User::getInstance()->logout();
            $this->error('您的帐号已经被锁定！',  url('Publics/login'));
            return false;
        }
        return $userInfo;
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
        echo $this->view->fetch(parseTemplateFile($templateFile), $vars, $replace, $config);
    }

    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param string $message 错误信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $data 传输的数据
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void 
     */
    final public function error($message = '', $jumpUrl = '', $data = '', $ajax = true) {
        model('admin/Operationlog')->record($message, 0);
        if(IS_AJAX && true === $ajax){
            $retdata = ['status'=>false,'msg'=>$message,'jumpurl'=>$jumpUrl];
            if(!empty($data)){
                if(is_array($data)){
                    $retdata = array_merge($retdata,$data);
                }else{
                    $retdata['data'] = $data;
                }
            }
            $retdata['wait'] = isset($retdata['wait']) ? $retdata['wait'] : ( is_numeric($ajax) ? $ajax : 3 );
            echo json_encode($retdata);
            exit;
        }else{
            $errorTemplate = $this->themePath ."error.php";

            $result = [
                'code' => 1,
                'msg'  => $message,
                'data' => [],
                'url'  => $jumpUrl,
                'wait' => 4, //单位是秒
            ];
            echo $this->view->fetch(parseTemplateFile($errorTemplate), $result);
            exit;
        }
    }

    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param string $message 提示信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $data 传输的数据
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    final public function success($message = '', $jumpUrl = '', $data='', $ajax = true) {
        model('admin/Operationlog')->record($message, 1);

        if(IS_AJAX && true === $ajax){
            $retdata = ['status'=>true,'msg'=>$message,'jumpurl'=>$jumpUrl];
            if(!empty($data)){
                if(is_array($data)){
                    $retdata = array_merge($retdata,$data);
                }else{
                    $retdata['data'] = $data;
                }
            }
            $retdata['wait'] = isset($retdata['wait']) ? $retdata['wait'] : ( is_numeric($ajax) ? $ajax : 3 );
            echo json_encode($retdata);
            exit;
        }else{
            $successTemplate = $this->themePath ."success.php";
            if(file_exists($successTemplate)){
                config('dispatch_success_tmpl',$successTemplate);
            }
            $result = [
                'code' => 1,
                'msg'  => $message,
                'data' => [],
                'url'  => $jumpUrl,
                'wait' => 3, //秒
            ];
            echo $this->view->fetch(parseTemplateFile($successTemplate), $result);
            exit;
        }
    }

    /**
     * 分页输出
     * @param type $total 信息总数
     * @param type $size 每页数量
     * @param type $number 当前分页号（页码）
     * @param type $config 配置，会覆盖默认设置
     * @return type
     */
    protected function page($total, $size = 20, $number = 0, $config = array()) {
        $Page = parent::page($total, $size, $number, $config);
        $strl = '<div class="col-sm-4 col-xs-12">'
            . '<div class="dataTables_info" id="dt_basic_info" role="status" aria-live="polite">'
            . '共有{recordcount}条信息,分{pagecount}页显示'
            . '</div>'
            . '</div>';
        $Page->SetPager('default', $strl.' '
            . '<div class="col-sm-8 col-xs-12">'
            . '<div class="dataTables_paginate paging_simple_numbers" id="dt_basic_paginate">'
            . '<ul class="pagination pagination-sm">'
            . '{first}{prev}{liststart}{list}{listend}{next}{last}'
            . '</ul>'
            . '</div>'
            . '</div>',
            array( //SetPager第三个参数，并入的设置
                "currentclass" => "active",
                "pagetag" => "li",
                "pagetagclass" => "paginate_button",
            ));
        return $Page;
    }

}

<?php

// +----------------------------------------------------------------------
// | rootCMS Controller
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\common\controller;

use think\Controller;
use system\Components;

class CMS extends Controller {

    //缓存
    public static $Cache = array();
    //当前对象
    private static $_app;

    public function __get($name) {
        return Components::getInstance()->$name;
    }

    public function __construct() {
        parent::__construct();
        self::$_app = $this;
    }

    //初始化
    protected function _initialize() {
        $this->initSite();
        //默认跳转时间
        $this->assign("waitSecond", 3000);
    }

    /**
     * 获取CMS 对象
     * @return type
     */
    public static function app() {
        return self::$_app;
    }

    /**
     * 初始化站点配置信息
     * @return Arry 配置数组
     */
    protected function initSite() {
        
        //定义模型名称及路径
        $request = \think\Request::instance();
        define("MODULE_NAME",$request->module());
        define("MODULE_PATH", APP_PATH.MODULE_NAME.DS);
        define("CONTROLLER_NAME",$request->controller());
        define("ACTION_NAME",$request->action());
        define("IS_AJAX",$request->isAjax());
        define("IS_POST",$request->isPost());
        
        define('MODULE_EXTRESDIR', 'statics'.DS.'extres'. DS . strtolower(MODULE_NAME) . DS );
        
        $Config = sys_cache("Config");
        $Config['version'] = \think\Config::get("version");
        self::$Cache['Config'] = $Config;
        $config_siteurl = $Config['siteurl'];
        if (isModuleInstall('Domains')) {
            $parse_url = parse_url($config_siteurl);
            $config_siteurl = (is_ssl() ? 'https://' : 'http://') . "{$_SERVER['HTTP_HOST']}{$parse_url['path']}";
        }
        defined('CONFIG_SITEURL_MODEL') or define('CONFIG_SITEURL_MODEL', $config_siteurl);
        defined('CONFIG_SITEURL') or define('CONFIG_SITEURL', $config_siteurl);
        defined('CONFIG_UPLOADMAXSIZE') or define('CONFIG_UPLOADMAXSIZE', $Config['uploadmaxsize']);
        defined('CONFIG_QTUPLOADMAXSIZE') or define('CONFIG_QTUPLOADMAXSIZE', $Config['qtuploadmaxsize']);
        defined('CONFIG_UPLOADALLOWEXT') or define('CONFIG_UPLOADALLOWEXT', $Config['uploadallowext']);
        defined('CONFIG_QTUPLOADALLOWEXT') or define('CONFIG_QTUPLOADALLOWEXT', $Config['qtuploadallowext']);
        
        $adminurl = $config_siteurl."statics/admin/";
        $admintheme = isset($Config['admintheme']) && empty($Config['admintheme']) ? $Config['admintheme'] : 'default';
        
        $this->assign("config_siteurl", $config_siteurl);
        $this->assign("statics_admin",$adminurl);
        $this->assign("admin_theme",$admintheme."/");
        $this->assign("config", $Config);
    }

    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @param int $json_option 传递给json_encode的option参数
     * @return void
     */
    protected function ajaxReturn($data,$type='',$json_option=0) {
        $data['state'] = $data['status'] ? "success" : "fail";
        if(empty($type)) $type  =   config('DEFAULT_AJAX_RETURN');
        switch (strtoupper($type)){
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:text/html; charset=utf-8');
                exit(json_encode($data,$json_option));
            case 'XML'  :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($data));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler  =   isset($_GET[config('VAR_JSONP_HANDLER')]) ? $_GET[config('VAR_JSONP_HANDLER')] : config('DEFAULT_JSONP_HANDLER');
                exit($handler.'('.json_encode($data,$json_option).');');  
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);            
            default     :
                // 用于扩展其他返回格式数据
                tag('ajax_return', $data);
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
    protected function page($total, $size = 0, $number = 0, $config = array()) {
        return page($total, $size, $number, $config);
    }

    /**
     * 返回模型对象
     * @param type $model
     * @return type
     */
    protected function getModelObject($model) {
        if (is_string($model) && strpos($model, '/') == false) {
            $model = M(ucwords($model));
        } else if (strpos($model, '/') && is_string($model)) {
            $model =  model($model);
        } else if (is_object($model)) {
            return $model;
        } else {
            $model = M();
        }
        return $model;
    }

    /**
     * 基本信息分页列表方法
     * @param type $model 可以是模型对象，或者表名，自定义模型请传递完整（例如：Content/Model）
     * @param type $where 条件表达式
     * @param type $order 排序
     * @param type $limit 每次显示多少
     */
    protected function basePage($model, $where = '', $order = '', $limit = 20) {
        $model = $this->getModelObject($model);
        $count = $model->where($where)->count();
        $page = $this->page($count, $limit);
        $data = $model->where($where)->order($order)->limit($page->firstRow . ',' . $page->listRows)->select();
        $this->assign('Page', $page->show());
        $this->assign('data', $data);
        $this->assign('count', $count);
        $this->display();
    }

    /**
     * 基本信息添加
     * @param type $model 可以是模型对象，或者表名，自定义模型请传递完整（例如：Content/Model）
     * @param type $u 添加成功后的跳转地址
     * @param type $data 需要添加的数据
     */
    protected function baseAdd($model, $u = 'index', $data = '') {
        $model = $this->getModelObject($model);
        if (IS_POST) {
            if (empty($data)) {
                $data = input('post.', '', '');
            }
            if ($model->create($data) && $model->add()) {
                $this->success('添加成功！', $u ?  url($u) : '');
            } else {
                $error = $model->getError();
                $this->error($error? : '添加失败！');
            }
        } else {
            $this->display();
        }
    }

    /**
     * 基础修改信息方法
     * @param type $model 可以是模型对象，或者表名，自定义模型请传递完整（例如：Content/Model）
     * @param type $u 修改成功后的跳转地址
     * @param type $data 需要修改的数据
     */
    protected function baseEdit($model, $u = 'index', $data = '') {
        $model = $this->getModelObject($model);
        $fidePk = $model->getPk();
        $pk = input('request.' . $fidePk, '', '');
        if (empty($pk)) {
            $this->error('请指定需要修改的信息！');
        }
        $where = array($fidePk => $pk);
        if (IS_POST) {
            if (empty($data)) {
                $data = input('post.', '', '');
            }
            if ($model->create($data) && $model->where($where)->save() !== false) {
                $this->success('修改成功！', $u ?  url($u) : '');
            } else {
                $error = $model->getError();
                $this->error($error? : '修改失败！');
            }
        } else {
            $data = $model->where($where)->find();
            if (empty($data)) {
                $this->error('该信息不存在！');
            }
            $this->assign('data', $data);
            $this->display();
        }
    }

    /**
     * 基础信息单条记录删除，根据主键
     * @param type $model 可以是模型对象，或者表名，自定义模型请传递完整（例如：Content/Model）
     * @param type $u 删除成功后跳转地址
     */
    protected function baseDelete($model, $u = 'index') {
        $model = $this->getModelObject($model);
        $pk = input('request.' . $model->getPk());
        if (empty($pk)) {
            $this->error('请指定需要修改的信息！');
        }
        $where = array($model->getPk() => $pk);
        $data = $model->where($where)->find();
        if (empty($data)) {
            $this->error('该信息不存在！');
        }
        if ($model->delete() !== false) {
            $this->success('删除成功！', $u ?  url($u) : '');
        } else {
            $error = $model->getError();
            $this->error($error? : '删除失败！');
        }
    }

    /**
     * 验证码验证
     * @param type $verify 验证码
     * @param type $type 验证码类型
     * @return boolean
     */
    static public function verify($verify, $type = "verify") {
        $vobj = new \app\api\controller\Checkcode();
        return $vobj->verifyValidate($type, $verify);
    }

    //空操作
    public function _empty() {
        $this->error('该页面不存在！');
    }

}

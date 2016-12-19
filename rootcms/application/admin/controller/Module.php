<?php

// +----------------------------------------------------------------------
// | rootCMS 本地模块管理
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\AdminBase;
use System\Module;

class Module extends AdminBase {

    //模块所处目录路径
    protected $appPath = APP_PATH;
    //已安装模块列表
    protected $moduleList = array();
    //系统模块，隐藏
    protected $systemModuleList = array('Admin', 'Api', 'Install', 'Attachment', 'Template', 'Content');

    //初始化
    protected function _initialize() {
        parent::_initialize();
        $this->moduleList = M('Module')->select();
    }

    //本地模块列表
    public function index() {
        //取得模块目录名称
        $dirs = glob($this->appPath . '*');
        foreach ($dirs as $path) {
            if (is_dir($path)) {
                //目录名称
                $path = basename($path);
                //系统模块隐藏
                if (in_array($path, $this->systemModuleList)) {
                    continue;
                }
                $dirs_arr[] = $path;
            }
        }
        //取得已安装模块列表
        $moduleList = array();
        foreach ($this->moduleList as $v) {
            $moduleList[$v['module']] = $v;
            //检查是否系统模块，如果是，直接不显示
            if ($v['iscore']) {
                $key = array_keys($dirs_arr, $v['module']);
                unset($dirs_arr[$key[0]]);
            }
        }

        //数量
        $count = count($dirs_arr);
        //把一个数组分割为新的数组块
        $dirs_arr = array_chunk($dirs_arr, 10, true);
        //当前分页
        $page = max(input('get.' . config('VAR_PAGE'), 0, 'intval'), 1);
        //根据分页取到对应的模块列表数据
        $directory = $dirs_arr[intval($page - 1)];
        foreach ($directory as $module) {
            $moduleList[$module] = $this->Module->config($module);
        }
        //进行分页
        $Page = $this->page($count, 10);

        $this->assign("Page", $Page->show());
        $this->assign("data", $moduleList);
        $this->assign("modules", $this->moduleList);
        $this->display();
    }

    //安装
    public function install() {
        if (IS_POST) {
            $post = input('post.');
            $module = $post['module'];
            if (empty($module)) {
                $this->error('请选择需要安装的模块！');
            }
            if ($this->Module->install($module)) {
                $this->success('模块安装成功！',  url('admin/Module/index'));
            } else {
                $error = $this->Module->error;
                $this->error($error ? $error : '模块安装失败！');
            }
        } else {
            $module = input('get.module', '', 'trim,ucwords');
            if (empty($module)) {
                $this->error('请选择需要安装的模块！');
            }
            //检查是否已经安装过
            if ($this->Module->isInstall($module) !== false) {
                $this->error('该模块已经安装！');
            }
            $config = $this->Module->config($module);
            //版本检查
            if ($config['adaptation']) {
                $version = version_compare(SHUIPF_VERSION, $config['adaptation'], '>=');
                $this->assign('version', $version);
            }
            $this->assign('config', $config);
            $this->display();
        }
    }

    //模块卸载 
    public function uninstall() {
        $module = input('get.module', '', 'trim,ucwords');
        if (empty($module)) {
            $this->error('请选择需要安装的模块！');
        }
        if ($this->Module->uninstall($module)) {
            $this->success("模块卸载成功，请及时更新缓存！",  url("Module/index"));
        } else {
            $error = $this->Module->error;
            $this->error($error ? $error : "模块卸载失败！",  url("Module/index"));
        }
    }

    //模块状态转换
    public function disabled() {
        $module = input('get.module', '', 'trim,ucwords');
        if (empty($module)) {
            $this->error('请选择模块！');
        }
        if ( model('Common/Module')->disabled($module)) {
            $this->success("状态转换成功，请及时更新缓存！",  url("Module/index"));
        } else {
            $this->error("状态转换成功失败！",  url("Module/index"));
        }
    }

}

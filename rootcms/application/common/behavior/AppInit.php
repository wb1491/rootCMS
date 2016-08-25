<?php

// +----------------------------------------------------------------------
// | rootCMS
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\common\behavior;

defined('THINK_PATH') or exit();

class AppInit {

    //执行入口
    public function run(&$param) {
        // 注册AUTOLOAD方法
        spl_autoload_register('app\common\behavior\AppInit::autoload');
        //检查是否安装
        if ($this->richterInstall() == false) {
            redirect('./install.php');
            return false;
        }
        //站点初始化
        $this->initialization();
    }

    /**
     * 是否安装检测
     */
    private function richterInstall() {
        //日志目录
        if (!is_dir(LOG_PATH)) {
            mkdir(LOG_PATH);
        }
        $dbHost = config('database.hostname');
        if (empty($dbHost) && !defined('INSTALL')) {
            return false;
        }
        return true;
    }

    //初始化
    private function initialization() {
        if (!config('database.password')) {
            return true;
        }
        //产品版本号
        define("CMS_VERSION", config("version.version"));
        //产品流水号
        define("CMS_BUILD", config("version.build"));
        //产品名称
        define("CMS_APPNAME", config("version.appname"));
        
        //MODULE_ALLOW_LIST配置
        $moduleList = sys_cache('Module');
        $moduleAllowList = array('admin', 'api', 'attachment', 'content', 'install', 'template');
        if(!empty($moduleList)){
            foreach ($moduleList as $rs) {
                $rs = $rs->toArray();
                if ($rs['disabled']) {
                    $moduleAllowList[] = $rs['module'];
                }
            }
        }
        config('MODULE_ALLOW_LIST', $moduleAllowList);
    }

    /**
     * 类库自动加载
     * @param string $class 对象类名
     * @return void
     */
    static public function autoload($class) {
        //内容模型content_xx.class.php类自动加载
        if (in_array($class, array('content_form', 'content_input', 'content_output', 'content_update', 'content_delete'))) {
            \app\content\model\content::classGenerate();
            require_cache(RUNTIME_PATH . "{$class}.class.php");
            return;
        }
    }

}

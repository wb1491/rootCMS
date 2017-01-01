<?php

// +----------------------------------------------------------------------
// | rootCMS
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------
// 检测PHP环境
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    header("Content-type: text/html; charset=utf-8");
    die('PHP环境不支持，使用本系统需要 PHP > 5.4.0 版本才可以~ !');
}
//当前目录路径
define("SITE_PATH",     getcwd() . DIRECTORY_SEPARATOR);
//定义系统使用的ROOT_PATH
define("ROOT_PATH",     SITE_PATH);
//应用运行缓存目录
define("RUNTIME_PATH",  SITE_PATH . "#runtime". DIRECTORY_SEPARATOR);
//项目路径
define("PROJECT_PATH",  SITE_PATH . "rootcms". DIRECTORY_SEPARATOR);
// 定义应用目录
define("APP_PATH",      PROJECT_PATH . "application". DIRECTORY_SEPARATOR);
// 应用公共目录
define("COMMON_PATH",   PROJECT_PATH . "common". DIRECTORY_SEPARATOR);
//定义核心路径
define("THINK_PATH",    PROJECT_PATH . "core" . DIRECTORY_SEPARATOR );
//定义配置目录
define("CONF_PATH",     PROJECT_PATH . "config" . DIRECTORY_SEPARATOR );
//模板存放路径
define("TEMPLATE_PATH", ROOT_PATH . "template". DIRECTORY_SEPARATOR);
//引入ThinkPHP入口文件
require THINK_PATH . "start.php";
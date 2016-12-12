<?php

// +----------------------------------------------------------------------
// | rootCMS 插件相关函数
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

/**
 * 插件模板定位
 * @staticvar array $TemplateFileCache
 * @param type $templateFile
 * @param type $addonPath 插件目录
 * @return type
 */
function parseAddonTemplateFile($templateFile = '', $addonPath) {
    static $TemplateFileCache = array();
    config('TEMPLATE_NAME', $addonPath . 'View/');
    
    if(ucwords(ADDON_MODULE_NAME) == "Index"){
        /////////////////////////////////////////////////////////////////////////
        //对于前台模块，搜索模板路径
        $TemplatePath = TEMPLATE_PATH;
        //模板主题
        $Theme = empty(\Common\Controller\ShuipFCMS::$Cache["Config"]['theme']) ? 'Default' : \Common\Controller\ShuipFCMS::$Cache["Config"]['theme'];
        //如果有指定 GROUP_MODULE 则模块名直接是GROUP_MODULE，否则使用 MODULE_NAME，这样做的目的是防止其他模块需要生成
        //$group = defined('GROUP_MODULE') ? GROUP_MODULE : MODULE_NAME;
        //兼容 Add:ss 这种写法
        if (!empty($templateFile) && strpos($templateFile, ':') && false === strpos($templateFile, config('TMPL_TEMPLATE_SUFFIX'))) {
            if (strpos($templateFile, '://')) {
                $temp = explode('://', $templateFile);
                $fxg = str_replace(':', '/', $temp[1]);
                $templateFile = $temp[0] . $fxg;
            } else {
                $templateFile = str_replace(':', '/', $templateFile);
            }
        }
        if ($templateFile != '' && strpos($templateFile, '://')) {
            $exp = explode('://', $templateFile);
            $Theme = $exp[0];
            $templateFile = $exp[1];
        }
        // 分析模板文件规则
        $depr = config('TMPL_FILE_DEPR');
        //模板标识
        if ('' == $templateFile) {
            $templateFile = $TemplatePath . $Theme . '/' . CONTROLLER_NAME ."/" . ucwords(ADDON_MODULE_NAME) . '/' . ACTION_NAME . config('TMPL_TEMPLATE_SUFFIX');
        }
        if (!file_exists_case($templateFile)) {
            $templateFile = config('TEMPLATE_NAME') . ucwords(ADDON_MODULE_NAME) . '/' . ACTION_NAME . config('TMPL_TEMPLATE_SUFFIX');
        }
    }
        
    /////////////////////////////////////////////////////////////////////////
    //
    //模板标识
    if ('' == $templateFile ) {
        $templateFile = config('TEMPLATE_NAME') . ucwords(ADDON_MODULE_NAME) . '/' . ACTION_NAME . config('TMPL_TEMPLATE_SUFFIX');
    }
    
    $key = md5($templateFile);
    if (isset($TemplateFileCache[$key])) {
        return $TemplateFileCache[$key];
    }
    if (false === strpos($templateFile, config('TMPL_TEMPLATE_SUFFIX'))) {
        // 解析规则为 模板主题:模块:操作 不支持 跨项目和跨分组调用
        $path = explode(':', $templateFile);
        $action = array_pop($path);
        $module = !empty($path) ? array_pop($path) : ucwords(ADDON_MODULE_NAME);
        $path = config("TEMPLATE_NAME");
        $depr = defined('GROUP_NAME') ? config('TMPL_FILE_DEPR') : '/';
        $templateFile = $path . $module . $depr . $action . config('TMPL_TEMPLATE_SUFFIX');
    }
    //区分大小写的文件判断，如果不存在，尝试一次使用默认主题
    if (!file_exists_case($templateFile)) {
        //记录日志
        $log = '模板:[' . $templateFile . ']不存在！';
        throw_exception($log);
    }
    $TemplateFileCache[$key] = $templateFile;
    return $templateFile;
}

<?php

// +----------------------------------------------------------------------
// | cms 全局配置
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wb1491
// +----------------------------------------------------------------------

// 异常错误报错级别,
error_reporting(E_ERROR | E_PARSE );

/**
 * 项目公共配置文件
 * 该文件请不要修改，如果要覆盖惯例配置的值，可在应用配置文件中设定和惯例不符的配置项
 * 配置名称大小写任意，系统会统一转换成小写
 * 所有配置参数都可以在生效前动态改变
 */
$conf = [
    // 应用命名空间
    'app_namespace'          => 'app',
    // 应用调试模式
    'app_debug'              => true,
    // 应用模式状态
    'app_status'             => '',
    // 应用Trace 
    'app_trace'              => false, 
    // 是否支持多模块
    'app_multi_module'       => true,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 扩展配置文件
    'extra_config_list'      => ['route','database', 'version'],
    // 扩展函数文件
    'extra_file_list'        => [THINK_PATH . 'helper' . EXT],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'PRC',
    // 是否开启多语言
    'lang_switch_on'         => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => '',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => false,
    // 控制器类后缀
    'controller_suffix'      => false,
    
    
    // 默认模块名
    'default_module'         => 'content',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => '_empty',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => true,
    
    
    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    
    ////////////////////////////////////////////////
    // url 相关
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由
    'url_route_on'           => true,
    // 是否强制使用路由
    'url_route_must'         => true,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如.thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => false,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',
    //路由配置文件分开
    'route_config_file'      => ['content_route', 'admin_route', 'api_route'],
    
    ////////////////////////////////////////////////
    // 模板相关
    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'php',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '<',
        // 标签库标签结束标记
        'taglib_end'   => '>',
        //标签库导入
        'taglib_build_in' => 'Cx,app\common\taglib\Content',
        //不允许在模板中使用php
        'tpl_deny_php' => false,
    ],
    
    'trace' =>[
        'type'       =>  'Html',
        'trace_tabs' =>  [
             'base'=>'基本',
             'file'=>'文件',
             'info'=>'流程',
             'error'=>'错误',
             'sql'=>'SQL',
             'debug|log'=>'调试'
         ]
    ],

    // 视图输出字符串内容替换
    'view_replace_str'       => [],
    
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
        
    //自定参数
    /* 站点安全设置 */
    "authcode" => 'rzqn6fokzas4naph3a', //密钥

    /* cookie设置 */
    "cookie_prefix" => 'sve_', //cookie前缀

    /* 数据缓存设置 */
    'data_cache_prefix' => '6pe_', // 缓存前缀
    
];

$extend = array(); 
//检查是否存在扩展配置文件
if(file_exists(CONF_PATH."extend.php")){
    $extend = require_once CONF_PATH.'extend.php';
}
return empty($extend)? $conf : array_merge($conf,$extend);

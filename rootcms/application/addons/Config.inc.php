<?php

// +----------------------------------------------------------------------
// | rootCMS 插件模块配置
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------
return array(
    //模块名称
    'modulename' => '插件管理',
    //图标
    'icon' => '/statics/images/module/53c0ec84592b5.png',
    //模块简介
    'introduce' => '插件管理是Stumanager的高级扩展，支持插件的安装和创建~。',
    //模块介绍地址
    'address' => 'http://www.linuxxt.cn',
    //模块作者
    'author' => 'stumanager',
    //作者地址
    'authorsite' => 'http://www.linuxxt.cn',
    //作者邮箱
    'authoremail' => 'wb1491@gmail.com',
    //版本号，请不要带除数字外的其他字符
    'version' => '1.1.3',
    //适配最低ShuipFCMS版本，
    'adaptation' => '2.0.0',
    //签名
    'sign' => '912b7e22bd9d86dddb1d460ca90581eb',
    //依赖模块
    'depend' => array(),
    //缓存，格式：缓存key=>array('module','model','action')
    'cache' => array(
        'Addons' => array(
            'name' => '插件列表',
            'model' => 'Addons',
            'action' => 'addons_cache',
        ),
    ),
);

<?php

// +----------------------------------------------------------------------
// | rootCMS 模块配置
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wb23 <wb1491@gmail.com>
// +----------------------------------------------------------------------
return array(
    //模块名称
    'modulename' => '域名绑定',
    //图标
    'icon' => 'd/file/content/2013/11/527dc4e2dab30.png',
    //模块简介
    'introduce' => '提供对模块进行二级域名绑定！',
    //模块介绍地址
    'address' => 'http://www.linuxxt.cn',
    //模块作者
    'author' => 'stumanager',
    //作者地址
    'authorsite' => 'http://www.linuxxt.cn',
    //作者邮箱
    'authoremail' => 'wb1491@gmail.com',
    //版本号，请不要带除数字外的其他字符
    'version' => '1.0.0',
    //适配最低Stumanager版本，
    'adaptation' => '2.0.0',
    //签名
    'sign' => '01d1cc6e0b01e5b5a1bc114ea8f2b3e9',
    //依赖模块
    'depend' => array(),
    //行为标签
    'tags' => array(
        'app_init' => array(
            'type' => 1,
            'phpfile:Domains|module:Domains',
        ),
    ),
    //缓存，格式：缓存key=>array('module','model','action')
    'cache' => array(
        'Domains_list' => array(
            'name' => '域名绑定模块',
            'model' => 'Domains',
            'action' => 'domains_cache',
        ),
        'Module_Domains_list' => array(
            'name' => '模块绑定域名',
            'model' => 'Domains',
            'action' => 'domains_domainslist',
        ),
    ),
);

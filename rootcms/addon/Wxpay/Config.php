<?php

/**
 * 插件配置，下面是示例
 * Some rights reserved： linuxxt.cn
 * Contact email:wb1491@gmail.com
 */
return array(
    'APPID' => array(
        'title' => 'Appid(应用id)',
        'type' => 'text',
        'value' => '',
        'style' => "width:400px;",
    ),
    'APPSECRET' => array(
        'title' => 'AppSecret(应用密钥)',
        'type' => 'text',
        'value' => '',
        'style' => "width:400px;",
    ),
    'MCHID' => array(
        'title'=> 'Mchid(商户号)',
        'type'=>'text',
        "value"=>"",
        "style"=>"width:400px;"        
    ),
    'KEY' => array(
        'title'=> 'Key(API密钥)',
        'type'=>'text',
        "value"=>"",
        "style"=>"width:400px;",
        "tips" => "",
    ),
    'DOMAIN' => array(
        'title' => '认证域名',
        'type'=>'text',
        "value"=>"",
        "style"=>"width:400px;",
        "tips" => "前面要加http://最后要加/,此域名最好填写在微信公众平台上配置的“授权回调页面域名”",
    )
);


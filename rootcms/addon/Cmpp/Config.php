<?php

/**
 * 插件配置，下面是示例
 * Some rights reserved： linuxxt.cn
 * Contact email:wb1491@gmail.com
 */
return array(
    'account' => array(
        'title' => '创蓝账户:',
        'type' => 'text',
        'value' => '',
        'style' => "width:200px;",
    ),
    'password' => array(
        'title' => '密码:',
        'type' => 'text',
        'value' => '',
        'style' => "width:200px;",
    ),
    'sendurl' => array(
        'title'=> '短信发送接口URL：',
        'type'=>'text',
        "value"=>"http://222.73.117.156/msg/HttpBatchSendSM",
        "style"=>"width:400px;"        
    ),
    'queryurl' => array(
        'title'=> '余额查询接口URL：',
        'type'=>'text',
        "value"=>"http://222.73.117.156/msg/QueryBalance",
        "style"=>"width:400px;"
    )
);


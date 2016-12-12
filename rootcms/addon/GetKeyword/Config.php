<?php

/**
 * 插件配置，下面是示例
 * Some rights reserved：abc3210.com
 * Contact email:admin@abc3210.com
 */
return array(
    'kaiguan' => array(
        'title' => '是否开启本插件:',
        'type' => 'radio',
        'options' => array(
            '1' => '开启',
            '0' => '关闭'
        ),
        'value' => '1'
    ),
    'jiekou' => array(
        'title' => '分词接口:',
        'type' => 'select',
        'options' => array(
            '1' => '使用内置简单分词接口',
            '2' => '使用Discuz在线分词接口',
            '3' => '使用矩网智慧在线分词接口',
        ),
        'tips' => '（提示：Discuz、矩网智慧在线分词接口速度可能受网络影响！矩网智慧接口比较精确）',
        'value' => '3'
    ),
);

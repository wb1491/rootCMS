<?php

return [
        'app_init' => [
            'app\common\behavior\AppInit', // 生成运行Lite文件
        ],
        'app_begin' => [
            'app\common\behavior\AppBegin', // 读取静态缓存
        ],
        'app_end' => [
            //'app\common\behavior\ShowPageTrace', // 页面Trace显示
        ],
        'view_filter' => [
            'app\common\behavior\WriteHtmlCache', // 写入静态缓存
        ]
];


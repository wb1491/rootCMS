<?php

// +----------------------------------------------------------------------
// | rootCMS 访问路由配置
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

return [
    
    //后台相关
    "admin$"  => ["admin/Index/index",'','ext'=>'html'],
    "admin/login"  => ['admin/Publics/login','','ext'=>'html'],
    "admin/logout" => ['admin/Publics/logout','','ext'=>'html'],
    "admin/tologin"=> ['admin/Publics/tologin','','ext'=>'html'],
    "admin/main"   => ['admin/Main/index','','ext'=>"html"],
    "admin/menu"   => ['admin/Index/public_menu','','ext'=>'json'],
    "admin/cache"  => ['admin/Index/cache','','ext'=>"html"],
    "admin/upcache/[:type]"  => ['admin/Index/upcache',[],["type"=>"\w+"]],
    "admin/config/[:menuid]" => ['admin/Config/index',[],["menuid"=>"\d+"]],
    "admin/mailcfg/[:menuid]" => ['admin/Config/mail',[],['menuid'=>"\d+"]],
    "admin/cfgsave$"         => ['admin/Config/cfgsave',[],[]],
    "admin/attachcfg/[:menuid]" => ['admin/Config/attach',[],['menuid'=>"\d+"]],
    "admin/additioncfg/[:menuid]"  => ['admin/Config/addition',[],['menuid'=>"\d+"]],
    "admin/extendcfg/[:menuid]"  => ['admin/Config/extend',[],['menuid'=>"\d+"]],
    "admin/additionsave$"  => ['admin/Config/additionsave',[],[]],
    "admin/extendsave$"  => ['admin/Config/extendsave',[],[]],
    "admin/extenddel/:fid/:action"  => ['admin/Config/extenddel',[],["fid"=>"\d+","action"=>'\w+',]],
    "admin/behavior/[:menuid]/[:page]"  => ['admin/Behavior/index',[],['menuid'=>"\d+",'page'=>"\d+"]],
    "admin/behaviorlogs"  => ['admin/Behavior/logs',[],[]],
    "admin/behavioradd"  => ['admin/Behavior/add',[],[]],
    "admin/behavioredit/:id"  => ['admin/Behavior/edit',[],['id'=>"\d+"]],
    "admin/behaviorstatus/:id"  => ['admin/Behavior/status',[],['id'=>"\d+"]],
    
    "admin/menugl/[:menuid]"  => ['admin/Menu/index',[],['menuid'=>"\d+"]],
    "admin/menuadd/[:menuid]" => ['admin/Menu/add',[],['menuid'=>"\d+"]],
    
    "admin/myinfo$" => ["admin/Adminmanage/myinfo",[],[]],
    "admin/changepwd$" => ["admin/Adminmanage/changepwd",[],[]],
    "admin/changeavr$" => ["admin/Adminmanage/changeavr",[],[]],

    //api相关
    "api/checkcode/:type/:code_len/:font_size/:width/:height" => [
        "api/Checkcode/index", [],
        [ "type"=>"\w+", "code_len"=>"\d+", "font_size"=>"\d+", "width"=>"\d+", "height"=>"\d+",]
    ],
    
];
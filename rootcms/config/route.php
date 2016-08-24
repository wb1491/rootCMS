<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return [
    //首页
    "/" => ["content/Index/index","","ext"=>"html"],
    //栏目页及分类显示页
    "cate/:catid/[:page]" => ["content/Index/lists",[],["catid"=>"\d+","page"=>"\d+"]],
    //内容显示页
    "show/:catid/:id/[:page]" => ["content/Index/shows",[],["catid"=>"\d+","id"=>"\d+","page"=>"\d+"]],
    //标签显示页
    "tags/:tagid/:tag/[:page]" => ["content/Index/tags",[],["tagid"=>"\d+","tag"=>"\w+","page"=>"\d+"]],
    
    //前台会员相关
    "login"  => ['member/Index/login','','ext'=>"html"],
    "logout" => ['member/Index/logout','','ext'=>"html"],
    "register" => ["member/Index/register",'',"ext"=>"html"],
    "profile"  => ["member/User/profile","","ext"=>"html"],
    "lostpwd"  => ["member/Index/lostpassword",'',"ext"=>"html"],
    
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
    "admin/mailsave$"         => ['admin/Config/mailsave',[],[]],
    "admin/mailcfg/[:menuid]" => ['admin/Config/mail',[],['menuid'=>"\d+"]],
    "admin/menugl/[:menuid]"  => ['admin/Menu/index',[],['menuid'=>"\d+"]],
    "admin/menuadd/[:menuid]" => ['admin/Menu/add',[],['menuid'=>"\d+"]],
        
    //api相关
    "api/checkcode/:type/:code_len/:font_size/:width/:height" => [
        "api/Checkcode/index", [],
        [ "type"=>"\w+", "code_len"=>"\d+", "font_size"=>"\d+", "width"=>"\d+", "height"=>"\d+",]
    ],
    
];
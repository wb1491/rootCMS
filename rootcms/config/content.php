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
    //增加前台项目测试路由
    "test"  => ["content/Test/test",[],[]],
    
    //前台会员相关
    "login"  => ['member/Index/login','','ext'=>"html"],
    "logout" => ['member/Index/logout','','ext'=>"html"],
    "register" => ["member/Index/register",'',"ext"=>"html"],
    "profile"  => ["member/User/profile","","ext"=>"html"],
    "lostpwd"  => ["member/Index/lostpassword",'',"ext"=>"html"],
];
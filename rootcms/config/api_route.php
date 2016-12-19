<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return [
    //api相关
    "api/checkcode/:type/:code_len/:font_size/:width/:height" => [
        "api/Checkcode/index", [],
        [ "type"=>"\w+", "code_len"=>"\d+", "font_size"=>"\d+", "width"=>"\d+", "height"=>"\d+",]
    ],
];
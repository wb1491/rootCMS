<?php

// +----------------------------------------------------------------------
// | rootCMS 验证码处理
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\api\controller;

use app\common\controller\CMS;

class Checkcode extends CMS {

    public function index() {
        //关闭页面trace
        \think\Config::set("app_trace",false);
        $checkcode = new \util\Checkcode();
        //验证码类型
        $checkcode->type = input('type', 'verify', 'strtolower');
        //设置长度
        $codelen = input('code_len', 0, 'intval');
        if ($codelen) {
            if ($codelen > 8 || $codelen < 2) {
                $codelen = 4;
            }
            $checkcode->codelen = $codelen;
        }
        //设置验证码字体大小
        $fontsize = input('font_size', 0, 'intval');
        if ($fontsize) {
            $checkcode->fontsize = $fontsize;
        }
        //设置验证码图片宽度
        $width = input('width', 0, 'intval');
        if ($width) {
            $checkcode->width = $width;
        }
        //设置验证码图片高度
        $height = input('height', 0, 'intval');
        if ($height) {
            $checkcode->height = $height;
        }
        //设置背景颜色
        $background = input('background', '', '');
        if ($background) {
            $checkcode->background = $background;
        }
        //设置字体颜色
        $fontcolor = input('font_color', '', '');
        if ($fontcolor) {
            $checkcode->fontcolor = $fontcolor;
        }

        //显示验证码
        return $checkcode->output(input('refresh', false, ''));
        //return true;
    }

    /**
     * 验证输入，看它是否生成的代码相匹配。
     * @param type $type
     * @param type $input
     * @return type 
     */
    public function verifyValidate($type, $input) {
        $checkcode = new \util\Checkcode();
        $checkcode->type = $type;
        return $checkcode->validate($input, false);
    }

}

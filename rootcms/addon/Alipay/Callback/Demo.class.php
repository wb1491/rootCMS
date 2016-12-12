<?php

// +----------------------------------------------------------------------
// | rootCMS 测试回调
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wb23 <wb1491@gmail.com>
// +----------------------------------------------------------------------

namespace Addon\Alipay\Callback;

use Addon\Alipay\Callback\Callback;

class Demo extends Callback {

    public function run() {
        //调试模式下，会在根目录下生成一个data.txt文件，表示回调成功
        array2file($this->data, SITE_PATH . 'data.txt');
    }

}

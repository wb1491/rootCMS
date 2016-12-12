<?php

// +----------------------------------------------------------------------
// | rootCMS 模块绑定域名
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wb23 <wb1491@gmail.com>
// +----------------------------------------------------------------------

namespace Domains\Behavior;

class Domains {

    public function app_init($param) {
        $Domains_list = cache('Domains_list');
        $domain = $_SERVER['HTTP_HOST'];
        if ($Domains_list[$domain]) {
            config('DEFAULT_MODULE', $Domains_list[$domain]);
        }
    }

}

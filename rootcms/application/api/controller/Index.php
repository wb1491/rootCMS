<?php

// +----------------------------------------------------------------------
// | rootCMS
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace Api\Controller;

use Common\Controller\ShuipFCMS;

class IndexController extends ShuipFCMS {

    public function token() {
        $token = \Util\Encrypt::authcode($_POST['token'], 'DECODE', config('CLOUD_USERNAME'));
        if (!empty($token)) {
            cache($this->Cloud->getTokenKey(), $token, 3600);
            $this->success('验证通过');
            exit;
        }
        $this->error('验证失败');
    }

}

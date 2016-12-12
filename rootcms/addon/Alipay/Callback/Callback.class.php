<?php

// +----------------------------------------------------------------------
// | rootCMS 支付成功后回调处理
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wb23 <wb1491@gmail.com>
// +----------------------------------------------------------------------

namespace Addon\Alipay\Callback;

class Callback {

    //订单数据
    protected $data = '';

    /**
     * 链接回调服务
     * @staticvar null $handier
     * @return \Callback
     */
    static public function getInstance($callback = '') {
        static $handier = NULL;
        if (empty($handier)) {
            if (empty($callback)) {
                $handier = new Callback();
                return $handier;
            }
            $class = "\\Addon\\Alipay\\Callback\\{$callback}";
            if (class_exists($class)) {
                $handier = new $class();
            }
        }
        return $handier;
    }

    public function __get($name) {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
    }

    /**
     * 设置数据
     * @param type $data
     * @return \Callback
     */
    public function data($data) {
        $this->data = $data;
        return $this;
    }

    /**
     * 执行回调
     * @return boolean
     */
    public function run() {
        return true;
    }

}

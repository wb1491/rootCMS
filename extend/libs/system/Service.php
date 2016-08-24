<?php

// +----------------------------------------------------------------------
// | rootCMS 服务
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace libs\system;

class Service {

    /**
     * 取得Service 服务
     * @static
     * @access public
     * @return mixed
     */
    static function getInstance($type = '', $options = array()) {
        static $_instance = array();
        $guid = $type . to_guid_string($options);
        if (!isset($_instance[$guid])) {
            $class = strpos($type, '\\') ? $type : 'libs\\service\\' . ucwords(strtolower($type));
            if (class_exists($class)) {
                $connect = new $class($options);
                $_instance[$guid] = $connect->connect($type, $options);
            } else {
                exception('Service 服务类不存在！');
            }
        }
        return $_instance[$guid];
    }

}

<?php

// +----------------------------------------------------------------------
// | rootCMS Components
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------
namespace system;

class Components {

    static private $_components = array(
        'Url' => array(
            'class' => '\\system\\Url',
            'path' => 'libs.system.Url',
        ),
        'Cloud' => array(
            'class' => '\\system\\Cloud',
            'path' => 'libs.system.Cloud',
        ),
        'CloudDownload' => array(
            'class' => '\\system\\CloudDownload',
            'path' => 'libs.system.CloudDownload',
        ),
        'Html' => array(
            'class' => '\\system\\Html',
            'path' => 'libs.system.Html',
        ),
        'UploadFile' => array(
            'class' => '\\UploadFile',
        ),
        'Dir' => array(
            'class' => '\\Dir',
            'path' => 'Libs.Util.Dir',
        ),
        'Content' => array(
            'class' => '\\system\\Content',
            'path' => 'libs.system.Content',
        ),
        'ContentOutput' => array(
            'class' => '\\Content_output',
        ),
    );

    public function __construct($_components = array()) {
        if (!empty($_components)) {
            $this->setComponents($_components);
        } else {
            $this->setComponents([]);
        }
    }

    public function __get($name) {
        if (isset(self::$_components[$name])) {
            $components = self::$_components[$name];
            if (!empty($components['class'])) {
                $class = $components['class'];
                if ($components['path'] && !class_exists($class, false)) {
                    import($components['path'], PROJECT_PATH);
                }
                unset($components['class'], $components['path']);
                $this->$name = new $class;
                return $this->$name;
            }
        }
    }

    /**
     * 连接
     * @access public
     * @param array $_components  配置数组
     * @return void
     */
    static public function getInstance($_components = array()) {
        static $systemHandier;
        if (empty($systemHandier)) {
            $systemHandier = new Components($_components);
        }
        return $systemHandier;
    }

    /**
     * 设置$_components
     * @param type $_components
     */
    public function setComponents($_components = array()) {
        self::$_components = array_merge(self::$_components, $_components);
    }

}

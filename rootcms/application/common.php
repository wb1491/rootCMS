<?php
// +----------------------------------------------------------------------
// | rootCMS
// +----------------------------------------------------------------------
// | Copyright (c) 2012-2014 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

/**
 * 基于命名空间方式导入函数库
 * load('@.Util.Array')
 * @param string $name 函数库命名空间字符串
 * @param string $baseUrl 起始路径
 * @param string $ext 导入的文件扩展名
 * @return void
 */
function load($name, $baseUrl = '', $ext = '.php') {
    $name = str_replace(array('.', '#'), array('/', '.'), $name);
    if (empty($baseUrl)) {
        if (0 === strpos($name, '@/')) {//加载当前模块函数库
            $baseUrl = MODULE_PATH . 'common/';
            $name = substr($name, 2);
        } else { //加载其他模块函数库
            $array = explode('/', $name);
            $baseUrl = APP_PATH . array_shift($array) . '/common/';
            $name = implode('/', $array);
        }
    }
    if (substr($baseUrl, -1) != '/')
        $baseUrl .= '/';
    require_cache($baseUrl . $name . $ext);
}

/**
 * 优化的require_once
 * @param string $filename 文件地址
 * @return boolean
 */
function require_cache($filename) {
    static $_importFiles = array();
    if (!isset($_importFiles[$filename])) {
        if (file_exists_case($filename)) {
            require $filename;
            $_importFiles[$filename] = true;
        } else {
            $_importFiles[$filename] = false;
        }
    }
    return $_importFiles[$filename];
}

/**
 * 处理标签扩展
 * @param string $tag 标签名称
 * @param mixed $params 传入参数
 * @return void
 */
function tag($tag, &$params = NULL) {
    \think\Hook::listen($tag, $params);
}
/**
 * XML编码
 * @param mixed $data 数据
 * @param string $root 根节点名
 * @param string $item 数字索引的子节点名
 * @param string $attr 根节点属性
 * @param string $id 数字索引子节点key转换的属性名
 * @param string $encoding 数据编码
 * @return string
 */
function xml_encode($data, $root = 'think', $item = 'item', $attr = '', $id = 'id', $encoding = 'utf-8') {
    if (is_array($attr)) {
        $_attr = array();
        foreach ($attr as $key => $value) {
            $_attr[] = "{$key}=\"{$value}\"";
        }
        $attr = implode(' ', $_attr);
    }
    $attr = trim($attr);
    $attr = empty($attr) ? '' : " {$attr}";
    $xml = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
    $xml .= "<{$root}{$attr}>";
    $xml .= data_to_xml($data, $item, $id);
    $xml .= "</{$root}>";
    return $xml;
}

/**
 * 数据XML编码
 * @param mixed $data 数据
 * @param string $item 数字索引时的节点名称
 * @param string $id 数字索引key转换为的属性名
 * @return string
 */
function data_to_xml($data, $item = 'item', $id = 'id') {
    $xml = $attr = '';
    foreach ($data as $key => $val) {
        if (is_numeric($key)) {
            $id && $attr = " {$id}=\"{$key}\"";
            $key = $item;
        }
        $xml .= "<{$key}{$attr}>";
        $xml .= (is_array($val) || is_object($val)) ? data_to_xml($val, $item, $id) : $val;
        $xml .= "</{$key}>";
    }
    return $xml;
}

/**
 * 加载动态扩展文件
 * @var string $path 文件路径
 * @return void
 */
function load_ext_file($path) {
    // 加载自定义外部文件
    if ($files = C('LOAD_EXT_FILE')) {
        $files = explode(',', $files);
        foreach ($files as $file) {
            $file = $path . 'Common/' . $file . '.php';
            if (is_file($file))
                include $file;
        }
    }
    // 加载自定义的动态配置文件
    if ($configs = C('LOAD_EXT_CONFIG')) {
        if (is_string($configs))
            $configs = explode(',', $configs);
        foreach ($configs as $key => $config) {
            $file = is_file($config) ? $config : $path . 'Conf/' . $config . CONF_EXT;
            if (is_file($file)) {
                is_numeric($key) ? C(load_config($file)) : C($key, load_config($file));
            }
        }
    }
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0, $adv = false) {
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL)
        return $ip[$type];
    if ($adv) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos)
                unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * 发送HTTP状态
 * @param integer $code 状态码
 * @return void
 */
function send_http_status($code) {
    static $_status = array(
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily ', // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',
        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    );
    if (isset($_status[$code])) {
        header('HTTP/1.1 ' . $code . ' ' . $_status[$code]);
        // 确保FastCGI模式下正常
        header('Status:' . $code . ' ' . $_status[$code]);
    }
}

function think_filter(&$value) {
    // TODO 其他安全过滤
    // 过滤查询特殊字符
    if (preg_match('/^(EXP|NEQ|GT|EGT|LT|ELT|OR|XOR|LIKE|NOTLIKE|NOT BETWEEN|NOTBETWEEN|BETWEEN|NOTIN|NOT IN|IN)$/i', $value)) {
        $value .= ' ';
    }
}

// 不区分大小写的in_array实现
function in_array_case($value, $array) {
    return in_array(strtolower($value), array_map('strtolower', $array));
}
/**
 * 系统缓存缓存管理
 * @param mixed $name 缓存名称
 * @param mixed $value 缓存值
 * @param mixed $options 缓存参数
 * @return mixed
 */
function sys_cache($name, $value = '', $options = null) {
    static $cache = '';
    if (empty($cache)) {
        $cache = \libs\system\Cache::getInstance();
    }
    // 获取缓存
    if ('' === $value) {
        if (false !== strpos($name, '.')) {
            $vars = explode('.', $name);
            $data = $cache->get($vars[0]);
            return is_array($data) ? $data[$vars[1]] : $data;
        } else {
            return $cache->get($name);
        }
    } elseif (is_null($value)) {//删除缓存
        return $cache->remove($name);
    } else {//缓存数据
        if (is_array($options)) {
            $expire = isset($options['expire']) ? $options['expire'] : NULL;
        } else {
            $expire = is_numeric($options) ? $options : NULL;
        }
        return $cache->set($name, $value, $expire);
    }
}
/**
 * 区分大小写的文件存在判断
 * @param string $filename 文件地址
 * @return boolean
 */
function file_exists_case($filename) {
    if (is_file($filename)) {
        if (IS_WIN && APP_DEBUG) {
            if (basename(realpath($filename)) != basename($filename)){
                return false;
            }
        }
        return true;
    }
    return false;
}
/**
 * 根据PHP各种类型变量生成唯一标识号
 * @param mixed $mix 变量
 * @return string
 */
function to_guid_string($mix) {
    if (is_object($mix)) {
        return spl_object_hash($mix);
    } elseif (is_resource($mix)) {
        $mix = get_resource_type($mix) . strval($mix);
    } else {
        $mix = serialize($mix);
    }
    return md5($mix);
}

/**
 * 调试，用于保存数组到txt文件 正式生产删除
 * 用法：array2file($info, SITE_PATH.'post.txt');
 * @param type $array
 * @param type $filename
 */
function array2file($array, $filename) {
    if (defined("APP_DEBUG") && APP_DEBUG) {
        //修改文件时间
        file_exists($filename) or touch($filename);
        if (is_array($array)) {
            $str = var_export($array, TRUE);
        } else {
            $str = $array;
        }
        return file_put_contents($filename, $str);
    }
    return false;
}
/**
 * 返回ShuipFCMS对象
 * @return Object
 */
function CMS() {
    return app\common\controller\CMS::app();
}
/**
 * 快捷方法取得服务
 * @param type $name 服务类型
 * @param type $params 参数
 * @return type
 */
function service($name, $params = array()) {
    return libs\system\Service::getInstance($name, $params);
}
/**
 * 生成上传附件验证
 * @param $args   参数
 */
function upload_key($args) {
    return md5($args . md5(C("AUTHCODE") . $_SERVER['HTTP_USER_AGENT']));
}
/**
 * 检查模块是否已经安装
 * @param type $moduleName 模块名称
 * @return boolean
 */
function isModuleInstall($moduleName) {
    $appCache = sys_cache('Module');
    if (isset($appCache[$moduleName])) {
        return true;
    }
    return false;
}
/**
 * 产生一个指定长度的随机字符串,并返回给用户 
 * @param type $len 产生字符串的长度
 * @return string 随机字符串
 */
function genRandomString($len = 6) {
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    // 将数组打乱 
    shuffle($chars);
    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}
/**
 * 生成SEO
 * @param $catid        栏目ID
 * @param $title        标题
 * @param $description  描述
 * @param $keyword      关键词
 */
function seo($catid = '', $title = '', $description = '', $keyword = '') {
    if (!empty($title))
        $title = strip_tags($title);
    if (!empty($description))
        $description = strip_tags($description);
    if (!empty($keyword))
        $keyword = str_replace(' ', ',', strip_tags($keyword));
    $site = sys_cache("Config");
    $cat = getCategory($catid);
    $seo['site_title'] = $site['sitename'];
    $titleKeywords = "";
    $seo['keyword'] = $keyword != $cat['setting']['meta_keywords'] ? (isset($keyword) && !empty($keyword) ? $keyword . (isset($cat['setting']['meta_keywords']) && !empty($cat['setting']['meta_keywords']) ? "," . $cat['setting']['meta_keywords'] : "") : $titleKeywords . (isset($cat['setting']['meta_keywords']) && !empty($cat['setting']['meta_keywords']) ? "," . $cat['setting']['meta_keywords'] : "")) : (isset($keyword) && !empty($keyword) ? $keyword : $cat['catname']);
    $seo['description'] = isset($description) && !empty($description) ? $description : $title . (isset($keyword) && !empty($keyword) ? $keyword : "");
    $seo['title'] = $cat['setting']['meta_title'] != $title ? ((isset($title) && !empty($title) ? $title . ' - ' : '') . (isset($cat['setting']['meta_title']) && !empty($cat['setting']['meta_title']) ? $cat['setting']['meta_title'] . ' - ' : (isset($cat['catname']) && !empty($cat['catname']) ? $cat['catname'] . ' - ' : ''))) : (isset($title) && !empty($title) ? $title . " - " : ($cat['catname'] ? $cat['catname'] . " - " : ""));
    foreach ($seo as $k => $v) {
        $seo[$k] = str_replace(array("\n", "\r"), '', $v);
    }
    return $seo;
}
/**
 *  通过用户邮箱，取得gravatar头像
 * @since 2.5
 * @param int|string|object $id_or_email 一个用户ID，电子邮件地址
 * @param int $size 头像图片的大小
 * @param string $default 如果没有可用的头像是使用默认图像的URL
 * @param string $alt 替代文字使用中的形象标记。默认为空白
 * @return string <img>
 */
function get_avatar($id_or_email, $size = '96', $default = '', $alt = false) {
    //头像大小
    if (!is_numeric($size))
        $size = '96';
    //邮箱地址
    $email = '';
    //如果是数字，表示使用会员头像 暂时没有写！
    if (is_int($id_or_email)) {
        $id = (int) $id_or_email;
        $userdata = service("Passport")->getLocalUser($id);
        $email = $userdata['email'];
    } else {
        $email = $id_or_email;
    }
    //设置默认头像
    if (empty($default)) {
        $default = 'mystery';
    }
    if (!empty($email))
        $email_hash = md5(strtolower($email));
    $host = 'http://gravatar.duoshuo.com';
    if ('mystery' == $default)
        $default = "$host/avatar/ad516503a11cd5ca435acc9bb6523536?s={$size}"; // ad516503a11cd5ca435acc9bb6523536 == md5('unknown@gravatar.com')
    elseif (!empty($email) && 'gravatar_default' == $default)
        $default = '';
    elseif ('gravatar_default' == $default)
        $default = "$host/avatar/s={$size}";
    elseif (empty($email))
        $default = "$host/avatar/?d=$default&amp;s={$size}";
    if (!empty($email)) {
        $out = "$host/avatar/";
        $out .= $email_hash;
        $out .= '?s=' . $size;
        $out .= '&amp;d=' . urlencode($default);
        $avatar = $out;
    } else {
        $avatar = $default;
    }
    return $avatar;
}/**
 * 分页处理
 * @param type $total 信息总数
 * @param type $size 每页数量
 * @param type $number 当前分页号（页码）
 * @param type $config 配置，会覆盖默认设置
 * @return \Page|array
 */
function page($total, $size = 0, $number = 0, $config = array()) {
    //配置
    $defaultConfig = array(
        //当前分页号
        'number' => $number,
        //接收分页号参数的标识符
        'param' => config("VAR_PAGE"),
        //分页规则
        'rule' => '',
        //是否启用自定义规则
        'isrule' => false,
        //分页模板
        'tpl' => '',
        //分页具体可控制配置参数默认配置
        'tplconfig' => array('listlong' => 6, 'listsidelong' => 2, "first" => "首页", "last" => "尾页", "prev" => "上一页", "next" => "下一页", "list" => "*", "disabledclass" => ""),
    );
    //分页具体可控制配置参数
    $cfg = array(
        //每次显示几个分页导航链接
        'listlong' => 6,
        //分页链接列表首尾导航页码数量，默认为2，html 参数中有”{liststart}”或”{listend}”时才有效
        'listsidelong' => 2,
        //分页链接列表
        'list' => '*',
        //当前页码的CSS样式名称，默认为”current”
        'currentclass' => 'current',
        //第一页链接的HTML代码，默认为 ”«”，即显示为 «
        'first' => '&laquo;',
        //上一页链接的HTML代码，默认为”‹”,即显示为 ‹
        'prev' => '&#8249;',
        //下一页链接的HTML代码，默认为”›”,即显示为 ›
        'next' => '&#8250;',
        //最后一页链接的HTML代码，默认为”»”,即显示为 »
        'last' => '&raquo;',
        //被省略的页码链接显示为，默认为”…”
        'more' => '...',
        //当处于首尾页时不可用链接的CSS样式名称，默认为”disabled”
        'disabledclass' => 'disabled',
        //页面跳转方式，默认为”input”文本框，可设置为”select”下拉菜单
        'jump' => '',
        //页面跳转文本框或下拉菜单的附加内部代码
        'jumpplus' => '',
        //跳转时要执行的javascript代码，用*代表页码，可用于Ajax分页
        'jumpaction' => '',
        //当跳转方式为下拉菜单时最多同时显示的页码数量，0为全部显示，默认为50
        'jumplong' => 50,
    );
    //覆盖配置
    if (!empty($config) && is_array($config)) {
        $defaultConfig = array_merge($defaultConfig, $config);
    }
    //每页显示信息数量
    $defaultConfig['size'] = $size ? $size : config("PAGE_LISTROWS");
    //把默认配置选项设置到tplconfig
    foreach ($cfg as $key => $value) {
        if (isset($defaultConfig[$key])) {
            $defaultConfig['tplconfig'][$key] = isset($defaultConfig[$key]) ? $defaultConfig[$key] : $value;
        }
    }
    //是否启用自定义规则，规则是一个数组，index和list。不启用的情况下，直接以当前$_GET的参数组成地址
    if ($defaultConfig['isrule'] && empty($defaultConfig['rule'])) {
        //通过全局参数获取分页规则
        $URLRULE = isset($GLOBALS['URLRULE']) ? $GLOBALS['URLRULE'] : (defined('URLRULE') ? URLRULE : '');
        $PageLink = array();
        if (!is_array($URLRULE)) {
            $URLRULE = explode('~', $URLRULE);
        }
        $PageLink['index'] = isset($URLRULE['index']) && $URLRULE['index'] ? $URLRULE['index'] : $URLRULE[0];
        $PageLink['list'] = isset($URLRULE['list']) && $URLRULE['list'] ? $URLRULE['list'] : $URLRULE[1];
        $defaultConfig['rule'] = $PageLink;
    } else if ($defaultConfig['isrule'] && !is_array($defaultConfig['rule'])) {
        $URLRULE = explode('|', $defaultConfig['rule']);
        $PageLink = array();
        $PageLink['index'] = $URLRULE[0];
        $PageLink['list'] = $URLRULE[1];
        $defaultConfig['rule'] = $PageLink;
    }
    $Page = new \Libs\Util\Page($total, $defaultConfig['size'], $defaultConfig['number'], $defaultConfig['list'], $defaultConfig['param'], $defaultConfig['rule'], $defaultConfig['isrule']);
    $Page->SetPager('default', $defaultConfig['tpl'], $defaultConfig['tplconfig']);
    return $Page;
}
/**
 * 获取栏目相关信息
 * @param type $catid 栏目id
 * @param type $field 返回的字段，默认返回全部，数组
 * @param type $newCache 是否强制刷新
 * @return boolean
 */
function getCategory($catid, $field = '', $newCache = false) {
    if (empty($catid)) {
        return false;
    }
    $key = 'getCategory_' . $catid;
    //强制刷新缓存
    if ($newCache) {
        cache($key, NULL);
    }
    $cache = cache($key);
    if ($cache === 'false') {
        return false;
    }
    if (empty($cache)) {
        //读取数据
        $cache = db('Category')->where(array('catid' => $catid))->find();
        if (empty($cache)) {
            cache($key, 'false', 60);
            return false;
        } else {
            //扩展配置
            $cache['setting'] = unserialize($cache['setting']);
            //栏目扩展字段
            $cache['extend'] = isset($cache['setting']['extend'])?$cache['setting']['extend']:'';
            cache($key, $cache, 3600);
        }
    }
    if ($field) {
        //支持var.property，不过只支持一维数组
        if (false !== strpos($field, '.')) {
            $vars = explode('.', $field);
            return $cache[$vars[0]][$vars[1]];
        } else {
            return $cache[$field];
        }
    } else {
        return $cache;
    }
}
/**
 * 获取位置相关信息
 * @param type $posid 位置id
 * @param type $field 返回的字段，默认返回全部，数组
 * @param type $newCache 是否强制刷新
 * @return boolean
 */
function getPosition($posid, $field = '', $newCache = false){
    if (empty($posid)) {
        return false;
    }
    $key = 'getPosition_' . $posid;
    //强制刷新缓存
    if ($newCache) {
        cache($key, NULL);
    }
    $cache = cache($key);
    if ($cache === 'false') {
        return false;
    }
    if (empty($cache)) {
        //读取数据
        $cache = db('position')->where(array('posid' => $posid))->find();
        if (empty($cache)) {
            cache($key, 'false', 60);
            return false;
        } else {
            //扩展配置
            $cache['setting'] = unserialize($cache['setting']);
            //栏目扩展字段
            $cache['extend'] = $cache['setting']['extend'];
            cache($key, $cache, 3600);
        }
    }
    if ($field) {
        //支持var.property，不过只支持一维数组
        if (false !== strpos($field, '.')) {
            $vars = explode('.', $field);
            return $cache[$vars[0]][$vars[1]];
        } else {
            return $cache[$field];
        }
    } else {
        return $cache;
    }
}
/**
 * 获取模型数据
 * @param type $modelid 模型ID
 * @param type $field 返回的字段，默认返回全部，数组
 * @return boolean
 */
function getModel($modelid, $field = '') {
    if (empty($modelid)) {
        return false;
    }
    $key = 'getModel_' . $modelid;
    $cache = cache($key);
    if ($cache === 'false') {
        return false;
    }
    if (empty($cache)) {
        //读取数据
        $cache = db('Model')->where(array('modelid' => $modelid))->find();
        if (empty($cache)) {
            cache($key, 'false', 60);
            return false;
        } else {
            cache($key, $cache, 3600);
        }
    }
    if ($field) {
        return $cache[$field];
    } else {
        return $cache;
    }
}/**
 * 检测一个数据长度是否超过最小值
 * @param type $value 数据
 * @param type $length 最小长度
 * @return type 
 */
function isMin($value, $length) {
    return mb_strlen($value, 'utf-8') >= (int) $length ? true : false;
}/**
 * 检测一个数据长度是否超过最大值
 * @param type $value 数据
 * @param type $length 最大长度
 * @return type 
 */
function isMax($value, $length) {
    return mb_strlen($value, 'utf-8') <= (int) $length ? true : false;
}/**
 * 取得文件扩展
 * @param type $filename 文件名
 * @return type 后缀
 */
function fileext($filename) {
    $pathinfo = pathinfo($filename);
    return $pathinfo['extension'];
}/**
 * 对 javascript escape 解码
 * @param type $str 
 * @return type
 */
function unescape($str) {
    $ret = '';
    $len = strlen($str);
    for ($i = 0; $i < $len; $i++) {
        if ($str[$i] == '%' && $str[$i + 1] == 'u') {
            $val = hexdec(substr($str, $i + 2, 4));
            if ($val < 0x7f)
                $ret .= chr($val);
            else
            if ($val < 0x800)
                $ret .= chr(0xc0 | ($val >> 6)) . chr(0x80 | ($val & 0x3f));
            else
                $ret .= chr(0xe0 | ($val >> 12)) . chr(0x80 | (($val >> 6) & 0x3f)) . chr(0x80 | ($val & 0x3f));
            $i += 5;
        } else
        if ($str[$i] == '%') {
            $ret .= urldecode(substr($str, $i, 3));
            $i += 2;
        } else
            $ret .= $str[$i];
    }
    return $ret;
}/**
 * 字符截取
 * @param $string 需要截取的字符串
 * @param $length 长度
 * @param $dot
 */
function str_cut($sourcestr, $length, $dot = '...') {
    $returnstr = '';
    $i = 0;
    $n = 0;
    $str_length = strlen($sourcestr); //字符串的字节数 
    while (($n < $length) && ($i <= $str_length)) {
        $temp_str = substr($sourcestr, $i, 1);
        $ascnum = Ord($temp_str); //得到字符串中第$i位字符的ascii码 
        if ($ascnum >= 224) {//如果ASCII位高与224，
            $returnstr = $returnstr . substr($sourcestr, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符         
            $i = $i + 3; //实际Byte计为3
            $n++; //字串长度计1
        } elseif ($ascnum >= 192) { //如果ASCII位高与192，
            $returnstr = $returnstr . substr($sourcestr, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符 
            $i = $i + 2; //实际Byte计为2
            $n++; //字串长度计1
        } elseif ($ascnum >= 65 && $ascnum <= 90) { //如果是大写字母，
            $returnstr = $returnstr . substr($sourcestr, $i, 1);
            $i = $i + 1; //实际的Byte数仍计1个
            $n++; //但考虑整体美观，大写字母计成一个高位字符
        } else {//其他情况下，包括小写字母和半角标点符号，
            $returnstr = $returnstr . substr($sourcestr, $i, 1);
            $i = $i + 1;            //实际的Byte数计1个
            $n = $n + 0.5;        //小写字母和半角标点等与半个高位字符宽...
        }
    }
    if ($str_length > strlen($returnstr)) {
        $returnstr = $returnstr . $dot; //超过长度时在尾处加上省略号
    }
    return $returnstr;
}
/**
 * 取得URL地址中域名部分
 * @param type $url 
 * @return \url 返回域名
 */
function urlDomain($url) {
    if ($url) {
        $pathinfo = parse_url($url);
        return $pathinfo['scheme'] . "://" . $pathinfo['host'] . "/";
    }
    return false;
}/**
 * 获取当前页面完整URL地址
 * @return type 地址
 */
function get_url() {
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
    return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
}/**
 * 返回附件类型图标
 * @param $file 附件名称
 * @param $type png为大图标，gif为小图标
 */
function file_icon($file, $type = 'png') {
    $ext_arr = array('doc', 'docx', 'ppt', 'xls', 'txt', 'pdf', 'mdb', 'jpg', 'gif', 'png', 'bmp', 'jpeg', 'rar', 'zip', 'swf', 'flv');
    $ext = fileext($file);
    if ($type == 'png') {
        if ($ext == 'zip' || $ext == 'rar')
            $ext = 'rar';
        elseif ($ext == 'doc' || $ext == 'docx')
            $ext = 'doc';
        elseif ($ext == 'xls' || $ext == 'xlsx')
            $ext = 'xls';
        elseif ($ext == 'ppt' || $ext == 'pptx')
            $ext = 'ppt';
        elseif ($ext == 'flv' || $ext == 'swf' || $ext == 'rm' || $ext == 'rmvb')
            $ext = 'flv';
        else
            $ext = 'do';
    }
    $config = cache('Config');
    if (in_array($ext, $ext_arr)) {
        return $config['siteurl'] . 'statics/images/ext/' . $ext . '.' . $type;
    } else {
        return $config['siteurl'] . 'statics/images/ext/blank.' . $type;
    }
}
/**
 * 根据文件扩展名来判断是否为图片类型
 * @param type $file 文件名
 * @return type 是图片类型返回 true，否则返回 false
 */
function isImage($file) {
    $ext_arr = array('jpg', 'gif', 'png', 'bmp', 'jpeg', 'tiff');
    //取得扩展名
    $ext = fileext($file);
    return in_array($ext, $ext_arr) ? true : false;
}
/**
 * 对URL中有中文的部分进行编码处理
 * @param type $url 地址 http://www.abc3210.com/s?wd=博客
 * @return type ur;编码后的地址 http://www.abc3210.com/s?wd=%E5%8D%9A%20%E5%AE%A2
 */
function cn_urlencode($url) {
    $pregstr = "/[\x{4e00}-\x{9fa5}]+/u"; //UTF-8中文正则
    if (preg_match_all($pregstr, $url, $matchArray)) {//匹配中文，返回数组
        foreach ($matchArray[0] as $key => $val) {
            $url = str_replace($val, urlencode($val), $url); //将转译替换中文
        }
        if (strpos($url, ' ')) {//若存在空格
            $url = str_replace(' ', '%20', $url);
        }
    }
    return $url;
}
/**
 * 获取模版文件 格式 主题://模块/控制器/方法
 * @param type $templateFile
 * @return boolean|string 
 */
function parseTemplateFile($templateFile = '') {
    static $TemplateFileCache = array();
    //模板路径
    $TemplatePath = TEMPLATE_PATH;
    
    if(defined("IN_ADMIN")){
        $TemplatePath .= "admin" . DS;
        //后台模板主题
        $Theme = empty(\app\common\controller\CMS::$Cache["Config"]['admintheme']) ? 'default' : \app\common\controller\CMS::$Cache["Config"]['adminitheme'];
    }else{
        $TemplatePath .= "index" . DS;
        //前台模板主题
        $Theme = empty(\app\common\controller\CMS::$Cache["Config"]['theme']) ? 'default' : \app\common\controller\CMS::$Cache["Config"]['theme'];
    }
    
    //如果有指定 GROUP_MODULE 则模块名直接是GROUP_MODULE，否则使用 MODULE_NAME，这样做的目的是防止其他模块需要生成
    $group = defined('GROUP_MODULE') ? GROUP_MODULE : MODULE_NAME;
    //兼容 Add:ss 这种写法
    if (!empty($templateFile) && strpos($templateFile, ':') && false === strpos($templateFile, config('template.view_suffix'))) {
        if (strpos($templateFile, '://')) {
            $temp = explode('://', $templateFile);
            $fxg = str_replace(':', '/', $temp[1]);
            $templateFile = $temp[0] . $fxg;
        } else {
            $templateFile = str_replace(':', '/', $templateFile);
        }
    }
    if ($templateFile != '' && strpos($templateFile, '://')) {
        $exp = explode('://', $templateFile);
        $Theme = $exp[0];
        $templateFile = $exp[1];
    }
    // 分析模板文件规则
    $depr = config('template.view_depr');
    //模板标识
    if ('' == $templateFile) {
        $templateFile = $TemplatePath . $Theme . DS . $group . DS . CONTROLLER_NAME . DS . ACTION_NAME . "." . config('template.view_suffix');
    }
    $key = md5($templateFile);
    if (isset($TemplateFileCache[$key])) {
        return $TemplateFileCache[$key];
    }
    if (false === strpos($templateFile, "/") && false === strpos($templateFile, config('template.view_suffix'))) {
        $templateFile = $TemplatePath . $Theme . DS . $group . DS . CONTROLLER_NAME . DS . $templateFile  . "." . config('template.view_suffix');
    } else if (false === strpos($templateFile, config('template.view_suffix'))) {
        $path = explode("/", $templateFile);
        $action = array_pop($path);
        $controller = !empty($path) ? array_pop($path) : CONTROLLER_NAME;
        if (!empty($path)) {
            $group = array_pop($path)? : $group;
        }
        $depr = defined('MODULE_NAME') ? config('template.view_depr') : DS;
        $templateFile = $TemplatePath . $Theme . DS . $group . DS . $controller . $depr . $action . "." . config('template.view_suffix');
    }
    
    //区分大小写的文件判断，如果不存在，尝试一次使用默认主题
    if (!file_exists($templateFile)) {
        $log = '模板:[' . $templateFile . '] 不存在！';
        \think\Log::record($log);
        //启用默认主题模板
        $templateFile = str_replace($TemplatePath . $Theme, $TemplatePath . 'default', $templateFile);
        //判断默认主题是否存在，不存在直接报错提示
        if (!file_exists($templateFile)) {
            if (defined('APP_DEBUG') && APP_DEBUG) {
                exception($log);
            }
            $TemplateFileCache[$key] = false;
            return false;
        }
    }
    $TemplateFileCache[$key] = $templateFile;
    return $TemplateFileCache[$key];
}
/**
 * 获取点击数
 * @param type $catid 栏目ID
 * @param type $id 信息ID
 * @return type
 */
function hits($catid, $id) {
    return \app\content\model\content::getInstance(getCategory($catid, 'modelid'))->where(array('id' => $id))->column('views');
}/**
 * 标题链接获取
 * @param type $catid 栏目id
 * @param type $id 信息ID
 * @return type 链接地址
 */
function titleurl($catid, $id) {
    return \app\content\model\content::getInstance(getCategory($catid, 'modelid'))->where(array('id' => $id))->column("url");
}/**
 * 获取文章评论总数
 * @param type $catid 栏目ID
 * @param type $id 信息ID
 * @return type 
 */
function commcount($catid, $id) {
    $comment_id = "c-{$catid}-{$id}";
    return db('Comments')->where(array("comment_id" => $comment_id, "parent" => 0, "approved" => 1))->count();
}/**
 * 生成标题样式
 * @param $style   样式，通常时字段style，以“;”隔开，第一个是文字颜色，第二个是否加粗
 * @param $html    是否显示完整的STYLE样式代码
 */
function title_style($style, $html = 1) {
    $str = '';
    if ($html) {
        $str = ' style="';
    }
    $style_arr = explode(';', $style);
    if (!empty($style_arr[0])) {
        $str .= 'color:' . $style_arr[0] . ';';
    }
    if (!empty($style_arr[1])) {
        $str .= 'font-weight:' . $style_arr[1] . ';';
    }
    if ($html) {
        $str .= '" ';
    }
    return $style ? $str : "";
}/**
 * 生成缩略图
 * @param type $imgurl 图片地址
 * @param type $width 缩略图宽度
 * @param type $height 缩略图高度
 * @param type $thumbType 缩略图生成方式 1 按设置大小截取 0 按原图等比例缩略
 * @param type $smallpic 图片不存在时显示默认图片
 * @return type
 */
function thumb($imgurl, $width = 100, $height = 100, $thumbType = 0, $smallpic = 'nopic.gif') {
    static $_thumb_cache = array();
    if (empty($imgurl)) {
        return $smallpic;
    }
    //区分
    $key = md5($imgurl . $width . $height . $thumbType . $smallpic);
    if (isset($_thumb_cache[$key])) {
        return $_thumb_cache[$key];
    }
    if (!$width) {
        return $smallpic;
    }
    //当获取不到DOCUMENT_ROOT值时的操作！
    if (empty($_SERVER['DOCUMENT_ROOT']) && !empty($_SERVER['SCRIPT_FILENAME'])) {
        $_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
    }
    if (empty($_SERVER['DOCUMENT_ROOT']) && !empty($_SERVER['PATH_TRANSLATED'])) {
        $_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
    }
    // 解析URLsitefileurl
    $imgParse = parse_url($imgurl);
    //图片路径
    $imgPath = $_SERVER['DOCUMENT_ROOT'] . $imgParse['path'];
    //取得文件名
    $basename = basename($imgurl);
    //取得文件存放目录
    $imgPathDir = str_replace($basename, '', $imgPath);
    //生成的缩略图文件名
    $newFileName = "thumb_{$width}_{$height}_" . $basename;
    //检查生成的缩略图是否已经生成过
    if (file_exists($imgPathDir . $newFileName)) {
        return str_replace($basename, $newFileName, $imgurl);
    }
    //检查文件是否存在，如果是开启远程附件的，估计就通过不了，以后在考虑完善！
    if (!file_exists($imgPath)) {
        return $imgurl;
    }
    //取得图片相关信息
    list($width_t, $height_t, $type, $attr) = getimagesize($imgPath);
    //如果高是0，自动计算高
    if ($height <= 0) {
        $height = round(($width / $width_t) * $height_t);
    }
    //判断生成的缩略图大小是否正常
    if ($width >= $width_t || $height >= $height_t) {
        return $imgurl;
    }
    //生成缩略图
    if (1 == $thumbType) {
        \Image::thumb2($imgPath, $imgPathDir . $newFileName, '', $width, $height, true);
    } else {
        \Image::thumb($imgPath, $imgPathDir . $newFileName, '', $width, $height, true);
    }
    $_thumb_cache[$key] = str_replace($basename, $newFileName, $imgurl);
    return $_thumb_cache[$key];
}/**
 * 获取用户头像 
 * @param type $uid 用户ID
 * @param int $format 头像规格，默认参数90，支持 180,90,45,30
 * @param type $dbs 该参数为true时，表示使用查询数据库的方式，取得完整的头像地址。默认false
 * @return type 返回头像地址
 */
function getavatar($uid, $format = 180, $dbs = false) {
    return service('Passport')->getUserAvatar($uid, $format, $dbs);
}/**
 * 邮件发送
 * @param type $address 接收人 单个直接邮箱地址，多个可以使用数组
 * @param type $title 邮件标题
 * @param type $message 邮件内容
 */
function SendMail($address, $title, $message) {
    $config = cache('Config');
    import('PHPMailer');
    try {
        $mail = new \PHPMailer();
        $mail->IsSMTP();
        // 设置邮件的字符编码，若不指定，则为'UTF-8'
        $mail->CharSet = config("DEFAULT_CHARSET");
        $mail->IsHTML(true);
        // 添加收件人地址，可以多次使用来添加多个收件人
        if (is_array($address)) {
            foreach ($address as $k => $v) {
                if (is_array($v)) {
                    $mail->AddAddress($v[0], $v[1]);
                } else {
                    $mail->AddAddress($v);
                }
            }
        } else {
            $mail->AddAddress($address);
        }
        // 设置邮件正文
        $mail->Body = $message;
        // 设置邮件头的From字段。
        $mail->From = $config['mail_from'];
        // 设置发件人名字
        $mail->FromName = $config['mail_fname'];
        // 设置邮件标题
        $mail->Subject = $title;
        // 设置SMTP服务器。
        $mail->Host = $config['mail_server'];
        // 设置为“需要验证”
        if ($config['mail_auth']) {
            $mail->SMTPAuth = true;
        } else {
            $mail->SMTPAuth = false;
        }
        // 设置用户名和密码。
        $mail->Username = $config['mail_user'];
        $mail->Password = $config['mail_password'];
        return $mail->Send();
    } catch (phpmailerException $e) {
        return $e->errorMessage();
    }
}

/**
 * 获取分类上级id
 * @param Integer $curCatid 当前分类id
 * @param integer $level 级别，默认为第0级
 */
function getCategoryRootId($curCatid,$level=0){
    $arrchild = getCategory($curCatid,"arrparentid");
    $arrchild = explode(",", $arrchild);
    if(isset($arrchild[$level])){
        return $arrchild[$level];
    }
    return 0;
}

/**
 * 返回根据指定编码转换编码后的字符串，不需要考虑字符串本身的编码。
 * @param String $str 要转换的字符串
 * @param String $out_charset 指定输出的字符串编码
 * @param string $in_charset  字符串原来编码 此参数可选
 * @return string
 */
function siconv($str, $out_charset, $in_charset='') {

    $encode = mb_detect_encoding($str, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
    //$str = $encode . $str;

    if(empty($in_charset) && !empty($encode)){
        $in_charset = $encode;
    }

    if (strtoupper($out_charset) != strtoupper($in_charset) ) {
        if (function_exists('iconv') && (@$outstr = iconv("$in_charset//IGNORE", "$out_charset//IGNORE", $str))) {
            return $outstr;
        } elseif (function_exists('mb_convert_encoding') && (@$outstr = mb_convert_encoding($str, $out_charset, $in_charset))) {
            return $outstr;
        }
    }
    return $str;
}

/**
 * 对目录进行加密
 * @param String $dir 需要加密的目录名称
 */
function dirEnCode($dir){
    $str = "";
    if(!empty($dir)){
        //替换路径分隔符
        $dir = str_replace(
                array("/",'\\',"/\\","\\/",'\\\\','//'),
                DIRECTORY_SEPARATOR,
                $dir);
        $str = base64_encode(urlencode( str_replace(DIRECTORY_SEPARATOR,'|',$dir)));
    }
    return $str;
}
/**
 * 对目录进行解密
 * @param String $dir 需要解密的目录名称
 */
function dirDeCode($dir){
    $str = "";
    if(!empty($dir)){
        $dir = str_replace(array('..\\', '../', './', '.\\',), '', trim(urldecode(base64_decode($dir))));
        $str = str_replace("|", DIRECTORY_SEPARATOR, $dir);
    }
    return $str;
}

/**
 * 在模板中解析U函数值
 * @param type $str
 */
function smallPress_url($str=''){
    $start = strpos($str,"{:url(");
    if( !empty($str) && false != $start ){
        $head  = substr($str, 0,$start);
        $end   = strpos($str, ")}",$start);
        if(false != $end){
            $foot = substr($str,$end+2);
            $tstr = substr($str,$start+5,$end-$start-6);
            $param = explode("','", $tstr );
            $str = $head . url($param[0],$param[1]).$foot;
        }
    }
    return $str;
}

function sbasename($filename){
    $pos = strrpos($filename,DIRECTORY_SEPARATOR);
    return substr($filename, $pos + 1);
}
/**
 * 删除指定目录下的所有文件及目录，成功删除则返回true，否则返回false。
 * @param String $dir 指定目录
 * @return boolean
 */
function rmdirs($dir){

	if( !is_dir($dir) || $dir == "" ) return false;

	$dh = opendir($dir);
	while(false !== ($file = readdir($dh))){
		if($file != '.' && $file != '..') {
			$path = $dir.'/'.$file;
			if(is_dir($path))
				rmdirs($path);
			else
				unlink($path);
		}
	}
	return @rmdir($dir);
}
/**
 * 获取VIP等级名称
 * @param intval $vipid  vipid
 * @param string $field 字段名称
 * @param boolen $newCache 是否更新缓存 默认false不更新
 */
function getVipRank($vipid=-1, $field = '', $newCache = false) {
    $key = 'getVipRank';
    //强制刷新缓存
    if ($newCache) {
        cache($key, NULL);
    }
    $cache = cache($key);
    if ($cache === 'false') {
        return false;
    }
    if (empty($cache)) {
        //读取数据,并缓存
        $tmpdata = db('member_viptype')->select();
        foreach($tmpdata as $v){
            $cache[$v['id']] = $v;
        }
        cache($key, $cache, 3600);
    }
    if ($vipid == -1){
        return $cache;
    }
    if ($vipid == 0){
        return '--';
    }
    if ($field) {
        return $cache[$vipid][$field];
    } else {
        return $cache[$vipid];
    }
}
/**
 * 获取VIP等级信息
 * @param intval $amount  vip等级费用
 * @param boolen $newCache 是否更新缓存 默认false不更新
 */
function getVipRankByAmount($amount,$newCache=false) {
    $key = 'getVipRank';
    //强制刷新缓存
    if ($newCache) {
        cache($key, NULL);
    }
    $cache = cache($key);
    if ($cache === 'false') {
        return false;
    }
    if (empty($cache)) {
        //读取数据,并缓存
        $tmpdata = db('member_viptype')->select();
        foreach($tmpdata as $v){
            $cache[$v['id']] = $v;
        }
        cache($key, $cache, 3600);
    }
    $retdata = array();
    if ($amount >0 ){
        foreach ($cache as $k => $v){
            if($amount == $v['amount']){
                $retdata = $v;
                break;
            }
        }
    }
    return $retdata;
}
/**
 * 用户资金操作函数
 * @param float  $amount 操作资金，可以是正值，和负值，正数表示用户自己增加，负数表示资金减少
 * @param string $msg 变化时信息
 * @param type $userid 用户id 如果为空则获取当前用户id
 * @param type $type 操作类型，1为充值，2为支付VIP费用，3为付费观看 为3时，以下参数必传，$catid,$cid,$title
 * @param type $ordersn 订单sn
 * @param type $catid 栏目id
 * @param type $cid 内容id
 * @param type $title 内容标题
 */
function optAmount($amount,$msg,$userid=0,$type=1,$ordersn='',$catid=0,$cid=0,$title=""){
    $usinfo = array();
    if($userid>0){
        $tusinfo = db('Member')->where("userid=$userid")->select();
        $usinfo = $tusinfo[0];
    }else{
        $usinfo = service('Passport')->getInfo();
    }
    if(!empty($usinfo)){
        $uid = $usinfo['userid'];
        $username = $usinfo['username'];
        
        $map = array();
        $map['userid'] = $uid;
        if (empty($map)) {
            return false;
        }
        $tinfo =  db('Member')->where($map)->select();
        if (empty($tinfo)) {
            return false;
        }
        $info = $tinfo[0];
        $point = $info['amount'] + $amount;
        if ($point < 0) {
            $point = 0;
        }
        $info['amount'] = $point;
        //更新
        if (false !== db('Member')->save($info)) {
            AmountLog($uid, $username,$type,$ordersn,$amount,$msg,$catid,$cid,$title);
            return true;
        }
        
        return db('Member')->getDbError();
    }
    return false;
}
/**
 * 记录资金日志
 * @param type $uid 用户id
 * @param type $username 用户名
 * @param type $type 日志类型
 * @param type $ordersn 订单编号
 * @param type $price 资金
 * @param type $log 日志信息
 * @param type $catid 栏目id
 * @param type $cid 内容id
 * @param type $title 内容标题
 * @return boolean 成功返回true，失败返回false
 */
function AmountLog($uid,$username,$type,$ordersn,$price=0,$log='',$catid=0,$cid=0,$title=""){
    $db = db("amountlog");
    $data=array(
        "userid" => $uid,
        "username" => $username,
        "type" => $type,
        "ordersn" => $ordersn,
        "price" => $price,
        "msg" => $log,
        "createtime" => time(),
        "catid" => $catid,
        "cid" => $cid,
        "title" => $title,
    );
    if($db->add($data)){
        return true;
    }
    return false;
}
/**
 * 获取内容是否付过费
 * @param type $uid 用户id
 * @param type $catid 栏目id
 * @param type $cid  内容id
 * @return boolean
 */
function getViewedContent($uid,$catid,$cid){
    $db = db("amountlog");
    $map = array(
        "userid"=> $uid,
        "catid" => $catid,
        "cid"   => $cid,
        "type"  => 3
    );
    $data = $db->where($map)->select();
    if(!empty($data)){
        return true;
    }
    return false;
}
/**
 * 创建订单
 * @param type $uid 用户id
 * @param type $username 用户名
 * @param type $type 订单类型 1为充值，2为购买VIP套餐, 3为付费查看内容
 * @param type $price 订单金额
 * @param type $quantity 订单数量
 * @param type $subject 标题
 * @param type $other  其它数据
 * @param type $order_sn 传入订单号
 * @return type 如果成功创建订单则返回数组array（id，ordersn），如果失败返回false
 */
function CreatOrder($uid,$username,$type,$price,$quantity,$subject,$other,$order_sn=""){
    $db = db("order");
    $ordersn = !empty($order_sn) ? $order_sn : "PD".date("YmdHis");
    $data = array(
        "ordersn"  => $ordersn,
        "userid"   => $uid,
        "username" => $username,
        "type"     => $type,
        "price"    => $price,
        "quantity" => $quantity,
        "subject"  => $subject,
        "other"    => $other,
        "createtime" => time(),
        "order_status" => 0,
    );
    $id = $db->add($data);
    if($id){
        return array("id"=>$id,"ordersn"=>$ordersn);
    }
    return false;
}
/**
 * 根据订单id，或者sn获取订单信息
 * @param type $order_id 订单id
 * @param type $order_sn 订单sn
 */
function getOrderInfo($order_sn,$field=''){
    $key = "OrderData-".$order_sn;

    $cache = cache($key);
    if (empty($cache)) {
        //读取数据,并缓存
        $db = db("order");
        $map = array();
        if(!empty($order_sn)){
            $map['ordersn'] = $order_sn;
        }
        $tmpdata = $db->where($map)->select();
        $cache = $tmpdata[0];
        cache($key, $cache, 3600);
    }
    
    if(empty($field)){
        return $cache;
    }
    if(!empty($field) && isset($cache[$field]) ){
        return $cache[$field];
    }
}
/**
 * 改变订单状态
 * @param type $order_sn 订单编号
 * @param type $wxsn 微信端交易号
 * @param type $status 订单状态 0为等待付款，5为已付款，
 * @param type $paytime 订单最后支付时间
 * @return boolean
 */
function changeOrderStatus($order_sn,$wxsn="",$status=0,$paytime=0){
    $db = db("order");
    $map = array();
    if(!empty($order_sn)){
        $map['ordersn'] = $order_sn;
    }else{
        return false;
    }
    $tmpdata = $db->where($map)->select();
    $data = $tmpdata[0];
    
    if(!empty($wxsn)){
        $data['tradeno'] = $wxsn;
    }
    if(!empty($status)){
        $data['order_status'] = $status;
    }
    if(!empty($paytime)){
        $data['pay_time'] = $paytime;
    }
    
    if($db->save($data)){
        return true;
    }
    return FALSE;
}
/**
 * 判断是否是手机等设备
 * @return boolean
 */
function is_mobile(){
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    if(strpos($useragent,"Mobile") !== false){
        return true;
    }
    return false;
}
/**
 * 判断是否是微信浏览器浏览
 * @return boolean
 */
function is_weixin(){
    //MicroMessenger
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    if(strpos($useragent,"MicroMessenger") !== false){
        return true;
    }
    return false;
}
/**
 * 解析字符串查询条件
 * @param type $where 查询条件
 * @return mixed 如果成功则返回规则数组，否则放回false
 */
function parseWhere($where=""){
        $retarray = [];
        
        $where = str_replace(['{','}'],[' ',' '],$where);
        $where = preg_replace("/\s*\(\s*/", "(", $where);
        $where = preg_replace("/\s*\)\s*/", ")", $where);
        $tmp = preg_split("/\s+/", trim($where));
        if(count($tmp) % 4 != 3){
            return FALSE;
        }
        //print_r($tmp);
        $flg = "and";
        for($i=0;$i<count($tmp);$i+=4){
            if($flg == "and"){
                $retarray[0][] = [$tmp[$i],$tmp[$i+1],$tmp[$i+2]]; 
            }else{
                $retarray[1][] = [$tmp[$i],$tmp[$i+1],$tmp[$i+2]]; 
            }
            $flg = strtolower($tmp[$i+3]);
        }
        
        return $retarray;
    }
<?php

// +----------------------------------------------------------------------
// | rootCMS 后台框架首页
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\AdminBase;

class Main extends AdminBase {

    public function index() {
        //服务器信息
        $info = array(
            '操作系统' => PHP_OS,
            '运行环境' => $_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式' => php_sapi_name(),
            'MYSQL版本' => '',//mysql_get_server_info(),
            '产品名称' => '<font color="#FF0000">' . CMS_APPNAME . '</font>',
            '用户类型' => '<font color="#FF0000" id="server_license">授权用户</font>',
            '产品版本' => '<font color="#FF0000">' . CMS_VERSION . '</font>',
            '产品流水号' => '<font color="#FF0000">' . CMS_BUILD . '</font>',
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time') . "秒",
            '剩余空间' => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
        );

        $this->assign('server_info', $info);
        $this->display();
    }

    public function public_server() {
        $post = array(
            'domain' => $_SERVER['SERVER_NAME'],
        );
        $cache = cache('_serverinfo');
        if (!empty($cache)) {
            $data = $cache;
        }
//        else {
//            $data = $this->Cloud->data($post)->act('get.serverinfo');
//            cache('_serverinfo', $data, 300);
//        }
        if (!empty($_COOKIE['notice_' . $data['notice']['id']])) {
            $data['notice']['id'] = 0;
        }
        if (version_compare(SHUIPF_VERSION, $data['latestversion']['version'], '<')) {
            $data['latestversion'] = array(
                'status' => true,
                'version' => $data['latestversion'],
            );
        } else {
            $data['latestversion'] = array(
                'status' => false,
                'version' => $data['latestversion'],
            );
        }
        $this->ajaxReturn($data);
    }

}

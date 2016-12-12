<?php

// +----------------------------------------------------------------------
// | rootCMS 插件后台管理
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wb23 <wb1491@gmail.com>
// +----------------------------------------------------------------------

namespace Addon\Alipay\Controller;

use Addons\Util\Adminaddonbase;

class AdminController extends Adminaddonbase {

    //订单
    public function index() {
        if (IS_POST) {
            $this->redirect('index', $_POST);
        }
        $where = array();
        $start_time = input('get.start_time');
        if ($start_time) {
            $where['createtime'] = array('EGT', strtotime($start_time));
            $this->assign('start_time', $start_time);
            $end_time = input('get.end_time');
            if ($end_time) {
                $where['createtime'] = array(array('EGT', strtotime($start_time)), array('ELT', strtotime($start_time)), 'and');
                $this->assign('end_time', $end_time);
            }
        }
        $tradestatus = $_GET['tradestatus'];
        if ($tradestatus != '') {
            $where['tradestatus'] = $tradestatus;
            $this->assign('tradestatus', $tradestatus);
        }
        $field = input('get.field');
        $keyword = input('get.keyword');
        if ($field && $keyword) {
            $this->assign('field', $field)->assign('keyword', $keyword);
            switch ($field) {
                case 'subject':
                    $where['subject'] = array('LIKE', "%{$keyword}%");
                    break;
                case 'userid':
                    $where['userid'] = (int) $keyword;
                    break;
                case 'username':
                    $where['username'] = trim($keyword);
                    break;
                case 'tradeno':
                    $where['tradeno'] = trim($keyword);
                    break;
            }
        }
        $this->basePage(M('Alipay'), $where, array('id' => 'DESC'), 20);
    }

    //操作日志
    public function log() {
        $where = array();
        if (IS_POST) {
            $this->redirect('log', $_POST);
        }
        $field = input('get.field');
        $keyword = input('get.keyword');
        if ($field && $keyword) {
            $this->assign('field', $field)->assign('keyword', $keyword);
            switch ($field) {
                case 'subject':
                    $where['subject'] = array('LIKE', "%{$keyword}%");
                    break;
                case 'orderid':
                    $where['orderid'] = (int) $keyword;
                    break;
            }
        }
        $this->basePage(M('AlipayLog'), $where, array('id' => 'DESC'), 20);
    }

    //订单类型
    public function type() {
        $where = array();
        $this->basePage(M('AlipayType'), $where, array('id' => 'DESC'), 20);
    }

    //添加新类型
    public function add() {
        if (IS_POST) {
            $data = input('post.');
            if (empty($data['name'])) {
                $this->error('类型名称不能为空！');
            }
            if (empty($data['price'])) {
                $this->error('金额不能为空！');
            }
        }
        $this->baseAdd(M('AlipayType'), 'type?isadmin=1');
    }

    //修改类型
    public function edit() {
        if (IS_POST) {
            $data = input('post.');
            if (empty($data['name'])) {
                $this->error('类型名称不能为空！');
            }
            if (empty($data['price'])) {
                $this->error('金额不能为空！');
            }
        }
        $this->baseEdit(M('AlipayType'), 'type?isadmin=1');
    }

    //删除类型
    public function delete() {
        $this->baseDelete(M('AlipayType'), 'type?isadmin=1');
    }

}

<?php

// +----------------------------------------------------------------------
// | rootCMS 插件后台管理
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace Addon\Keylink\Controller;

use Addons\Util\Adminaddonbase;

class AdminController extends Adminaddonbase {

    private $db = NULL;

    //初始化
    protected function _initialize() {
        parent::_initialize();
        import('KeylinkModel', $this->addonPath);
        $this->db = \Think\Think::instance('\Addon\Keylink\KeylinkModel');
    }

    //显示已添加的关键词
    public function index() {
        $map = array();
        $count = $this->db->where($map)->count();
        $page = $this->page($count, 20);
        $data = $this->db->where($map)->limit($page->firstRow . ',' . $page->listRows)->order(array("keylinkid" => "DESC"))->select();
        $this->assign("Page", $page->show());
        $this->assign("data", $data);
        $this->display();
    }

    //添加
    public function add() {
        if (IS_POST) {
            if ($this->db->addKey($_POST)) {
                $this->success('添加成功！',  url('index', 'isadmin=1'));
            } else {
                $error = $this->db->getError();
                $this->error($error ? $error : '添加失败！');
            }
        } else {
            $this->display();
        }
    }

    //编辑
    public function edit() {
        if (IS_POST) {
            if ($this->db->editKey($_POST)) {
                $this->success('编辑成功！',  url('index', 'isadmin=1'));
            } else {
                $error = $this->db->getError();
                $this->error($error ? $error : '编辑失败！');
            }
        } else {
            $keylinkid = input('get.keylinkid', 0, 'intval');
            $info = $this->db->where(array('keylinkid' => $keylinkid))->find();
            if (empty($info)) {
                $this->error('该信息不存在！');
            }
            $this->assign('info', $info);
            $this->display();
        }
    }

    //删除
    public function delete() {
        if (IS_POST) {
            $keylinkid = $_POST['keylinkid'];
            if (empty($keylinkid)) {
                $this->error('请选择需要删除的信息！');
            }
        } else {
            $keylinkid = input('get.keylinkid', 0, 'intval');
            $info = $this->db->where(array('keylinkid' => $keylinkid))->find();
            if (empty($info)) {
                $this->error('该信息不存在！');
            }
        }
        if ($this->db->deleteKey($keylinkid)) {
            $this->success('删除成功！');
        } else {
            $error = $this->db->getError();
            $this->error($error ? $error : '删除失败！');
        }
    }

}

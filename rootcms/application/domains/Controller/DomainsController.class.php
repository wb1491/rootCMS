<?php

// +----------------------------------------------------------------------
// | rootCMS 模块绑定域名
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wb23 <wb1491@gmail.com>
// +----------------------------------------------------------------------

namespace Domains\Controller;

use Common\Controller\AdminBase;

class DomainsController extends AdminBase {

    public $Module;

    //初始化
    protected function _initialize() {
        parent::_initialize();
        //可用模块列表
        $Module = array(
            'Admin' => array('module' => 'Admin', 'name' => '后台管理'),
        );
        foreach (cache('Module') as $r) {
            $Module[$r['module']] = array(
                "module" => $r['module'],
                "name" => $r['modulename']
            );
        }
        $this->assign("Module", $Module);
        $this->Module = $Module;
    }

    public function index() {
        $db = M("Domains");
        $data = $db->select();
        $this->assign("data", $data);
        $this->display();
    }

    //添加
    public function add() {
        if (IS_POST) {
            $db =  model("Domains/Domains");
            $data = $db->create();
            if ($data) {
                $status = $db->AddDomains($data);
                if ($status !== false) {
                    $this->success("添加成功！",  url('index'));
                } else {
                    $this->error("添加失败！");
                }
            } else {
                $this->error($db->getError());
            }
        } else {
            $this->display();
        }
    }

    //编辑
    public function edit() {
        if (IS_POST) {
            $db =  model("Domains/Domains");
            $data = $db->create();
            if ($data) {
                $status = $db->editDomains($data);
                if ($status !== false) {
                    $this->success("编辑成功！",  url('index'));
                } else {
                    $this->error("编辑失败！");
                }
            } else {
                $this->error($db->getError());
            }
        } else {
            $db = M("Domains");
            $id = input('get.id', 0, 'intval');
            $info = $db->where(array("id" => $id))->find();
            if (!$info) {
                $this->error("该信息不存在！");
            }
            $this->assign($info);
            $this->display();
        }
    }

    //删除
    public function delete() {
        $this->baseDelete('Domains/Domains');
    }

}

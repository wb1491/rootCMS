<?php

// +----------------------------------------------------------------------
// | rootCMS 会员收藏
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\member\controller;

class Amountlog extends Memberbase {

    //记录交易对象
    protected $log = NULL;

    protected function _initialize() {
        parent::_initialize();
        $this->log = M('amountlog');
    }

    //资金日志
    public function index() {
        $page = input("get.page");
        //查询条件
        $where = array(
            'userid' => $this->userid,
        );
        $count = $this->log->where($where)->count();
        $page = $this->page($count, 10,$page);
        $log = $this->log->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "DESC"))->select();

        $this->assign("Page", $page->show('Admin'));
        $this->assign('log', $log);
        $this->display();
    }

}

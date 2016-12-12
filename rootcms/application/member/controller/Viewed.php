<?php

// +----------------------------------------------------------------------
// | rootCMS 会员观看记录
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\member\controller;

class Viewed extends Memberbase {


    protected function _initialize() {
        parent::_initialize();
        $this->order = M('amountlog');
    }

    public function index() {
        $page = input("get.page");
        //查询条件
        $where = array(
            'userid' => $this->userid,
            'type' => 3
        );
        $count = $this->order->where($where)->count();
        $pages = $this->page($count, 10, $page);
        $favorite = $this->order->where($where)->limit($pages->firstRow . ',' . $pages->listRows)->order(array("id" => "DESC"))->select();

        $this->assign("Page", $pages->show('Admin'));
        $this->assign('favorite', $favorite);
        $this->display();
    }

}

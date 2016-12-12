<?php

// +----------------------------------------------------------------------
// | rootCMS 会员收藏
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\member\controller;

class Favorite extends Memberbase {

    //好友数据对象
    protected $favorite = NULL;

    protected function _initialize() {
        parent::_initialize();
        $this->favorite =  model('member/Favorite');
    }

    //收藏首页
    public function index() {
        //查询条件
        $where = array(
            'userid' => $this->userid,
        );
        $count = $this->favorite->where($where)->count();
        $page = $this->page($count, 10);
        $favorite = $this->favorite->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("fid" => "DESC"))->select();

        $this->assign("Page", $page->show('Admin'));
        $this->assign('favorite', $favorite);
        $this->display();
    }

    //添加收藏
    public function add() {
        //栏目ID
        $catid = input('get.catid', 0, 'intval');
        //信息ID
        $id = input('get.id', 0, 'intval');
        //参数检查
        if (empty($catid) || empty($id)) {
            $this->error('参数错误！');
        }
        //构建数据
        $data = array(
            'userid' => $this->userid,
            'catid' => $catid,
            'id' => $id,
        );
        //添加收藏
        if ($this->favorite->favoriteAdd($data)) {
            $this->message(10000, array(), true);
        } else {
            $error = $this->favorite->getError();
            $this->error($error ? $error : '收藏失败！');
        }
    }

    //删除收藏
    public function favoritedel() {
        //收藏ID
        $fid = input('get.fid', 0, 'intval');
        if (empty($fid)) {
            $this->message(array(
                'info' => '没有该收藏记录！',
                'error' => 20002,
            ));
        }
        //检查是否有该收藏
        $fid = $this->favorite->where(array('userid' => $this->userid, 'fid' => $fid))->column('fid');
        if (empty($fid)) {
            $this->message(array(
                'info' => '没有该收藏记录！',
                'error' => 20002,
            ));
        }
        //执行删除
        if (false !== $this->favorite->favoriteDel($this->userid, $fid)) {
            $this->message(10000, array(), true);
        } else {
            $this->error('删除失败！');
        }
    }

}

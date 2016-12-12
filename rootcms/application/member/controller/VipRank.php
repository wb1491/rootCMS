<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\member\controller;

class VipRank extends Memberbase {
    //会员用户组缓存
    protected $groupCache = array();
    //会员模型
    protected $groupsModel = array();
    //会员数据模型
    protected $member = NULL;
    
    protected $viptype = Null;

    //初始化
    protected function _initialize() {
        parent::_initialize();
        $this->viptype = M("member_viptype");
    }
    
    //会员vip等级管理首页
    public function index() {
        
        $where = "";
        
        $count = $this->viptype->where($where)->count();
        $page = $this->page($count, 20);
        $data = $this->viptype->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "DESC"))->select();

        foreach ($this->groupCache as $g) {
            $groupCache[$g['groupid']] = $g['name'];
        }
        foreach ($this->groupsModel as $m) {
            $groupsModel[$m['modelid']] = $m['name'];
        }
        
        $this->assign("Page", $page->show('Admin'));
        $this->assign("data", $data);
        $this->display();
    }
    
    public function add(){
        if(IS_POST){
            $data =  $this->viptype->create();
            if(!empty($data)){
                $this->viptype->add($data);
                $this->success("VIP等级添加成功");
            }
            $this->error("添加失败！");
        }else{
            $this->assign("stattime", getStartTime());
            $this->display();
        }
    }
    
    public function delete(){
        $id = input("id");
        if(!empty($id) ){
            $this->viptype->delete($id);
            $this->success("删除成功！");
        }
    }
    
    public function edit(){
        $id = input("get.id");
        if(IS_POST){
            $data =  input("post.");
            if(!empty($data)){
                $this->viptype->where("id=".$id)->save($data);
                $this->success("VIP等级修改成功");
            }
            $this->error("修改失败！");
        }
        $data = $this->viptype->select($id);
        $this->assign("data",$data[0]);
        $this->assign("stattime", getStartTime());
        $this->display();
    }
}
<?php

// +----------------------------------------------------------------------
// | rootCMS 我的面板
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\AdminBase;
use app\admin\service\User;

class Adminmanage extends AdminBase {

    //修改当前登陆状态下的用户个人信息
    public function myinfo() {
        if (IS_POST) {
            $data = array(
                'id' => User::getInstance()->id,
                'nickname' => input('nickname'),
                'email' => input('email'),
                'remark' => input('remark')
            );
            if ( model("admin/User")->create($data)) {
                if ( model("admin/User")->where(array('id' => User::getInstance()->id))->save() !== false) {
                    $this->success("资料修改成功！",  url("Adminmanage/myinfo"));
                } else {
                    $this->error("更新失败！");
                }
            } else {
                $this->error( model("admin/User")->getError());
            }
        } else {
            $this->assign("data", User::getInstance()->getInfo());
            $this->display();
        }
    }

    //后台登陆状态下修改当前登陆人密码
    public function chanpass() {
        if (IS_POST) {
            $oldPass = input('post.password', '', 'trim');
            if (empty($oldPass)) {
                $this->error("请输入旧密码！");
            }
            $newPass = input('post.new_password', '', 'trim');
            $new_pwdconfirm = input('post.new_pwdconfirm', '', 'trim');
            if ($newPass != $new_pwdconfirm) {
                $this->error("两次密码不相同！");
            }
            if ( model("admin/User")->changePassword(User::getInstance()->id, $newPass, $oldPass)) {
                //退出登陆
                User::getInstance()->logout();
                $this->success("密码已经更新，请从新登陆！",  url("admin/Publics/login"));
            } else {
                $error =  model("admin/User")->getError();
                $this->error($error ? $error : "密码更新失败！");
            }
        } else {
            $this->assign('userInfo', User::getInstance()->getInfo());
            $this->display();
        }
    }

    //验证密码是否正确
    public function public_verifypass() {
        $password = input("get.password");
        if (empty($password)) {
            $this->error("密码不能为空！");
        }
        //验证密码
        $user =  model('admin/User')->getUserInfo((int) User::getInstance()->id, $password);
        if (!empty($user)) {
            $this->success("密码正确！");
        } else {
            $this->error("密码错误！");
        }
    }

}

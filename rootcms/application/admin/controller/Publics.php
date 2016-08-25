<?php

// +----------------------------------------------------------------------
// | rootCMS 后台模块公共方法
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\AdminBase;
use app\admin\service\User;

class Publics extends AdminBase {

    //后台登陆界面
    public function login() {
        //如果已经登录
        if (User::getInstance()->id) {
            $this->redirect('admin/Index/index');
        }
        return $this->display("Public:login");
    }

    //后台登陆验证
    public function tologin() {
        //记录登陆失败者IP
        $ip = get_client_ip();
        $username = input("post.username", "", "trim");
        $password = input("post.password", "", "trim");
        $code = input("post.code", "", "trim");
        if (empty($username) || empty($password)) {
            $this->error("用户名或者密码不能为空，请重新输入！",  url("admin/Publics/login"));
        }
        if (empty($code)) {
            $this->error("请输入验证码！",  url("admin/Publics/login"));
        }
        //验证码开始验证
        if (!$this->verify($code,'adminlogin')) {
            $this->error("验证码错误，请重新输入！",  url("admin/Publics/login"));
        }
        if (User::getInstance()->login($username, $password)) {
            $forward = cookie("forward");
            if (!$forward) {
                $forward =  url("admin/Index/index");
            } else {
                cookie("forward", NULL);
            }
            //增加登陆成功行为调用
            $admin_public_tologin = array(
                'username' => $username,
                'ip' => $ip,
            );
            tag('admin_public_tologin', $admin_public_tologin);
            //$this->redirect('admin/Index/index');
            $this->success("认证成功！",url("admin/Index/index"));
        } else {
            //增加登陆失败行为调用
            $admin_public_tologin = array(
                'username' => $username,
                'password' => $password,
                'ip' => $ip,
            );
            tag('admin_public_tologin_error', $admin_public_tologin);
            $this->error("用户名或者密码错误，登陆失败！",  url("admin/Publics/login"));
        }
    }

    //退出登陆
    public function logout() {
        if (User::getInstance()->logout()) {
            //手动登出时，清空forward
            cookie("forward", NULL);
            $this->success('注销成功！',  url("admin/Publics/login"));
        }
    }

    //常用菜单设置
    public function changyong() {
        if (IS_POST) {
            //被选中的菜单项
            $menuidAll = explode(',', input('post.menuid', ''));
            if (is_array($menuidAll) && count($menuidAll) > 0) {
                //取得菜单数据
                $menu_info = cache('Menu');
                $addPanel = array();
                //检测数据合法性
                foreach ($menuidAll as $menuid) {
                    if (empty($menu_info[$menuid])) {
                        continue;
                    }
                    $info = array(
                        'mid' => $menuid,
                        'userid' => User::getInstance()->id,
                        'name' => $menu_info[$menuid]['name'],
                        'url' => "{$menu_info[$menuid]['app']}/{$menu_info[$menuid]['controller']}/{$menu_info[$menuid]['action']}",
                    );
                    if($menu_info[$menuid]['parameter']){
                        $info['url'] .= "?{$menu_info[$menuid]['parameter']}";
                    }
                    $addPanel[] = $info;
                }
                if ( model('admin/AdminPanel')->addPanel($addPanel)) {
                    $this->success("添加成功！",  url("Publics/changyong"));
                } else {
                    $error =  model('admin/AdminPanel')->getError();
                    $this->error($error ? $error : '添加失败！');
                }
            } else {
                 model('admin/AdminPanel')->where(array("userid" => \app\admin\service\User::getInstance()->id))->delete();
                $this->error("常用菜单清除成功！");
            }
        } else {
            //菜单缓存
            $result = cache("Menu");
            $json = array();
            //子角色列表
            $child = explode(',',  model("admin/Role")->getArrchildid(\app\admin\service\User::getInstance()->role_id));
            foreach ($result as $rs) {
                if ($rs['status'] == 0) {
                    continue;
                }
                //条件
                $where = array('app' => $rs['app'], 'controller' => $rs['controller'], 'action' => $rs['action'], 'role_id' => array('IN', $child));
                //是否有权限
                if (! model('admin/Access')->isCompetence($where)) {
                    continue;
                }
                $data = array(
                    'id' => $rs['id'],
                    'nocheck' => $rs['type'] ? 0 : 1,
                    'checked' => $rs['id'],
                    'parentid' => $rs['parentid'],
                    'name' => $rs['name'],
                    'checked' =>  model("admin/AdminPanel")->isExist($rs['id']) ? true : false,
                );
                $json[] = $data;
            }
            $this->assign('json', json_encode($json));
            return $this->display();
        }
    }

}

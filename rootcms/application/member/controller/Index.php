<?php

// +----------------------------------------------------------------------
// | rootCMS 会员中心首页
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\member\controller;


class Index extends Memberbase {

    //会员中心首页
    public function index() {
        $this->redirect('Index/home');
    }

    //个人首页
    public function home() {
        $this->redirect('User/profile');
    }

    //登录页面
    public function login() {
        $forward = $_REQUEST['forward'] ? $_REQUEST['forward'] : cookie("forward");
        cookie("forward", null);
        if (!empty($this->userid)) {
            $this->success("您已经是登陆状态！", $forward ? $forward :  url("Index/index"));
        } else {
            $this->assign('forward', $forward);
            return $this->display('Public:login');
        }
    }

    //注册页面
    public function register() {
        if (empty($this->memberConfig['allowregister'])) {
            $this->error("系统不允许新会员注册！");
        }
        $forward = $_REQUEST['forward'] ? $_REQUEST['forward'] : cookie("forward");
        cookie("forward", null);
        if ($this->userid) {
            $this->success("您已经是登陆状态，无需注册！", $forward ? $forward :  url("Index/index"));
        } else {
            $count = $this->memberDb->where(array('checked' => 1))->count('userid');
            //取出人气高的8位会员
            $heat = $this->memberDb->where(array('checked' => 1))->order(array('heat' => 'DESC'))->field('userid,username,heat')->limit(8)->select();

            $this->assign('heat', $heat);
            $this->assign('count', $count);
            return $this->display('Public:register');
        }
    }

    //头像设置
    public function regavatar() {
        $user_avatar = service("Passport")->getUploadPhotosHtml($this->userid);
        $this->assign('user_avatar', $user_avatar);
        return $this->display('Public:regavatar');
    }

    //保存用户头像
    public function uploadavatar() {
        $auth_code = input("get.auth_data");
        $auth_data = \Libs\Util\Encrypt::authcode(str_replace(' ', '+', $auth_code), 'DECODE');
        if ($auth_data != $this->userid) {
            exit(json_encode(array(
                'success' => false,
                'msg' => '身份验证失败！',
            )));
        }
        
        //$_POST['avatar_src'], $_POST['avatar_data'], $_FILES['avatar_file'];
        $src  = input("post.avatar_src");
        $data = $_POST['avatar_data'];
        $file = $_FILES['avatar_file'];
        //头像保存目录
        if(empty($src)){
            $src  = config("UPLOADFILEPATH") . service("Passport")->getAvatarPath($this->userid);
            $src .= service("Passport")->getAvatarFile($this->userid);
        }
        $src = str_replace(SITE_PATH,"",$src);
        
        //error_reporting(E_ALL); 
        //ini_set('display_errors', '1');
        //require_once(APP_PATH."Libs/Util/CropAvatar.class.php");
        $crop = new \CropAvatar($src , $data , $file);
        
        $response = array(
            'state'  => 200,
            'message' => $crop -> getMsg(),
            'result' => $crop -> getResult()
        );
        
        echo json_encode($response);
    }

    //退出
    public function logout() {
        service("Passport")->logoutLocal();
        session("connect_openid", NULL);
        session("connect_app", NULL);
        //注销在线状态
        model('member/Online')->onlineDel();
        //tag 行为点
        tag('action_member_logout');
        $this->success("退出成功！",  url("member/Index/login"));
    }

    //忘记密码界面
    public function lostpassword() {
        return $this->display('Public:lostpassword');
    }

    //重置密码
    public function resetpassword() {
        $getKey = input('get.key');
        if ($getKey) {
			$getKey = str_replace(array('+', '%23', '%2F', '%3F', '%26', '%3D','%2B'), array(' ', '#', '/', '?', '&', '=','+'), $getKey);
        }
        $key = \Libs\Util\Encrypt::authcode($getKey);
        if (empty($key)) {
            $this->error('验证失败，请从新提交密码找回申请！',  url('Index/lostpassword'));
        }
        $userinfo = explode('|', $key);
        $this->assign('userinfo', array(
            'userid' => $userinfo[0],
            'username' => $userinfo[1],
            'email' => $userinfo[2],
        ));
        $this->assign('key', $getKey);
        return $this->display('Public:resetpassword');
    }

    //验证邮箱
    public function verifyemail() {
        $getKey = input('get.key');
        if ($getKey) {
			$getKey = str_replace(array('+', '%23', '%2F', '%3F', '%26', '%3D','%2B'), array(' ', '#', '/', '?', '&', '=','+'), $getKey);
        }
        $key = \Libs\Util\Encrypt::authcode($getKey);
        if (empty($key)) {
            $this->error('验证失败，请从新提交密码找回申请！',  url('Index/login'));
        }
        $userinfo = explode('|', $key);
        //取得用户资料
        $userinfo = $this->memberDb->getUserInfo((int) $userinfo[0], 'userid,username,email,groupid,checked,point');
        if (empty($userinfo)) {
            $this->error('该帐号不存在，无法进行邮箱验证！',  url('member/Index/index'));
        }
        if ($userinfo['checked']) {
            $this->success('该帐号已经验证通过！',  url('member/Index/index'));
        }
        $data = array();
        $data['checked'] = 1;
        $data['groupid'] = $this->memberDb->get_usergroup_bypoint($userinfo['point']);
        if (false !== $this->memberDb->where(array('userid' => $userinfo['userid']))->save($data)) {
            $this->success('邮箱验证完成！',  url('member/Index/index'));
        } else {
            $this->error('邮箱验证失败！',  url('member/Index/index'));
        }
    }

}

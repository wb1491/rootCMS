<?php
// +----------------------------------------------------------------------
// | rootCMS 获取手机验证码
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------
namespace Api\Controller;

use Common\Controller\ShuipFCMS;

class GetcodeController extends ShuipFCMS {
    public $charset = '0123456789';
    private $key = "mobile";

    public function _initialize() {
        parent::_initialize();
        $this->key .= session_id();
    }

    public function index(){
        $code = $this->getCode();
        $mobile = input("post.user_mobile");
        $msg = "您本次的验证码为：".$code." 此验证码10分钟内有效。";
        
        require_once PROJECT_PATH.'Addon/Cmpp/sendSMS.class.php';
        $sendsms = new \Addon\Cmpp\sendSMS();
        $result = $sendsms->sendSMS($mobile,$msg);
        if(is_array($result)){
            $flg = $result[1] == 0 ? 1 : 0;
            $retdata = array_merge($result,array("status"=>$flg,"info"=>  $sendsms->recode[$result[1]]));
            if($flg){
                session($this->key,$code);
            }
        }
        
        if(IS_AJAX){
            $this->ajaxReturn($retdata);
        }
        if($retdata['status'] ==0){
            $this->error($retdata['info']);
        }
        else
        {
            $this->success($retdata['info']);
        }
    }
    
    private function getCode($len=6){
        $code = cache($this->key);
        if(!$code){
            $code = '';
            $_len = strlen($this->charset) - 1;
            for ($i = 0; $i < $len; $i++) {
                $code .= $this->charset[mt_rand(0, $_len)];
            }
            cache($this->key,$code,600);
        }
        return $code;
    }
}

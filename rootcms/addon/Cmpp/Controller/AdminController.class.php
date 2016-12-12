<?php

// +----------------------------------------------------------------------
// | rootCMS 插件后台管理
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------
namespace Addon\Cmpp\Controller;

use Addons\Util\Adminaddonbase;

class AdminController extends Adminaddonbase {

    protected $config = array();
    
    protected $code = array(
        0=>"提交成功",
        101	=>"无此用户",
        102	=>"密码错",
        103	=>"提交过快（提交速度超过流速限制）",
        104	=>"系统忙（因平台侧原因，暂时无法处理提交的短信）",
        105	=>"敏感短信（短信内容包含敏感词）",
        106	=>"消息长度错（>536或<=0）",
        107	=>"包含错误的手机号码",
        108	=>"手机号码个数错（群发>50000或<=0;单发>200或<=0）",
        109	=>"无发送额度（该用户可用短信数已使用完）",
        110	=>"不在发送时间内",
        111	=>"超出该账户当月发送额度限制",
        112	=>"无此产品，用户没有订购该产品",
        113	=>"extno格式错（非数字或者长度不对）",
        115	=>"自动审核驳回",
        116	=>"签名不合法，未带签名（用户必须带签名的前提下）",
        117	=>"IP地址认证错,请求调用的IP地址不是系统登记的IP地址",
        118	=>"用户没有相应的发送权限",
        119	=>"用户已过期",
        120	=>"测试内容不是白名单",
    );


    protected function _initialize() {
        parent::_initialize();
        //获取插件配置
        $config = $this->getAddonConfig();
        if (empty($config)) {
            $this->error('请先进行相关配置！');
        }
        $this->config = $config;
    }
    
    function index(){
        $this->display();
    }

    public function postSMS() {
        
        $mobile  = input("post.mobile");
        $message = input("post.message");
        
        if(empty($mobile) || empty($message)){
            $this->error("参数错误！");
            exit;
        }
        $tmobile = str_replace(array("\n","\r","\n\r",";"),",", $mobile);
        
        $result = $this->sendSMS($tmobile, $message,true);
        if(is_array($result) && $result[1] == 0){
            $this->success("短信发送成功！");
        }elseif(is_array($result)){
            $this->error($this->code[$result[1]]);
        }
        $this->error("短信发送失败！");
    }
    
    /**
	 * 发送短信
	 *
	 * @param string $mobile 手机号码
	 * @param string $msg 短信内容
	 * @param string $needstatus 是否需要状态报告
	 * @param string $extno   扩展码，可选
	 */
	public function sendSMS( $mobile, $msg, $needstatus = 'false', $extno = '') {
		//创蓝接口参数
		$postArr = array (
				          'account' => $this->config['account'],
				          'pswd' => $this->config["password"],
				          'msg' => $msg,
				          'mobile' => $mobile,
				          'needstatus' => $needstatus,
				          'extno' => $extno
                     );
		
		$result = $this->curlPost( $this->config['sendurl'] , $postArr);
		return $result;
	}
	
	/**
	 * 查询额度
	 *
	 *  查询地址
	 */
	public function queryBalance() {
		//查询参数
		$postArr = array ( 
		          'account' => $this->config['account'],
		          'pswd' => $this->config["password"],
		);
		$result = $this->curlPost($this->config['queryurl'], $postArr);
        if(is_array($result) && $result[1] == 0 ){
            $this->ajaxReturn(array("status"=>1,"balance"=>$result[3]));
        }
		$this->ajaxReturn(array("status"=>0,"balance"=>"--"));
	}

	/**
	 * 处理返回值
	 * 
	 */
	public function returnCode($result){
		$result=preg_split("/[,\r\n]/",$result);
        if(count($result>1)){
            return $result;
        }
		return false;
	}

	/**
	 * 通过CURL发送HTTP请求
	 * @param string $url  //请求URL
	 * @param array $postFields //请求参数 
	 * @return mixed
	 */
	private function curlPost($url,$postFields){
		$tpostFields = http_build_query($postFields);
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $tpostFields );
		$result = curl_exec ( $ch );
		curl_close ( $ch );
        
		return $this->returnCode($result);
	}

}

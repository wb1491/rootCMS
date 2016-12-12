<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Addon\Cmpp;

class sendSMS{
    public $config = array();
    
    public $recode = array(
        0   =>"短信发送成功",
        101	=>"无此用户",
        102	=>"密码错",
        103	=>"发送过快（提交速度超过流速限制）",
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
    
    public function __construct() {
        $addons = cache('Addons');
        $this->config = $addons['Cmpp']['config'];
    }
    
    /**
	 * 发送短信
	 *
	 * @param string $mobile 手机号码
	 * @param string $msg 短信内容
	 * @param string $needstatus 是否需要状态报告
	 * @param string $extno   扩展码，可选
	 */
	function sendSMS( $mobile, $msg, $needstatus = 'false', $extno = '') {
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
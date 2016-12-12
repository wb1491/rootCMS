<?php
/*
 * +----------------------------------------------------------------------
 * | Stumanager 微信支付对象
 * +----------------------------------------------------------------------
 * | Copyright (c) 2012-2016 http://www.linuxxt.cn, All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: wb23 (wb1491@gmail.com) 
 * +----------------------------------------------------------------------  
 */
namespace Addon\Wxpay\Controller;

use Addons\Util\AddonsBase;

//微信支付类
class IndexController  extends AddonsBase {
 
    protected $error = array();
    protected $path  = "";
    protected $logfile = "";
    protected $errfile = "";


    //初始化
    protected function _initialize() {
        parent::_initialize();
        
        config("APP_DEBUG",false);
        config('SHOW_PAGE_TRACE',false);
        
        $this->path = RUNTIME_PATH."Logs/wxpay/";
        if(!file_exists($this->path)){
            mkdir($this->path, 0777, TRUE);
        }
        $this->logfile = $this->path . "notify_url".date("Y-m").".log";
        $this->errfile = $this->path . "notify_err".date("Y-m").".log";
    }
	
	//jsapi接口异步通知url，商户根据实际开发过程设定
	public function notify() {
                
        require_once($this->addonPath."WxPayPubHelper/WxPayPubHelper.php");
	    //使用通用通知接口
	    $notify = new \Notify_pub();
        
	    //存储微信的回调
	    $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);
        
        if($notify->checkSign() == FALSE){
	        $notify->setReturnParameter("return_code", "FAIL");//返回状态码
	        $notify->setReturnParameter("return_msg", "签名失败");//返回信息
	    }else{
	        $notify->setReturnParameter("return_code", "SUCCESS");//设置返回码
	    }
        
        $this->log_result($this->logfile, "【接收到的notify通知】:\n".$xml."\n");
        
	    //返回状态，防止重复回调！
        echo $notify->returnXml();
        
	    if($notify->checkSign() == TRUE){
	        if ($notify->data["return_code"] == "FAIL") {
	            //此处应该更新一下订单状态，商户自行增删操作
	            $this->log_result($this->logfile, "【return_code返回值为fail】:\n". serialize($notify->data) ."\n");
	            //更新订单数据【通信出错】设为无效订单
	            
	            // 返回状态码
	            $notify->setReturnParameter("return_code","FAIL");
	            // 返回信息
	            $notify->setReturnParameter("return_msg","签名失败");
	        } else if($notify->data["result_code"] == "FAIL"){
          
	            // 返回状态码
	            $notify->setReturnParameter("return_code","FAIL");
	            // 返回信息
	            $notify->setReturnParameter("return_msg","签名失败");
	        } else{
	            	            
	            //订单号
	            $order_id         = $notify->data['out_trade_no'];
	            //微信交易号
	            $transaction_id   = $notify->data['transaction_id'];
	            
	            //交易金额，单位分,转化为元
	            $price            = (int) $notify->data['total_fee'] / 100;
	            
	            //交易完成时间
	            $finish_time      = $notify->data['time_end'];
	            $finish_time      = strtotime($finish_time);
	            
                $logmsg = "【支付成功】:\n 订单SN：{$order_id}\n";
	            //验证订单合法性
	            if(!$order_id) {
	                $this->error[] = '缺少必要参数,订单号';
                    $this->writeError();
	            }
	            
                $order_info = getOrderInfo($order_id);
	            if(!$order_info) {
	                $this->error[] = '该订单('.$order_id.')不存在';
                    $this->writeError();
	            }
	            $logmsg .= " 订单信息：\n".serialize($order_info)."\n";
	            // 订单已取消
	            if($order_info['order_status'] == 20) {
	                $this->error[] =  '该订单('.$order_id.')已取消';
                    $this->writeError();
	            }
	            
	            // 已经支付，无需再次支付
	            if($order_info['order_status'] == 5) {
	                $this->error[] =  '该订单('.$order_id.')已经支付';
                    $this->writeError();
	            }
	            
	            // 订单被删除
	            if($order_info['order_status'] == -1) {
	                $this->error[] =  '该订单('.$order_id.')已关闭';
                    $this->writeError();
	            }
	            
	            // 更新订单状态为已付款，未发货
	            $order_status = 5;
                
	            //改变订单状态
                $orderflg = changeOrderStatus($order_id,$transaction_id,$order_status,$finish_time);
	            if($orderflg){
                    $logmsg .= " 订单设置为“已付款”\n";
                }
                                
                //如果不是充值，在不增加账户资金
                if($order_info['type'] != 2 ){
                    //充值过程，改变用户资金
                    $aflg = optAmount($price,$order_info['other'],$order_info['userid'],1,$order_info['ordersn']);
                    if($aflg === true){
                        $logmsg .= "已经记录用户(".$order_info['username']." ID:".$order_info['userid'].")充值信息，充值金额：$price 元"
                            . " 订单号信息:".$order_info['other'];
                    }else{
                        $this->error[] = "用户(".$order_info['username']." ID:".$order_info['userid'].")充值：$price 元,但未成功加入账户余额。\n" .$aflg;
                        $this->writeError();
                    }
                }else{
                    //order_info['type] == 2 是购买vip套餐
                    //购买vip套餐日志
                    AmountLog($order_info['userid'], $order_info['username'],2,$order_id,-$price,$order_info['other']);
                    
                    service("Passport")->updateVip($order_info['userid'],0,$price);
                    $logmsg .= "已经记录日志，并修改用户对应VIP等级\n";
                }
                
	            //开始记录日志
	            $this->log_result($this->logfile,$logmsg );
                
	            $notify->setReturnParameter("return_code","SUCCESS");
	        }
	    } else {
	        // 返回状态码
	        $notify->setReturnParameter("return_code","FAIL");
	        // 返回信息
	        $notify->setReturnParameter("return_msg","签名失败");
	    }
        
        //最后记录错误信息！
        $this->writeError();
	}

	
	// 打印log
	public function log_result($file,$word)
	{
	    $fp = fopen($file,"a");
	    flock($fp, LOCK_EX) ;
	    fwrite($fp,"执行日期：".strftime("%Y-%m-%d %H:%M:%S",time())."\n".$word."\n\n");
	    flock($fp, LOCK_UN);
	    fclose($fp);
	}
    
    private function writeError(){
        if(!empty($this->error)){
            $msg  = "[错误信息]\n";
            $msg .= implode("\n",  $this->error);
            $msg .= "\n---------------------------------------------\n\n";

            $this->log_result($this->errfile, $msg);
            exit;
        }
    }
}
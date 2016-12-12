<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\member\controller;

class GetVip extends Memberbase {
    
    public function index(){
        $viplist = M("member_viptype")->where("status=1")->select();
                
        $this->assign("viplist", $viplist);
        $this->display("getvip");
    }
    
    public function updateVip(){
        //获取充值金额
	    $price = input('price', 0,"floatval");
        $vipid = input("vipid",0,"intval");
	    $price = round($price,2);
	    if($price<=0) {
	        $this->error('请选择VIP套餐！');
	    }
        //获取用户信息
        $userinfo = service("Passport")->getInfo();
        if(empty($userinfo)){
            $this->error('您还未登陆！', url("member/Index/login"));
        }
        
        $addonPath = PROJECT_PATH . "Addon/Wxpay/";
        require_once($addonPath."WxPayPubHelper/WxPayPubHelper.php");
        
        $iswx = is_weixin();
        if ($iswx)
        {
            //使用jsapi接口
            $jsApi = new \JsApi_pub();

            //=========步骤1：网页授权获取用户openid============
            //通过code获得openid
            if (!isset($_GET['code']))
            {
                //触发微信返回code码
                $url = $jsApi->createOauthUrlForCode( url('',"price=$price&vipid=$vipid"),true);
                header("Location:$url");
                exit;
            }else
            {
                //获取code码，以获取openid
                $code = $_GET['code'];
                $jsApi->setCode($code);
                $openid = $jsApi->getOpenId();
            }

            if(empty($openid)){
                $this->error("未获得支付必要参数:'openid'");
            }
            
        }
        
	    //使用统一支付接口，获取prepay_id
	    $unifiedOrder = new \UnifiedOrder_pub();
	    //设置统一支付接口参数
	    
	    //设置必填参数
	    $total_fee = $price * 100;
	    
        //创建订单号
        $ordersn = "PD" . date("YmdHis");
        
	    $body = "订单号:".$ordersn;
        if ($iswx)
        {
            $unifiedOrder->setParameter("openid", "$openid"); //用户标识
            $tradetype = "JSAPI";
        }
        else
        {
            $tradetype = "NATIVE";
        }
	    $unifiedOrder->setParameter("body", $body);//商品描述
	    //自定义订单号，此处仅作举例
	    $unifiedOrder->setParameter("out_trade_no", $ordersn);//商户订单号
	    $unifiedOrder->setParameter("total_fee", $total_fee);//总金额
	    //$unifiedOrder->setParameter("attach", "order_sn={$res['order_sn']}");//附加数据
	    $unifiedOrder->setParameter("notify_url", \WxPayConf_pub::NOTIFY_URL);//通知地址
	    $unifiedOrder->setParameter("trade_type", $tradetype);//交易类型
        if ($iswx)
        {
            //如果是在微信中支付，必须获取id
            $prepay_id = $unifiedOrder->getPrepayId();
            //通过prepay_id获取调起微信支付所需的参数
            $jsApi->setPrepayId($prepay_id);
            $jsApiParameters = $jsApi->getParameters();
            //创建本地订单
            $orderinfo = CreatOrder(
                $userinfo['userid'], 
                $userinfo['username'], 
                2,
                $price, 
                1, 
                "成为VIP", 
                "购买VIP套餐“".getVipRank($vipid,"typename")."” $price 元",
                $ordersn
            );
            if(empty($orderinfo)){
                $this->error("保存订单失败！");
            }
            $this->assign("jsApiParameters", $jsApiParameters);
            
        }else{
            
            $errormsg = "";
            //如果是网页获取统一支付接口结果
            $unifiedOrderResult = $unifiedOrder->getResult();
            //商户根据实际情况设置相应的处理流程
            if ($unifiedOrderResult["return_code"] == "FAIL")
            {
                //商户自行增加处理流程
                $errormsg .= "通信出错：" . $unifiedOrderResult['return_msg'] . "<br>";
            }
            elseif ($unifiedOrderResult["result_code"] == "FAIL")
            {
                //商户自行增加处理流程
                $errormsg .= "错误代码：" . $unifiedOrderResult['err_code'] . "<br>";
                $errormsg .= "错误代码描述：" . $unifiedOrderResult['err_code_des'] . "<br>";
            }
            elseif ($unifiedOrderResult["code_url"] != NULL)
            {
                //从统一支付接口获取到code_url
                $code_url = $unifiedOrderResult["code_url"];
                //商户自行增加处理流程
                //创建订单，获取订单id及ordersn
                $orderinfo = CreatOrder(
                    $userinfo['userid'], $userinfo['username'], 1, $price, 1, 
                    "账户充值", 
                    "购买VIP套餐“".getVipRank($vipid,"typename")."” $price 元", 
                    $ordersn
                );
            }
            if (!empty($errormsg))
            {
                $this->error($errormsg);
            }
            $this->assign('code_url', $code_url);
            $this->assign('unifiedOrderResult', $unifiedOrderResult);
        }
        $this->assign("ordersn",$orderinfo['ordersn']);
        $this->assign("price",$price);
        $this->assign("vipid",$vipid);
        $this->assign("iswx", $iswx);
        $this->display("updatevip");
    }

    public function doupdate(){
        $ordersn = input("get.ordersn");
        $vipid   = input("get.vipid");
        
        if(empty($ordersn)){
            $this->error("订单号错误！");
        }
        
        $orderinfo = getOrderInfo($ordersn);
        
        //改变订单状态为完成
        changeOrderStatus($ordersn, "", 6);
        
        $vipinfo = getVipRank($vipid);
        $timeadd =array(
            1=>"+1 month",
            2=>"+2 month",
            3=>"+3 month",
            4=>"+6 month",
            5=>"+1 year"
        );
        
        date_default_timezone_set('Asia/Shanghai');
        $t = time();
        $endtime = strtotime($timeadd[$vipinfo['starttime']],$t);
        
        //print_r($orderinfo);
        $this->assign("order",$orderinfo);
        $this->assign("vipname",$vipinfo['typename']);
        $this->assign("endtime",$endtime);
        $this->display();
    }
    
    //查询订单
    public function orderQuery() {
        $addonPath = PROJECT_PATH . "Addon/Wxpay/";
        require_once($addonPath . "WxPayPubHelper/WxPayPubHelper.php");

        $out_trade_no = input("ordersn");
        //退款的订单号
        if (!isset($out_trade_no))
        {
            $this->ajaxReturn(array("status" => "error", "msg" => "订单号未知"));
        }

        //使用订单查询接口
        $orderQuery = new \OrderQuery_pub();
        //设置必填参数
        //appid已填,商户无需重复填写
        //mch_id已填,商户无需重复填写
        //noncestr已填,商户无需重复填写
        //sign已填,商户无需重复填写
        $orderQuery->setParameter("out_trade_no", "$out_trade_no"); //商户订单号 
        //非必填参数，商户可根据实际情况选填
        //$orderQuery->setParameter("sub_mch_id","XXXX");//子商户号  
        //$orderQuery->setParameter("transaction_id","XXXX");//微信订单号
        //获取订单查询结果
        $orderQueryResult = $orderQuery->getResult();

        //商户根据实际情况设置相应的处理流程,此处仅作举例
        if ($orderQueryResult["return_code"] == "FAIL")
        {
            $this->ajaxReturn(array("status" => "error", "msg" => "$out_trade_no"));
        }
        elseif ($orderQueryResult["result_code"] == "FAIL")
        {
            $this->ajaxReturn(array("status" => "error", "msg" => "$out_trade_no"));
        }
        else
        {
            $i = $_SESSION['i'];
            $i--;
            $_SESSION['i'] = $i;
            if ($_SESSION['i'] > 1)
            {
                if($orderQueryResult["trade_state"] == "SUCCESS"){
                    $this->ajaxReturn(array("status" => "ok", "msg" => "支付成功"));
                }else{
                    $this->ajaxReturn(array("status" => "error", "msg" => "订单超时时间：" . $i . "秒<br>".$orderQueryResult["trade_state_desc"]));
                }
            }else{
                $this->ajaxReturn(array("status" => "error", "msg" => "订单超时时间：" . $i . "秒", "url"=> url("index")));
            }
        }
    }
}
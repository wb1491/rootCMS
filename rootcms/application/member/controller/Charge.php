<?php

// +----------------------------------------------------------------------
// | rootCMS 会员充值及余额支付
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wb1491 <wb1491@gmail.com>
// +----------------------------------------------------------------------

namespace app\member\controller;

class Charge extends Memberbase {

    public function index() {
        //显示收费界面
        $this->display();
    }

    /**
     * 充值过程
     */
    public function docharge() {
        //获取充值金额
        $price = input('price', 0, "floatval");
        $price = round($price, 2);
        if ($price <= 0)
        {
            $this->error('充值金额不正确！');
        }
        //获取当前用户信息
        $userinfo = service("Passport")->getInfo();
        if (empty($userinfo))
        {
            $this->error('您还未登陆！',  url("member/Index/login"));
        }

        $addonPath = PROJECT_PATH . "Addon/Wxpay/";
        require_once($addonPath . "WxPayPubHelper/WxPayPubHelper.php");

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
                $url = $jsApi->createOauthUrlForCode( url('', "price=$price"), true);
                header("Location:{$url}");
                exit;
            }
            else
            {
                //获取code码，以获取openid
                $code = $_GET['code'];
                $jsApi->setCode($code);
                $openid = $jsApi->getOpenId();
            }
        }
        //创建订单号
        $ordersn = "PD" . date("YmdHis");

        //使用统一支付接口，获取prepay_id
        $unifiedOrder = new \UnifiedOrder_pub();
        //设置统一支付接口参数
        //设置必填参数 $total_fee 的单位为分 要传过来参数是元
        $total_fee = $price * 100;

        $body = "订单编号" . $ordersn;
        if ($iswx)
        {
            $unifiedOrder->setParameter("openid", "$openid"); //用户标识
            $tradetype = "JSAPI";
        }
        else
        {
            $tradetype = "NATIVE";
        }
        $unifiedOrder->setParameter("body", $body); //商品描述
        //自定义订单号，此处仅作举例
        $unifiedOrder->setParameter("out_trade_no", $ordersn); //商户订单号
        $unifiedOrder->setParameter("total_fee", $total_fee); //总金额
        //$unifiedOrder->setParameter("attach", "order_sn={$res['order_sn']}");//附加数据
        $unifiedOrder->setParameter("notify_url", \WxPayConf_pub::NOTIFY_URL); //通知地址
        $unifiedOrder->setParameter("trade_type", $tradetype); //交易类型
        if ($iswx)
        {
            $prepay_id = $unifiedOrder->getPrepayId();
            //通过prepay_id获取调起微信支付所需的参数
            $jsApi->setPrepayId($prepay_id);
            $jsApiParameters = $jsApi->getParameters();

            $wxconf = json_decode($jsApiParameters, true);
            if ($wxconf['package'] == 'prepay_id=')
            {
                $this->error('当前充值订单存在异常，不能使用支付');
            }

            //创建订单，获取订单id及ordersn
            $orderinfo = CreatOrder(
                $userinfo['userid'], $userinfo['username'], 1, $price, 1, "账户充值", "为账户充值 $price 元", $ordersn
            );

            $this->assign("jsApiParamenters", $jsApiParameters);
        }
        else
        {

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
                    $userinfo['userid'], $userinfo['username'], 1, $price, 1, "账户充值", "为账户充值 $price 元", $ordersn
                );
            }
            if (!empty($errormsg))
            {
                $this->error($errormsg);
            }
            $this->assign('code_url', $code_url);
            $this->assign('unifiedOrderResult', $unifiedOrderResult);
        }
        $this->assign("ordersn", $ordersn);
        $this->assign("price", $price);
        $this->assign("iswx", $iswx);

        $this->display();
    }

    /**
     * 充值成功后
     */
    public function charged() {
        $ordersn = input("get.ordersn");
        $price = input("get.price");

//        $orderinfo = getOrderInfo($ordersn);
//        //如果订单状态不是已付款
//        if($orderinfo['order_status'] != 5){
//            //操作 用户资金 + 充值金额
//            optAmount($price,$orderinfo['other'],$orderinfo['userid'],1,$orderinfo['ordersn']);
//            $orderinfo['order_status'] = 5;
//            $wxsn = date("YmdHis");
//            //给变订单状态为已付款
//            changeOrderStatus($ordersn, $wxsn, 5, time());
//        }
        //改变订单状态为完成
        changeOrderStatus($ordersn, "", 6);

        $returl = cookie("site_returl");
        if (!empty($returl))
        {
            $this->assign("returl", base64_decode($returl));
        }
        $this->assign("price", $price);
        $this->display();
    }

    /**
     * 余额支付付费内容
     */
    public function collection() {
        $catid = input('post.catid', 0, 'intval');
        $cid = input('post.id', 0, 'intval');
        $returl = input("post.returl", '');

        //如果内容已经付过费了则提示
        if (getViewedContent($this->userid, $catid, $cid))
        {
            $this->ajaxReturn(array("status" => 0, "msg" => "您已经购买过此内容不需要重复购买", "returl" => $returl));
        }

        //获取当前栏目数据
        $category = getCategory($catid);
        if (empty($category))
        {
            $this->ajaxReturn(array("status" => 0, "msg" => "未找到页面"));
        }
        //反序列化栏目配置
        $category['setting'] = $category['setting'];
        //检查是否禁止访问动态页
        if ($category['setting']['showoffmoving'])
        {
            $this->ajaxReturn(array("status" => 0, "msg" => "禁止访问"));
        }

        $price = $category['setting']['price'];
        if ($this->userinfo['amount'] < $price)
        {
            $this->ajaxReturn(array("status" => 0, "msg" => "用户余额不足"));
        }

        $modelid = $category['modelid'];
        $data = \Content\Model\ContentModel::getInstance($modelid)->relation(true)
                ->where(array("id" => $cid, 'status' => 99))->select();
        if (empty($data))
        {
            $this->ajaxReturn(array("status" => 0, "msg" => "未找到内容"));
        }
        $title = $data[0]['title'];
        if ($price >= 0)
        {
            //生成付费订单
            $order = CreatOrder($this->userid, $this->username, 3, $price, 1, "查看付费内容", "查看“$title”付费内容！");
            //操作资金
            $flg = optAmount(-$price, "查看“" . $title . "”付费内容！", $this->userid, 3, $order['ordersn'], $catid, $cid, $title);
            if ($flg)
            {
                //直接修改订单状态为：交易完成
                changeOrderStatus($order['ordersn'], "VED" . date("YmdHis"), 6, time());
                $this->ajaxReturn(array("status" => 1, "msg" => "支付成功！", "returl" => $returl));
            }
        }
        $this->ajaxReturn(array("status" => 0, "msg" => "未知错误"));
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

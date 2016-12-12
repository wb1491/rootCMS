<?php

// +----------------------------------------------------------------------
// | rootCMS 支付宝交易模型
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wb23 <wb1491@gmail.com>
// +----------------------------------------------------------------------

namespace Addon\Alipay\Model;

use Common\Model\Model;

class AlipayModel extends Model {

    //支付宝配置
    protected $alipayConfig = array();
    //HTTPS形式消息验证地址
    protected $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
    //HTTP形式消息验证地址
    protected $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';
    //支付宝支付网关
    protected $http_gateway_url = 'https://mapi.alipay.com/gateway.do?';
    //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
    protected $_validate = array(
        array('userid', 'require', '用户ID不能为空！'),
        array('username', 'require', '用户ID不能为空！'),
        //array('tradeno', 'require', '支付宝交易号不能为空！'),
        array('price', 'require', '交易金额不能为空！'),
        array('quantity', 'require', '交易数量不能为空！'),
        array('logisticsfee', 'require', '物流费用不能为空！'),
        array('logisticstype', 'require', '物流类型不能为空！'),
        array('logisticspayment', 'require', '物流支付方式不能为空！'),
        array('subject', 'require', '交易名称不能为空！'),
    );
    //array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array(
        array('createtime', 'time', 1, 'function'),
        array('lasttime', 'time', 1, 'function'),
        array('lasttime', 'time', 2, 'function'),
        array('other', 'serialize', 1, 'function'),
        array('other', 'serialize', 2, 'function'),
        array('logisticstype', 'getLogisticsTypeId', 1, 'callback'),
        array('logisticspayment', 'getLogisticsPaymentId', 1, 'callback'),
    );

    //快捷获取对象
    static public function getInstance() {
        static $_instance = NULL;
        if (is_null($_instance)) {
            $_instance = new \Addon\Alipay\Model\AlipayModel();
        }
        $addons = cache('Addons');
        $addonInfo = $addons['Alipay'];
        $_instance->setConfig($addonInfo['config']);
        return $_instance;
    }

    //初始化
    public function setConfig($data) {
        //合作身份者id
        $this->alipayConfig['partner'] = $data['partner'];
        //安全检验码
        $this->alipayConfig['key'] = $data['key'];
        //买方支付宝
        $this->alipayConfig['seller_email'] = $data['seller_email'];
        //签名方式 不需修改
        $this->alipayConfig['sign_type'] = strtoupper('MD5');
        //字符编码格式 目前支持 gbk 或 utf-8
        $this->alipayConfig['input_charset'] = strtolower('utf-8');
        //ca证书路径地址，用于curl中ssl校验
        $this->alipayConfig['cacert'] = PROJECT_PATH . 'Addon/Alipay/Data/cacert.pem';
        //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        $this->alipayConfig['transport'] = 'http';
    }

    /**
     * 改变支付订单状态
     * @param type $out_trade_no 订单号
     * @param type $trade_status 支付宝状态码
     * @return boolean
     */
    public function saveTradeStatus($out_trade_no, $trade_status) {
        if (empty($out_trade_no) || empty($trade_status)) {
            return false;
        }
        //取得状态码对应的状态ID
        $tradestatus = $this->getTradeStatus($trade_status);
        if (is_null($tradestatus) || $tradestatus === false) {
            return false;
        }
        //更新
        $status = $this->where(array('id' => $out_trade_no))->save(array(
            'tradestatus' => $tradestatus,
            'lasttime' => time(),
        ));
        if ($status === false) {
            return false;
        }
        return $tradestatus;
    }

    /**
     * 添加操作日志
     * @param type $orderid 订单号
     * @param type $log 日志信息
     * @param type $system 是否系统操作，比如执行发货/充值，属于系统操作
     * @return boolean
     */
    public function log($orderid, $subject, $log, $system = 0) {
        if (empty($orderid) || empty($log)) {
            return false;
        }
        $db = M('AlipayLog');
        $id = $db->add(array(
            'orderid' => $orderid,
            'system' => $system,
            'subject' => $subject,
            'log' => $log,
            'createtime' => time(),
        ));
        return $id ? $id : false;
    }

    /**
     * 添加一条订单记录
     * @param type $data
     */
    public function createOrder($data) {
        if (empty($data)) {
            $this->error = '参数不能为空！';
            return false;
        }
        //创建合法数据
        $data = $this->create($data, 1);
        if ($data) {
            $id = $this->add($data);
            if ($id) {
                return $id;
            } else {
                $this->error = '订单创建失败！';
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 获取物流类型ID
     * @param type $logistics_type 三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
     * @return int
     */
    public function getLogisticsTypeId($logistics_type, $array_flip = false) {
        if (empty($logistics_type)) {
            return 0;
        }
        $logisticsType = array(
            'express' => 1,
            'post' => 2,
            'ems' => 3,
        );
        if ($array_flip) {
            //反转数组
            $logisticsType = array_flip($logisticsType);
        } else {
            $logistics_type = strtolower($logistics_type);
        }
        if (!isset($logisticsType[$logistics_type])) {
            return 0;
        }
        return $logisticsType[$logistics_type];
    }

    /**
     * 获取物流支付方式ID
     * @param type $logisticspayment 两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）
     * @return int
     */
    public function getLogisticsPaymentId($logisticspayment, $array_flip = false) {
        if (empty($logisticspayment)) {
            return 0;
        }
        $logisticsPaymen = array(
            'seller_pay' => 1,
            'buyer_pay' => 2,
        );
        if ($array_flip) {
            //反转数据
            $logisticsPaymen = array_flip($logisticsPaymen);
        } else {
            $logisticspayment = strtolower($logisticspayment);
        }
        if (!isset($logisticsPaymen[$logisticspayment])) {
            return 0;
        }
        return $logisticsPaymen[$logisticspayment];
    }

    /**
     * 确认发货
     * @param type $trade_no 支付宝交易号
     */
    public function confirmDelivery($trade_no) {
        //构造要请求的参数数组，无需改动
        $data = array(
            "service" => "send_goods_confirm_by_platform",
            "partner" => trim($this->alipayConfig['partner']),
            "trade_no" => $trade_no,
            "logistics_name" => '顺丰快递',
            "invoice_no" => '',
            "transport_type" => 'DIRECT',
            "_input_charset" => trim(strtolower($this->alipayConfig['input_charset']))
        );

        unset($data['sign'], $data['sign_type']);
        //去除空值
        $parameter = array_filter($data, create_function('$v', 'return !empty($v);'));
        //对数组排序
        ksort($parameter);
        reset($parameter);
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = "";
        foreach ($parameter as $key => $val) {
            $prestr.=$key . "=" . $val . "&";
        }
        //去掉最后一个&字符
        $prestr = substr($prestr, 0, count($prestr) - 2);
        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $prestr = stripslashes($prestr);
        }
        //生成签名结果
        $mysign = "";
        switch (strtoupper(trim($this->alipayConfig['sign_type']))) {
            case "MD5" :
                $mysign = md5($prestr . $this->alipayConfig['key']);
                break;
            default :
                $mysign = "";
        }
        //签名结果与签名方式加入请求提交参数组中
        $parameter['sign'] = $mysign;
        $parameter['sign_type'] = strtoupper(trim($this->alipayConfig['sign_type']));
        //发送post请求
        $url = $this->http_gateway_url . "_input_charset=" . trim(strtolower($this->alipayConfig['input_charset']));
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true); //SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); //严格认证
        curl_setopt($curl, CURLOPT_CAINFO, $this->alipayConfig['cacert']); //证书地址
        curl_setopt($curl, CURLOPT_HEADER, 0); // 过滤HTTP头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 显示输出结果
        curl_setopt($curl, CURLOPT_POST, true); // post传输数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameter); // post传输数据
        $responseText = curl_exec($curl);
        curl_close($curl);
        $doc = new \DOMDocument();
        $doc->loadXML($responseText);
        //请求是否成功is_success 
        $success = $doc->getElementsByTagName("is_success")->item(0)->nodeValue;
        //获取错误代码 error 
        $errorcode = $doc->getElementsByTagName("error")->item(0)->nodeValue;
        //支付宝交易号
        $trade_no = $doc->getElementsByTagName("trade_no")->item(0)->nodeValue;
        //交易状态
        $trade_status = $doc->getElementsByTagName("trade_status")->item(0)->nodeValue;
        //操作时间
        $send_time = $doc->getElementsByTagName("last_modified_time")->item(0)->nodeValue;
        //商家网站唯一订单号
        $out_trade_no = $doc->getElementsByTagName("out_trade_no")->item(0)->nodeValue;
        return array(
            'success' => $success,
            'errorcode' => $errorcode,
            'trade_no' => $trade_no,
            'trade_status' => $trade_status,
            'send_time' => $send_time,
            'out_trade_no' => $out_trade_no,
        );
    }

    /**
     * 根据支付宝状态，返回状态编号
     * @param type $trade_status 支付宝状态
     * @param type $array_flip 是否反过来获取支付宝编号
     * @return int
     */
    public function getTradeStatus($trade_status, $array_flip = false) {
        if (empty($trade_status)) {
            return NULL;
        }
        $trade = array(
            'TRADE_FINISHED' => 1, //交易成功结束 
            'WAIT_BUYER_PAY' => -1, //等待买家付款
            'WAIT_SELLER_SEND_GOODS' => -2, //买家已付款，等待卖家发货 
            'WAIT_BUYER_CONFIRM_GOODS' => -3, //卖家已发货，等待买家确认 
            'TRADE_CLOSED' => -4, //交易中途关闭（已结束，未成功完成） 
        );
        if ($array_flip) {
            //反转数组
            $trade = array_flip($trade);
        }
        return $trade[$trade_status] ? $trade[$trade_status] : NULL;
    }

    /**
     * 返回状态名称
     * @param type $statusId 状态Id
     * @return type
     */
    public function getTradeStatusName($statusId) {
        $trade = array(
            0 => '未完成订单',
            1 => '交易成功', //交易成功结束 
            -1 => '等待付款', //等待买家付款
            -2 => '买家已付款，等待卖家发货', //买家已付款，等待卖家发货 
            -3 => '卖家已发货，等待买家确认', //卖家已发货，等待买家确认 
            -4 => '交易关闭', //交易中途关闭（已结束，未成功完成） 
        );
        return $trade[$statusId] ? : NULL;
    }

    /**
     * 取得支付收款处理页面地址
     * @param type $id 订单ID
     * @return boolean
     */
    public function gateway($id) {
        if (empty($id)) {
            $this->error = '参数错误！';
            return false;
        }
        //查询出订单
        $info = $this->where(array('id' => $id))->find();
        if (empty($info)) {
            $this->error = '订单不存在！';
            return false;
        }
        //其他参数
        $other = unserialize($info['other']);
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "trade_create_by_buyer",
            "partner" => trim($this->alipayConfig['partner']),
            //支付类型 必填，不能修改
            "payment_type" => '1',
            //服务器异步通知页面路径 需http://格式的完整路径，不能加?id=123这类自定义参数
            "notify_url" => cache('Config.siteurl') . 'alipay.php', //cache('Config.siteurl')
            //页面跳转同步通知页面路径 需http://格式的完整路径，不能加?id=123这类自定义参数
            "return_url" => cache('Config.siteurl') . 'alipay.php',
            //卖家支付宝帐户 必填
            "seller_email" => $this->alipayConfig['seller_email'],
            //商户订单号 必填
            "out_trade_no" => $info['id'],
            //订单名称 必填
            "subject" => $info['subject'],
            //付款金额 必填
            "price" => $info['price'],
            //商品数量 必填，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品
            "quantity" => $info['quantity'],
            //物流费用 必填
            "logistics_fee" => $info['logisticsfee']? : 0,
            //物流类型 必填，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
            "logistics_type" => strtoupper($this->getLogisticsTypeId($info['logisticstype'], true)),
            //物流支付方式 必填，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）
            "logistics_payment" => strtoupper($this->getLogisticsPaymentId($info['logisticspayment'], true)),
            //订单描述
            "body" => trim($other['body']),
            //商品展示地址 需以http://开头的完整路径
            "show_url" => $other['show_url'],
            //收货人姓名
            "receive_name" => $other['receive_name'],
            //收货人地址
            "receive_address" => $other['receive_address'],
            //收货人邮编
            "receive_zip" => $other['receive_zip'],
            //收货人电话号码
            "receive_phone" => $other['receive_phone'],
            //收货人手机号码
            "receive_mobile" => $other['receive_mobile'],
            "_input_charset" => trim(strtolower($this->alipayConfig['input_charset']))
        );
        //返回支付宝地址
        return $this->http_gateway_url . http_build_query($this->buildRequestPara($parameter));
    }

    /**
     * 生成要请求给支付宝的参数数组
     * @param type $parameter 参数，数组
     * @return boolean
     */
    private function buildRequestPara($parameter) {
        if (empty($parameter) || !is_array($parameter)) {
            return false;
        }
        unset($parameter['sign'], $parameter['sign_type']);
        //去除空值
        $parameter = array_filter($parameter, create_function('$v', 'return !empty($v);'));
        //对数组排序
        ksort($parameter);
        reset($parameter);
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = "";
        foreach ($parameter as $key => $val) {
            $prestr.=$key . "=" . $val . "&";
        }
        //去掉最后一个&字符
        $prestr = substr($prestr, 0, count($prestr) - 2);
        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $prestr = stripslashes($prestr);
        }
        //生成签名
        $mysign = "";
        switch (strtoupper(trim($this->alipayConfig['sign_type']))) {
            case "MD5" :
                $mysign = md5($prestr . $this->alipayConfig['key']);
                break;
            default :
                $mysign = "";
        }
        //签名结果与签名方式加入请求提交参数组中
        $parameter['sign'] = $mysign;
        $parameter['sign_type'] = strtoupper(trim($this->alipayConfig['sign_type']));
        return $parameter;
    }

    /**
     * 针对return_url验证消息是否是支付宝发出的合法消息
     * @return boolean  合法true，非法false
     */
    public function verifyReturn($get) {
        //判断get是否有参数！
        if (empty($get) || !is_array($get)) {
            return false;
        }
        //去除TP带上的干扰参数
        unset($get[C('VAR_URL_PARAMS')]);
        //支付宝返回的签名
        $sign = $get['sign'];
        //除去待签名参数数组中的空值和签名参数
        unset($get['sign'], $get['sign_type']);
        //去除空值
        $get = array_filter($get, create_function('$v', 'return !empty($v);'));
        //对待签名参数数组排序
        ksort($get);
        reset($get);
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = "";
        foreach ($get as $key => $val) {
            $prestr.=$key . "=" . $val . "&";
        }
        //去掉最后一个&字符
        $prestr = substr($prestr, 0, count($prestr) - 2);
        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $prestr = stripslashes($prestr);
        }
        $isSign = false;
        switch (strtoupper(trim($this->alipayConfig['sign_type']))) {
            case "MD5" :
                $isSign = md5($prestr . $this->alipayConfig['key']) == $sign ? true : false;
                break;
            default :
                $isSign = false;
        }
        //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
        $responseTxt = 'false';
        if (!empty($get["notify_id"])) {
            $transport = strtolower(trim($this->alipayConfig['transport']));
            $partner = trim($this->alipayConfig['partner']);
            $veryfy_url = '';
            if ($transport == 'https') {
                $veryfy_url = $this->https_verify_url;
            } else {
                $veryfy_url = $this->http_verify_url;
            }
            $veryfy_url = $veryfy_url . "partner=" . $partner . "&notify_id=" . $get["notify_id"];
            // 远程获取数据，GET模式
            $curl = curl_init($veryfy_url);
            curl_setopt($curl, CURLOPT_HEADER, 0); // 过滤HTTP头
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 显示输出结果
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true); //SSL证书认证
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); //严格认证
            curl_setopt($curl, CURLOPT_CAINFO, $this->alipayConfig['cacert']); //证书地址
            $responseTxt = curl_exec($curl);
            curl_close($curl);
        }
        //验证
        //$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
        //isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
        if (preg_match("/true$/i", $responseTxt) && $isSign) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 针对Notify验证消息是否是支付宝发出的合法消息
     * @return boolean  合法true，非法false
     */
    public function verifyNotify($post) {
        //判断post是否有参数！
        if (empty($post) || !is_array($post)) {
            return false;
        }
        //支付宝返回的签名
        $sign = $post['sign'];
        //除去待签名参数数组中的空值和签名参数
        unset($post['sign'], $post['sign_type']);
        //去除空值
        $post = array_filter($post, create_function('$v', 'return !empty($v);'));
        //对待签名参数数组排序
        ksort($post);
        reset($post);
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = "";
        foreach ($post as $key => $val) {
            $prestr.=$key . "=" . $val . "&";
        }
        //去掉最后一个&字符
        $prestr = substr($prestr, 0, count($prestr) - 2);
        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $prestr = stripslashes($prestr);
        }
        $isSign = false;
        switch (strtoupper(trim($this->alipayConfig['sign_type']))) {
            case "MD5" :
                $isSign = md5($prestr . $this->alipayConfig['key']) == $sign ? true : false;
                break;
            default :
                $isSign = false;
        }
        //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
        $responseTxt = 'false';
        if (!empty($post["notify_id"])) {
            $transport = strtolower(trim($this->alipayConfig['transport']));
            $partner = trim($this->alipayConfig['partner']);
            $veryfy_url = '';
            if ($transport == 'https') {
                $veryfy_url = $this->https_verify_url;
            } else {
                $veryfy_url = $this->http_verify_url;
            }
            $veryfy_url = "{$veryfy_url}partner={$partner}&notify_id={$post["notify_id"]}";
            // 远程获取数据，GET模式
            $curl = curl_init($veryfy_url);
            curl_setopt($curl, CURLOPT_HEADER, 0); // 过滤HTTP头
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 显示输出结果
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true); //SSL证书认证
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); //严格认证
            curl_setopt($curl, CURLOPT_CAINFO, $this->alipayConfig['cacert']); //证书地址
            $responseTxt = curl_exec($curl);
            curl_close($curl);
        }
        //验证
        //$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
        //isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
        if (preg_match("/true$/i", $responseTxt) && $isSign) {
            return true;
        } else {
            return false;
        }
    }

}

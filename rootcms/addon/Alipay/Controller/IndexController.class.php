<?php

// +----------------------------------------------------------------------
// | rootCMS 插件前台管理
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wb23 <wb1491@gmail.com>
// +----------------------------------------------------------------------

namespace Addon\Alipay\Controller;

use Addons\Util\AddonsBase;
use Addon\Alipay\Model\AlipayModel;
use Addon\Alipay\Callback\Callback;

class IndexController extends AddonsBase {

    //数据模型
    private $alipayModel = NULL;

    //初始化
    protected function _initialize() {
        parent::_initialize();
        //数据模型
        $this->alipayModel = new AlipayModel();
        $this->alipayModel->setConfig($this->getAddonConfig());
    }

    //支付宝通知
    public function notice() {
        if (IS_POST) {
            $this->notify();
        } else {
            $this->returnurl();
        }
    }

    //支付界面
    public function index() {
        //支付类型
        $id = input('get.id', 0, 'intval');
        if (!empty($id)) {
            $data = M('AlipayType')->find($id);
            if (empty($data)) {
                $this->error('该支付类型不存在！');
            }
            $this->assign('data', $data);
            $this->display();
        } else {
            $data = array(
                'subject' => input('get.subject'),
                'price' => input('get.price'),
                'remark' => input('get.remark'),
            );
            $this->assign('data', $data);
            $this->display('free');
        }
    }

    //支付处理
    public function pay() {
        if (!IS_POST) {
            $this->error('支付错误！');
        }
        $validate = input('post.validate');
        if (empty($validate)) {
            $this->error('请输入验证码！');
        }
        if ($this->verify($validate, 'alipay') == false) {
            $this->error('验证码错误，请重新输入！');
        }
        //支付类型
        $id = input('post.id', 0, 'intval');
        if (!empty($id)) {
            $info = M('AlipayType')->find($id);
            if (empty($info)) {
                $this->error('该支付类型不存在！');
            }
            //金额
            $money = $info['price'];
            //标题
            $subject = $info['name'];
            //商品地址
            $showurl =  url('index', array('id' => $id));
            //备注
            $remark = $info['remark'];
        } else {
            $subject = input('post.subject', '', 'trim');
            if (empty($subject)) {
                $this->error('标题不能为空！');
            }
            $price = input('post.price', 0);
            if (empty($price)) {
                $this->error('金额不能小于等于0！');
            }
            //金额
            $money = $price;
            //商品地址
            $showurl =  url('index');
            //备注
            $remark = input('post.remark');
        }

        //创建订单
        $data = array(
            'userid' => service("Passport")->userid? : 0,
            'username' => service("Passport")->usename? : '游客',
            'alipay_type' => $id,
            //订单名称 必填
            "subject" => $subject,
            //付款金额 必填
            "price" => round($money, 2),
            //商品数量 必填，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品
            "quantity" => '1',
            //物流费用 必填
            "logisticsfee" => '0.00',
            //物流类型 必填，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
            "logisticstype" => 'EXPRESS',
            //物流支付方式 必填，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）
            "logisticspayment" => 'BUYER_PAY',
            //其他数据
            "other" => array(
                //订单描述
                "body" => trim($subject),
                //商品展示地址 需以http://开头的完整路径
                "showurl" => $showurl,
                //收货人姓名
                "receive_name" => service("Passport")->usename? : '游客',
                //收货人地址
                "receive_address" => '',
                //收货人邮编
                "receive_zip" => '',
                //收货人电话号码
                "receive_phone" => '',
                //收货人手机号码
                "receive_mobile" => '',
                //ip
                "ip" => get_client_ip(),
            ),
        );
        //备注
        if ($remark) {
            $data['other']['remark'] = $remark;
        }
        //创建一比订单
        $orderNumber = $this->alipayModel->createOrder($data);
        if ($orderNumber) {
            //跳到支付宝
            $url = $this->alipayModel->gateway($orderNumber);
            if (false !== $url) {
                redirect($url);
            } else {
                $this->error($this->alipayModel->getError()? : '获取支付宝支付地址失败！');
            }
        } else {
            $this->error($this->alipayModel->getError()? : '订单创建操作失败！');
        }
    }

    //接收 服务器异步通知
    protected function notify() {
        //计算得出通知验证结果
        if ($this->alipayModel->verifyNotify($_POST)) {//验证成功
            $out_trade_no = $_POST['out_trade_no'];
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            //交易状态
            $trade_status = $_POST['trade_status'];
            //查询订单
            $info = $this->alipayModel->where(array('id' => $out_trade_no))->find();
            if (empty($info)) {
                //记录日志
                $this->alipayModel->log($out_trade_no, $out_trade_no . '订单出现错误，订单表没有相应记录！', json_encode($_GET), 1);
                exit('fail');
            }
            //已经交易成功的不处理
            if ($info['tradestatus'] == 1) {
                exit('fail');
            }
            $this->alipayModel->where(array('id' => $out_trade_no))->save(array('tradeno' => $trade_no));
            $info['tradeno'] = $trade_no;
            //更新对应订单状态
            $info['tradestatus'] = $this->alipayModel->saveTradeStatus($out_trade_no, $trade_status);
            switch ($trade_status) {
                case 'WAIT_BUYER_PAY'://等待买家付款
                case 'WAIT_SELLER_SEND_GOODS'://买家已付款，等待卖家发货
                    //自动发货
                    $ship = $this->alipayModel->confirmDelivery($trade_no);
                    if ($ship['success'] == 'T') {//发货成功
                        if ($ship['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS') {
                            //记录日志
                            $this->alipayModel->log($out_trade_no, '系统已经发货！', json_encode($ship), 1);
                        }
                    }
                case 'WAIT_BUYER_CONFIRM_GOODS'://卖家已发货，等待买家确认
                case 'TRADE_CLOSED'://交易中途关闭（已结束，未成功完成）
                    break;
                case 'TRADE_FINISHED'://交易成功结束
                    //进行回调处理
                    if ($info['alipay_type']) {
                        $callback = M('AlipayType')->where(array('id' => $info['alipay_type']))->column('callback');
                        if ($callback) {
                            Callback::getInstance($callback)->data($info)->run();
                        }
                    }
                    break;
            }
            exit('success');
        } else {
            //验证失败
            exit('fail');
        }
    }

    //商户系统请求/支付宝响应交互模式
    //http://help.alipay.com/support/help_detail.htm?help_id=243126&keyword=%CD%A8%D6%AA
    protected function returnurl() {
        $this->assign("waitSecond", 5000);
        //计算得出通知验证结果
        if ($this->alipayModel->verifyReturn($_GET)) {//验证成功
            //商户订单号
            $out_trade_no = $_GET['out_trade_no'];
            //支付宝交易号
            $trade_no = $_GET['trade_no'];
            //交易状态
            $trade_status = $_GET['trade_status'];
            //查询订单
            $info = $this->alipayModel->where(array('id' => $out_trade_no))->find();
            if (empty($info)) {
                //记录日志
                $this->alipayModel->log($out_trade_no, $out_trade_no . '订单出现错误，订单表没有相应记录！', json_encode($_GET), 1);
                $this->error('订单不存在，或者漏单，请联系客服！', cache('Config.siteurl'));
            }
            //已经交易成功的不处理
            if ($info['tradestatus'] == 1) {
                $this->success('该订单已经处理完毕！');
                exit;
            }
            $this->alipayModel->where(array('id' => $out_trade_no))->save(array('tradeno' => $trade_no));
            $info['tradeno'] = $trade_no;
            //更新对应订单状态
            $info['tradestatus'] = $this->alipayModel->saveTradeStatus($out_trade_no, $trade_status);
            switch ($trade_status) {
                case 'WAIT_SELLER_SEND_GOODS'://买家已付款，等待卖家发货 
                    $ship = $this->alipayModel->confirmDelivery($trade_no);
                    if ($ship['success'] == 'T') {//发货成功
                        if ($ship['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS') {
                            //记录日志
                            $this->alipayModel->log($out_trade_no, '系统已经发货！', json_encode($ship), 1);
                            $this->success('已经付款，请先确认收货后系统将进行操作！', cache('Config.siteurl'));
                            exit;
                        }
                    }
                    //记录日志
                    $this->alipayModel->log($out_trade_no, '系统发货失败！', json_encode($ship), 1);
                    $this->success('已经付款，等待系统确认！', cache('Config.siteurl'));
                    exit;
                    break;
                case 'WAIT_BUYER_CONFIRM_GOODS':
                    $this->success('系统已经确认发货，请进入支付宝确认收货后系统将进行操作！', cache('Config.siteurl'));
                    exit;
                    break;
                case 'TRADE_FINISHED'://交易成功结束 
                    //进行回调处理
                    if ($info['alipay_type']) {
                        $callback = M('AlipayType')->where(array('id' => $info['alipay_type']))->column('callback');
                        if ($callback) {
                            Callback::getInstance($callback)->data($info)->run();
                        }
                    }
                    $this->success('交易成功！', cache('Config.siteurl'));
                    exit;
                    break;
                case 'TRADE_CLOSED'://交易中途关闭（已结束，未成功完成）
                    break;
                default :
                    $this->error('出现未知订单状态，请联系客服！', cache('Config.siteurl'));
                    break;
            }
        } else {
            //验证失败
            $this->error('订单出错，请联系客服！', cache('Config.siteurl'));
        }
    }

}

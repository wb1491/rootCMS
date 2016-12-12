<?php

// +----------------------------------------------------------------------
// | rootCMS 插件后台管理
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------
namespace Addon\Wxpay\Controller;

use Addons\Util\Adminaddonbase;

class AdminController extends Adminaddonbase {
    
    public $id = 0;
    public $config = array();

    public function _initialize() {
        parent::_initialize();
        $this->addons =  model('Addons/Addons');
        $name = $this->getAddonName();
        $row  = $this->addons->field("id")->where("name='{$name}'")->select();
        $this->id = $row[0]['id'];
        
        //获取插件配置
        $config = $this->getAddonConfig();
        
        $parse_url = parse_url(CONFIG_SITEURL);
        $config_siteurl = (is_ssl() ? 'https://' : 'http://') . "{$_SERVER['HTTP_HOST']}{$parse_url['path']}";

        //获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
        $config['JS_API_CALL_URL'] = $config_siteurl;
        
        //=======【证书路径设置】=====================================
        //证书路径,注意应该填写绝对路径
        $config['SSLCERT_PATH'] = $this->addonPath .'WxPayPubHelper/cacert/apiclient_cert.pem';
        $config['SSLKEY_PATH']  = $this->addonPath .'WxPayPubHelper/cacert/apiclient_key.pem';

        //=======【异步通知url设置】===================================
        //异步通知url，商户根据实际开发过程设定
        $config['NOTIFY_URL'] = $config['DOMAIN']."wxnotify.php";

        //=======【curl超时设置】===================================
        //本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
        $config['CURL_TIMEOUT'] = 30;
        
        $this->config = $config;

        //-----------------------------------------------------------
        $filename = $this->addonPath."WxPayPubHelper/WxPay.pub.config.php";
        $this->writeConfigArray($filename, $config);
    }

    //插件设置界面
    public function config() {
        if (IS_POST) {
            //插件ID
            $id = $this->id;
            //获取插件信息
            $info = $this->addons->where(array('id' => $id))->find();
            if (empty($info)) {
                $this->error('该插件没有安装！');
            }
            //插件配置
            $config = input('post.config', '', '');
            if (false !== $this->addons->where(array('id' => $id))->save(array('config' => serialize($config)))) {
                //更新附件状态，把相关附件和插件进行关联
                service("Attachment")->api_update('', 'addons-' . $id, 1);
                //更新插件缓存
                $this->addons->addons_cache();
                $this->success('保存成功！',  url('',"isadmin=1"));
            } else {
                $this->error('保存失败！');
            }
        } else {
            //插件名称
            $addonId = $this->id;
            if (empty($addonId)) {
                $this->error('请选择需要操作的插件！');
            }
            //获取插件信息
            $info = $this->addons->where(array('id' => $addonId))->find();
            if (empty($info)) {
                $this->error('该插件没有安装！');
            }
            $info['config'] = unserialize($info['config']);
            //实例化插件入口类
            $addonObj = $this->addons->getAddonObject($info['name']);
            //标题
            $meta_title = '微信支付设置-' . $addonObj->info['title'];
            //载入插件配置数组
            $fileConfig = include $addonObj->configFile;
            if (!empty($info['config'])) {
                foreach ($fileConfig as $key => $form) {
                    //如果已经有保存的值
                    if (isset($info['config'][$key])) {
                        $fileConfig[$key]['value'] = $info['config'][$key];
                    }
                }
            }
            $this->assign('meta_title', $meta_title);
            $this->assign('config', $fileConfig);
            $this->assign('info', $info);
            $this->display();
        }
    }
    
    public function index(){
        //检查是否有参数设置
        $this->checkConfig();
        
        $where = array();
        $start_time = input('post.start_time');
        if ($start_time) {
            $where['createtime'] = array('EGT', strtotime($start_time));
            $this->assign('start_time', $start_time);
            $end_time = input('get.end_time');
            if ($end_time) {
                $where['createtime'] = array(array('EGT', strtotime($start_time)), array('ELT', strtotime($start_time)), 'and');
                $this->assign('end_time', $end_time);
            }
        }
        $tradestatus = input('post.order_status');
        if ($tradestatus != '') {
            $where['order_status'] = $tradestatus;
            $this->assign('order_status', $tradestatus);
        }
        $field = input('post.field');
        $keyword = input('post.keyword');
        if ($field && $keyword) {
            $this->assign('field', $field)->assign('keyword', $keyword);
            switch ($field) {
                case 'ordersn':
                    $where['ordersn'] = trim($keyword);
                    break;
                case 'subject':
                    $where['subject'] = array('LIKE', "%{$keyword}%");
                    break;
                case 'userid':
                    $where['userid'] = (int) $keyword;
                    break;
                case 'username':
                    $where['username'] = trim($keyword);
                    break;
                case 'tradeno':
                    $where['tradeno'] = trim($keyword);
                    break;
            }
        }
        
        $this->basePage(M('order'), $where, array('id' => 'DESC'), 20);
    }
    
    public function amontlog(){
        
        //检查是否有参数设置
        $this->checkConfig();
        
        $where = array();
        $start_time = input('post.start_time');
        if ($start_time) {
            $where['createtime'] = array('EGT', strtotime($start_time));
            $this->assign('start_time', $start_time);
            $end_time = input('get.end_time');
            if ($end_time) {
                $where['createtime'] = array(array('EGT', strtotime($start_time)), array('ELT', strtotime($start_time)), 'and');
                $this->assign('end_time', $end_time);
            }
        }
        $tradestatus = input('post.type');
        if ($tradestatus != '') {
            $where['type'] = $tradestatus;
            $this->assign('type', $tradestatus);
        }
        $field = input('post.field');
        $keyword = input('post.keyword');
        if ($field && $keyword) {
            $this->assign('field', $field)->assign('keyword', $keyword);
            switch ($field) {
                case 'ordersn':
                    $where['ordersn'] = trim($keyword);
                    break;
                case 'msg':
                    $where['msg'] = array('LIKE', "%{$keyword}%");
                    break;
                case 'userid':
                    $where['userid'] = (int) $keyword;
                    break;
                case 'username':
                    $where['username'] = trim($keyword);
                    break;
                case 'title':
                    $where['title'] = array('LIKE', "%{$keyword}%");
                    break;
            }
        }
        
        $this->basePage(M('amountlog'), $where, array('id' => 'DESC'), 20);
    }
    
    private function checkConfig(){
        //获取插件配置
        if (empty($this->config)) {
            $this->error('请先进行相关配置！', url("config","isadmin=1"));
        }
        if (empty($this->config['DOMAIN'])){
            $this->error("认证域名还未设置！", url("config","isadmin=1"));
        }
    }

    /**
     * 将配置数据写入配置文件，配置数组中的key必须与配置文件中的变量名一致
     * @param type $filename 配置文件名称，包含完整路径
     * @param type $config 要写入的配置内容
     */    
    public function writeConfigArray($filename,$config=array()){
        if(!empty($config) && is_array($config)){
            foreach ($config as $k => $v){
                $this->writeConfig($filename, $k, $v);
            }
        }
    }

    public function writeConfig($filename,$key,$value){
        if(!file_exists($filename)){
            exit("配置文件：$filename 无法访问！");
        }
        $content = file_get_contents($filename);
        $pattern = "/$key\s*=\s*([^;]+);/";
        $revalue = "$key = ";
        if(is_numeric($value)){
            $revalue .= $value.";";
        }else{
            $revalue .= "'$value';";
        }
        $content = preg_replace($pattern, $revalue, $content);
       
        file_put_contents($filename, $content);
    }
}


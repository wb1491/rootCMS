<?php

// +----------------------------------------------------------------------
// | rootCMS 后台首页
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\AdminBase;
use app\admin\service\User;

class Index extends AdminBase {

    //后台框架首页
    public function index() {
        if (IS_AJAX) {
            $this->ajaxReturn(array('status' => 1));
            return true;
        }
        $this->assign('userInfo', User::getInstance()->getInfo());
        $this->assign('role_name',  model('admin/Role')->getRoleIdName(User::getInstance()->role_id));
        return $this->display();
    }

    //缓存更新
    public function cache() {
        //throw new Exception;
        $this->display();
    }
    
    public function upcache(){
        $type = input("type");
        if ($type) {
            $Dir = new \util\Dir();
            $cache =  model('common/Cache');
            set_time_limit(0);
            switch ($type) {
                case "site":
                    //开始刷新缓存
                    $stop = input('stop', 0, 'intval');
                    if (empty($stop)) {
                        try {
                            //已经清除过的目录
                            $dirList = explode(',', input('dir', '','base64_decode'));
                            //删除缓存目录下的文件
                            $Dir->del(RUNTIME_PATH);
                            //获取子目录
                            $subdir = glob(RUNTIME_PATH . '*', GLOB_ONLYDIR | GLOB_NOSORT);
                            if (is_array($subdir)) {
                                foreach ($subdir as $path) {
                                    $dirName = str_replace(RUNTIME_PATH, '', $path);
                                    //忽略目录
                                    if (in_array($dirName, array('cache', 'log'))) {
                                        continue;
                                    }
                                    if (in_array($dirName, $dirList)) {
                                        continue;
                                    }
                                    $dirList[] = $dirName;
                                    //删除目录
                                    $Dir->delDir($path);
                                    //防止超时，清理一个从新跳转一次
                                    $this->assign("waitSecond", 200);
                                    $this->success("清理缓存目录[{$dirName}]成功！",  url('admin/Index/upcache', array('type' => 'site', 'dir' => base64_encode(implode(',', $dirList)))),['wait'=>1]);
                                    exit;
                                }
                            }
                            //更新开启其他方式的缓存
                            $cache = new \think\Cache;
                            $cache->clear();
                        } catch (Exception $exc) {
                            
                        }
                    }
                    if ($stop) {
                        $modules = $cache->getCacheList();
                        //需要更新的缓存信息
                        $cacheInfo = $modules[$stop - 1];
                        if ($cacheInfo) {
                            if ($cache->runUpdate($cacheInfo) !== false) {
                                $this->assign("waitSecond", 200);
                                $this->success('更新缓存：' . $cacheInfo['name'],  url('admin/Index/upcache', array('type' => 'site', 'stop' => $stop + 1)),['wait'=>1]);
                                exit;
                            } else {
                                $this->error('缓存[' . $cacheInfo['name'] . ']更新失败！',  url('admin/Index/upcache', array('type' => 'site', 'stop' => $stop + 1)),['wait'=>1]);
                            }
                        } else {
                            $this->success('缓存更新完毕！',  url('admin/Index/cache'));
                            exit;
                        }
                    }
                    $this->success("即将更新站点缓存！",  url('admin/Index/upcache', array('type' => 'site', 'stop' => 1)),['wait'=>5]);
                    break;
                case "template":
                    //删除缓存目录下的文件
                    $Dir->del(RUNTIME_PATH);
                    $Dir->delDir(RUNTIME_PATH . "cache/");
                    $Dir->delDir(RUNTIME_PATH . "temp/");
                    //更新开启其他方式的缓存
                    $cache = new \think\Cache();
                    $cache->clear();
                    $this->success("模板缓存清理成功！",  url('admin/Index/cache'));
                    break;
                case "logs":
                    $Dir->delDir(RUNTIME_PATH . "log/");
                    $this->success("站点日志清理成功！",  url('admin/Index/cache'));
                    break;
                default:
                    $this->error("请选择清楚缓存类型！");
                    break;
            }
        }
    }

    //后台框架首页菜单搜索
    public function public_find() {
        $keyword = input('get.keyword');
        if (!$keyword) {
            $this->error("请输入需要搜索的关键词！");
        }
        $where = array();
        $where['name'] = array("LIKE", "%$keyword%");
        $where['status'] = array("EQ", 1);
        $where['type'] = array("EQ", 1);
        $data = M("Menu")->where($where)->select();
        $menuData = $menuName = array();
        $Module = F("Module");
        foreach ($data as $k => $v) {
            $menuData[ucwords($v['app'])][] = $v;
            $menuName[ucwords($v['app'])] = $Module[ucwords($v['app'])]['name'];
        }
        $this->assign("menuData", $menuData);
        $this->assign("menuName", $menuName);
        $this->assign("keyword", $keyword);
        $this->display();
    }

    public function public_menu(){
        return json(model("admin/Menu")->getMenuList());
    }
}

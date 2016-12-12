<?php

// +----------------------------------------------------------------------
// | rootCMS 插件后台管理
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace Addon\BatchColumn\Controller;

use Addons\Util\Adminaddonbase;

class AdminController extends Adminaddonbase {

    //栏目缓存
    protected $categorys;
    //模型缓存
    protected $model;
    //模板文件夹
    private $filepath;
    //频道模板路径
    protected $tp_category;
    //列表页模板路径
    protected $tp_list;
    //内容也模板路径
    protected $tp_show;
    //评论模板路径
    protected $tp_comment;

    protected function _initialize() {
        parent::_initialize();
        $this->categorys = cache('Category');
        $this->model = cache('Model');
        //取得当前内容模型模板存放目录
        $this->filepath = TEMPLATE_PATH . (empty(self::$Cache["Config"]['theme']) ? "Default" : self::$Cache["Config"]['theme']) . DIRECTORY_SEPARATOR . "Content" . DIRECTORY_SEPARATOR; //
        //取得栏目频道模板列表
        $this->tp_category = str_replace($this->filepath . "Category" . DIRECTORY_SEPARATOR, "", glob($this->filepath . "Category" . DIRECTORY_SEPARATOR . 'category*'));
        //取得栏目列表模板列表
        $this->tp_list = str_replace($this->filepath . "List" . DIRECTORY_SEPARATOR, "", glob($this->filepath . "List" . DIRECTORY_SEPARATOR . 'list*'));
        //取得内容页模板列表
        $this->tp_show = str_replace($this->filepath . "Show" . DIRECTORY_SEPARATOR, "", glob($this->filepath . "Show" . DIRECTORY_SEPARATOR . 'show*'));
        //取得评论模板列表
        $this->tp_comment = str_replace($this->filepath . "Comment" . DIRECTORY_SEPARATOR, "", glob($this->filepath . "Comment" . DIRECTORY_SEPARATOR . 'comment*'));
    }

    public function index() {
        if (IS_POST) {
            //需要处理的栏目集合
            $catids = input('post.catids', '', '');
            if (empty($catids)) {
                $this->error('请选择需要操作的栏目！');
            }
            //操作
            $act = input('post.act', '', '');
            if (empty($act)) {
                $this->error('请选择你需要批量处理的操作！');
            }
            //栏目模型
            $CategoryModel =  model("Content/Category");
            //开始操作
            foreach ($catids as $catid) {
                //检查栏目是否存在，且获取栏目信息
                $info = $CategoryModel->where(array('catid' => $catid))->find();
                if (empty($info)) {
                    continue;
                }
                //相关配置
                $info['setting'] = $setting = unserialize($info['setting']) ? unserialize($info['setting']) : array();
                //遍历操作
                foreach ($act as $action) {
                    //info 数据
                    $post = input('post.', array(), '');
                    switch ($action) {
                        //是否在导航显示
                        case 'navigation':
                            $info['ismenu'] = (int) $post['info']['ismenu'];
                            break;
                        //后台增加/编辑信息
                        case 'adminedit':
                            $info['setting']['generatehtml'] = (int) $post['setting']['generatehtml'];
                            $info['setting']['generatelish'] = (int) $post['setting']['generatelish'];
                            break;
                        //前台投稿审核
                        case 'contribute':
                            $info['setting']['member_check'] = (int) $post['setting']['member_check'];
                            break;
                        //管理投稿
                        case 'contributeconfig':
                            $info['setting']['member_admin'] = (int) $post['setting']['member_admin'];
                            $info['setting']['member_editcheck'] = (int) $post['setting']['member_editcheck'];
                            break;
                        //投稿生成列表
                        case 'contributelist':
                            $info['setting']['member_generatelish'] = (int) $post['setting']['member_generatelish'];
                            break;
                        //投稿增加点数
                        case 'contributereward':
                            $info['setting']['contributereward'] = $post['setting']['contributereward'];
                            break;
                        //栏目首页模板
                        case 'indextemplate':
                            $info['setting']['category_template'] = $post['setting']['category_template'];
                            break;
                        //栏目列表模板
                        case 'liststemplate':
                            $info['setting']['list_template'] = $post['setting']['list_template'];
                            break;
                        //栏目内容页模板
                        case 'showtemplate':
                            $info['setting']['show_template'] = $post['setting']['show_template'];
                            break;
                        // 栏目生成Html
                        case 'listgenerate':
                            $info['setting']['ishtml'] = (int) $post['setting']['ishtml'];
                            //栏目生成静态配置
                            if ($info['setting']['ishtml']) {
                                $info['setting']['category_ruleid'] = input('post.category_html_ruleid', 0, 'intval');
                            } else {
                                $info['setting']['category_ruleid'] = input('post.category_php_ruleid', 0, 'intval');
                            }
                            break;
                        // 内容页生成Html
                        case 'showgenerate':
                            $info['setting']['content_ishtml'] = (int) $post['setting']['content_ishtml'];
                            //内容生成静态配置
                            if ($info['setting']['content_ishtml']) {
                                $info['setting']['show_ruleid'] = input('post.show_html_ruleid', 0, 'intval');
                            } else {
                                $info['setting']['show_ruleid'] = input('post.show_php_ruleid', 0, 'intval');
                            }
                            break;
                        // 后台角色权限
                        case 'adminrole':
                             model("Content/Category_priv")->update_priv($catid, $_POST['priv_roleid']);
                            break;
                        // 前台用户组权限
                        case 'memberrole':
                             model("Content/Category_priv")->update_priv($catid, $_POST['priv_groupid'], 0);
                            break;
                        //栏目类型转换
                        case 'cattype':
                            $cattype_id = input('post.cattype_id');
                            switch ($cattype_id) {
                                //转为单页栏目
                                case 1:
                                    $info['type'] = 1;
                                    break;
                                //转为外链栏目
                                case 2:
                                    if($info['child'] == 0){
                                        $info['type'] = 2;
                                    }
                                    break;
                                //转为普通栏目
                                case 3:
                                    $info['type'] = 0;
                                    break;
                            }
                            break;
                    }
                }
                //操作处理完，保存
                $info['setting'] = serialize($info['setting']);
                $CategoryModel->where(array('catid' => $catid))->save($info);
                getCategory($catid, '', true);
            }
            //更新栏目缓存
            $CategoryModel->category_cache();
            $this->success('栏目批量处理完毕，请及时更新栏目缓存！');
        } else {
            $tree = new \Tree();
            $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            $categorys = array();
            if (!empty($this->categorys)) {
                foreach ($this->categorys as $catid => $r) {
                    $r = getCategory($r['catid']);
                    $categorys[$catid] = $r;
                }
            }
            $str = "<option value='\$catid' \$selected>\$spacer \$catname</option>";

            $tree->init($categorys);
            $string .= $tree->get_tree(0, $str);
            $this->assign("string", $string);
            $this->assign("tp_category", $this->tp_category);
            $this->assign("tp_list", $this->tp_list);
            $this->assign("tp_show", $this->tp_show);
            $this->assign("tp_comment", $this->tp_comment);
            $this->assign("category_php_ruleid", \Form::urlrule('content', 'category', 0, "", 'name="category_php_ruleid"'));
            $this->assign("category_html_ruleid", \Form::urlrule('content', 'category', 1, "", 'name="category_html_ruleid"'));
            $this->assign("show_php_ruleid", \Form::urlrule('content', 'show', 0, "", 'name="show_php_ruleid"'));
            $this->assign("show_html_ruleid", \Form::urlrule('content', 'show', 1, "", 'name="show_html_ruleid"'));
            if (isModuleInstall('Member')) {
                //会员组
                $this->assign("Member_group", cache("Member_group"));
            }
            //角色组
            $this->assign("Role_group", M("Role")->order(array("id" => "ASC"))->select());
            $this->display();
        }
    }

}

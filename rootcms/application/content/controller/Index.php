<?php

// +----------------------------------------------------------------------
// | rootCMS 网站前台
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\content\controller;

use app\common\controller\Base;
use app\content\model\content;

class Index extends Base {
    
    public $usinfo = null;
    
    public function _initialize() {
        parent::_initialize();
        $this->usinfo = service("Passport")->getInfo();
        
        //向前台赋值seo变量
        $SEO = seo('', '', self::$Cache['Config']['siteinfo'], self::$Cache['Config']['sitekeywords']);
        //seo分配到模板
        $this->assign("SEO", $SEO);
        
    }

    //首页
    public function index() {
        //模板处理
        $indextp = empty(self::$Cache['Config']['indextp'])?"index.php":self::$Cache['Config']['indextp'];
        $tp = explode(".",$indextp);
        $template = parseTemplateFile("Index/{$tp[0]}");
        //把分页分配到模板
        return $this->display($template);
    }

    //列表
    public function lists() {
        //栏目ID
        $catid = input('catid', 0, 'intval');
        //分页
        $page  = input(config("extend.var_page"),1,'intval');
        //获取栏目数据
        $category = getCategory($catid);
        if (empty($category)) {
            send_http_status(404);
            exit;
        }
        //栏目扩展配置信息
        $setting = $category['setting'];
        //检查是否禁止访问动态页
        if ($setting['listoffmoving']) {
            send_http_status(404);
            exit;
        }
        //生成静态分页数
        $repagenum = (int) $setting['repagenum'];
        if ($repagenum && !$GLOBALS['dynamicRules']) {
            //设置动态访问规则给page分页使用
            $GLOBALS['Rule_Static_Size'] = $repagenum;
            $GLOBALS['dynamicRules'] = CONFIG_SITEURL_MODEL . "index.php?a=lists&catid={$catid}&page=*";
        }
        //父目录
        $parentdir = $category['parentdir'];
        //目录
        $catdir = $category['catdir'];
        //生成路径
        $category_url = $this->Url->category_url($catid, $page);
        //取得URL规则
        $urls = $category_url['page'];
        //生成类型为0的栏目
        if ($category['type'] == 0) {
            //栏目首页模板
            $template = $setting['category_template'] ? $setting['category_template'] : 'category';
            //栏目列表页模板
            $template_list = $setting['list_template'] ? $setting['list_template'] : 'list';
            //判断使用模板类型，如果有子栏目使用频道页模板，终极栏目使用的是列表模板
            $template = $category['child'] ? "Category/{$template}" : "List/{$template_list}";
            //去除后缀开始
            $tpar = explode(".", $template, 2);
            //去除完后缀的模板
            $template = $tpar[0];
            unset($tpar);
            $GLOBALS['URLRULE'] = $urls;
        } else if ($category['type'] == 1) {//单页
            $db =  model('content/Page');
            $template = $setting['page_template'] ? $setting['page_template'] : 'page';
            //判断使用模板类型，如果有子栏目使用频道页模板，终极栏目使用的是列表模板
            $template = "Page/{$template}";
            //去除后缀开始
            $tpar = explode(".", $template, 2);
            //去除完后缀的模板
            $template = $tpar[0];
            unset($tpar);
            $GLOBALS['URLRULE'] = $urls;
            $info = $db->getPage($catid);
            $this->assign($category['setting']['extend']);
            $this->assign($info);
        }
        //把分页分配到模板
        $this->assign(config("extend.var_page"), $page);
        //分配变量到模板 
        $this->assign($category);
        //seo分配到模板
        $seo = seo($catid, $setting['meta_title'], $setting['meta_description'], $setting['meta_keywords']);
        $this->assign("SEO", $seo);
        return $this->display($template);
    }

    //内容页
    public function shows() {
        $catid = input('catid', 0, 'intval');
        $id = input('id', 0, 'intval');
        $page = input(config("extend.var_page"),1,'intval');
        //获取当前栏目数据
        $category = getCategory($catid);
        if (empty($category)) {
            send_http_status(404);
            exit;
        }
        //反序列化栏目配置
        $category['setting'] = $category['setting'];
        //检查是否禁止访问动态页
        if ($category['setting']['showoffmoving']) {
            send_http_status(404);
            exit;
        }
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        // 2016年7月10日22:35:19 加入付费内容判断
        //首先判断信息是否收费如果收费，其次判断是否已经付过费！
        if( $category['setting']['charge'] && $category['setting']['price']>0 && 
            !getViewedContent($this->usinfo['userid'],$catid,$id)
            ){
            
            $reurl = base64_encode( url("","catid=$catid&id=$id"));
            session("site_returl", $reurl);
            
            $this->assign("charge",$category['setting']['price']);
            
            //如果余额不足提示充值
            if($this->usinfo['amount']<=0 || $this->usinfo['amount'] < $category['setting']['price']){
                $this->error("此内容需要额外的费用才能查看，但您的余额不足，请先充值！", url("member/Charge/index"));
                exit;
            }

        }
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //模型ID
        $modelid = $category['modelid'];
        $data = \app\content\model\Content::getInstance($modelid)->relation()->where(array("id" => $id, 'status' => 99))->find();
        if (empty($data)) {
            send_http_status(404);
            exit;
        }
        \app\content\model\Content::getInstance($modelid)->dataMerger($data);
        //分页方式
        if (isset($data['paginationtype'])) {
            //分页方式 
            $paginationtype = $data['paginationtype'];
            //自动分页字符数
            $maxcharperpage = (int) $data['maxcharperpage'];
        } else {
            //默认不分页
            $paginationtype = 0;
        }
        //tag
        tag('html_shwo_buildhtml', $data);
        $content_output = new \content_output($modelid);
        //获取字段类型处理以后的数据
        $output_data = $content_output->get($data);
        $output_data['id'] = $id;
        $output_data['title'] = strip_tags($output_data['title']);
        //SEO
        $seo_keywords = '';
        if (!empty($output_data['keywords'])) {
            $seo_keywords = implode(',', $output_data['keywords']);
        }
        $seo = seo($catid, $output_data['title'], $output_data['description'], $seo_keywords);

        //内容页模板
        $template = $output_data['template'] ? $output_data['template'] : $category['setting']['show_template'];
        //去除模板文件后缀
        $newstempid = explode(".", $template);
        $template = $newstempid[0];
        unset($newstempid);
        //分页处理
        $pages = $titles = '';
        //分配解析后的文章数据到模板 
        $this->assign($output_data);
        //seo分配到模板
        $this->assign("SEO", $seo);
        //栏目ID
        $this->assign("catid", $catid);
        //分页生成处理
        //分页方式 0不分页 1自动分页 2手动分页
        if ($data['paginationtype'] > 0) {
            $urlrules = $this->Url->show($data, $page);
            //手动分页
            $CONTENT_POS = strpos($output_data['content'], '[page]');
            if ($CONTENT_POS !== false) {
                $contents = array_filter(explode('[page]', $output_data['content']));
                $pagenumber = count($contents);
                $pages = page($pagenumber, 1, $page, array(
                    'isrule' => true,
                    'rule' => $urlrules['page'],
                        ))->show("default");
                //判断[page]出现的位置是否在第一位 
                if ($CONTENT_POS < 7) {
                    $content = $contents[$page];
                } else {
                    $content = $contents[$page - 1];
                }
                //分页
                $this->assign("pages", $pages);
                $this->assign("content", $content);
            }
        } else {
            $this->assign("content", $output_data['content']);
        }
        return $this->display("Show/{$template}");
    }

    //tags标签
    public function tags() {
        $tagid = input('get.tagid', 0, 'intval');
        $tag = input('get.tag', '', '');
        $where = array();
        if (!empty($tagid)) {
            $where['tagid'] = $tagid;
        } else if (!empty($tag)) {
            $where['tag'] = $tag;
        }
        //如果条件为空，则显示标签首页
        if (empty($where)) {
            $key = 'Tags_Index_index';
            $dataCache = cache($key);
            if (empty($dataCache)) {
                $data = db('Tags')->order(array('hits' => 'DESC'))->limit(100)->select();
                if (!empty($data)) {
                    //查询每个tag最新的一条数据
                    $tagsContent = db('TagsContent');
                    foreach ($data as $k => $r) {
					    $url = $this->Url->tags($r);
                        $data[$k]['url'] = $url['url'];
                        $data[$k]['info'] = $tagsContent->where(array('tag' => $r['tag']))->order(array('updatetime' => 'DESC'))->find();
                    }
                    //进行缓存
                    cache($key, $data, 3600);
                }
            } else {
                $data = $dataCache;
            }
            $SEO = seo('', '标签');
            //seo分配到模板
            $this->assign("SEO", $SEO);
            $this->assign('list', $data);
            $this->display("Tags/index");
            return true;
        }
        //分页号
        $page = isset($_GET[config("extend.var_page")]) ? $_GET[config("extend.var_page")] : 1;
        //根据条件获取tag信息
        $info = db('Tags')->where($where)->find();
        if (empty($info)) {
            $this->error('抱歉，沒有找到您需要的内容！');
        }
        //访问数+1
        db('Tags')->where($where)->setInc("hits");
        //更新最后访问时间
        db('Tags')->where($where)->save(array("lasthittime" => time()));
        $this->assign($data);
        $Urlrules = cache('Urlrules');
        //取得tag分页规则
        $urlrules = $Urlrules[self::$Cache['Config']['tagurl']];
        if (empty($urlrules)) {
            $urlrules = 'index.php?g=Tags&tagid={$tagid}|index.php?g=Tags&tagid={$tagid}&page={$page}';
        }else{
            $urlrules = $urlrules['urlrule'];
        }
        $GLOBALS['URLRULE'] = str_replace('|', '~', str_replace(array('{$tag}', '{$tagid}'), array($info['tag'], $info['tagid']), $urlrules));
        $SEO = seo();
        //seo分配到模板
        $this->assign("SEO", $SEO);
        //把分页分配到模板
        $this->assign(config("extend.var_page"), $page);
        $this->assign($info);
        $this->display("Tags/tag");
    }

}

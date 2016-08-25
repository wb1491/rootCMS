<?php

// +----------------------------------------------------------------------
// | rootCMS 网站前台
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wb23 <wb1491@gmail.com>
// +----------------------------------------------------------------------

namespace Content\Controller;

use Common\Controller\Base;
use Content\Model\FlightInfoInModel;
use Content\Model\FlightInfoOutModel;

class InfoController extends Base {

    //到港航班信息数据库
    protected $dbIn;
    //离港航班信息数据库
    protected $dbOut;

    public function __construct() {
        parent::__construct();
        //实例化航班信息数据库
        $this->dbIn = new FlightInfoInModel();
        $this->dbOut = new FlightInfoOutModel();
    }

    //首页
    public function index() {
        $page = max(input('page', 1, 'intval'), 1);
        $type = input('type', 'in');
        $pages = $this->page($this->dbIn->count(), 30, $page);
        $urls = $this->Url->index($page);
        $GLOBALS['URLRULE'] = $urls['page'];

        //查询航班信息
        if ($type == 'in') {
            $Data = $this->dbOut->field("CLNAME,AIRCORP,FLTNO,TKFPNAME,PASS1NAME,ARRPNAME,FSTATUSNAME,"
                                    . "to_char(ARRTM,'hh24:mi') as ARRTM,to_char(TKFTM, 'hh24:mi') as TKFTM,"
                                    . "to_char(RTKFTM,'hh24:mi') as RTKFTM,to_char(RARRTM,'hh24:mi') as RARRTM")
                            ->order('TKFTM ASC')->limit($pages->firstRow, $pages->listRows)->select();
        } else {
            $Data = $this->dbIn->field("CLNAME,AIRCORP,FLTNO,TKFPNAME,PASS1NAME,ARRPNAME,FSTATUSNAME,"
                                    . "to_char(ARRTM,'hh24:mi') as ARRTM,to_char(TKFTM, 'hh24:mi') as TKFTM,"
                                    . "to_char(RTKFTM,'hh24:mi') as RTKFTM,to_char(RARRTM,'hh24:mi') as RARRTM")
                            ->order('TKFTM ASC')->limit($pages->firstRow, $pages->listRows)->select();
        }
        $SEO = seo('', '', self::$Cache['Config']['siteinfo'], self::$Cache['Config']['sitekeywords']);
        //seo分配到模板
        $this->assign("SEO", $SEO);
        //把分页分配到模板
        $this->assign("type", $type);
        $this->assign("pages", $pages->show());
        $this->assign("Data", $Data);
        $this->assign("catid", 1);
        $this->display();
    }

    public function search() {
        $fromcity = input("post.fromcity", '', "htmlspecialchars");
        $tocity = input("post.tocity", '', "htmlspecialchars");
        $company = input("post.company", '', "htmlspecialchars");
        $airnmb = input("post.airnmb", '', "htmlspecialchars");
        $type = input("post.type", 0, 'intval');
        $where = " 1=1 ";

        if (!empty($fromcity)) {
            $where .= " and TKFPNAME like '%$fromcity%'";
        }
        if (!empty($tocity)) {
            $where .= " and ARRPNAME like '%$tocity%'";
        }
        if (!empty($company)) {
            $where .= " and CLNAME like '%$company%'";
        }
        if (!empty($airnmb)) {
            $where .= " and CONCAT(AIRCORP,FLTNO) like '%$airnmb%'";
        }
        $inData = $this->dbIn->field("CLNAME,AIRCORP,FLTNO,TKFPNAME,PASS1NAME,ARRPNAME,FSTATUSNAME,"
                                . "to_char(ARRTM,'hh24:mi') as ARRTM,to_char(TKFTM, 'hh24:mi') as TKFTM,"
                                . "to_char(RTKFTM,'hh24:mi') as RTKFTM,to_char(RARRTM,'hh24:mi') as RARRTM")
                        ->where($where)->order('TKFTM ASC')->select();
        $outData = $this->dbOut->field("CLNAME,AIRCORP,FLTNO,TKFPNAME,PASS1NAME,ARRPNAME,FSTATUSNAME,"
                                . "to_char(ARRTM,'hh24:mi') as ARRTM,to_char(TKFTM, 'hh24:mi') as TKFTM,"
                                . "to_char(RTKFTM,'hh24:mi') as RTKFTM,to_char(RARRTM,'hh24:mi') as RARRTM")
                        ->where($where)->order('TKFTM ASC')->select();

        if (!$inData && !$outData) {
            $this->error('查询过程中出现错误，请重试！');
        }
        if ($type > 0) {
            if ($type == 1) {
                $Data = $outData;
            } else {
                $Data = $inData;
            }
        } else {
            //$Data = array_merge($inData, $outData);
            //合并查询结果
            foreach($inData as $v){
                $Data[] = $v;
            }
            foreach ($outData as $v){
                $Data[] = $v;
            }
        }
        
        $SEO = seo('', '', self::$Cache['Config']['siteinfo'], self::$Cache['Config']['sitekeywords']);
        //seo分配到模板
        $this->assign("SEO", $SEO);
        //把分页分配到模板
        //$this->assign(C("VAR_PAGE"), $page);
        $this->assign('search', '1');
        $this->assign("Data", $Data);
        $this->assign('fromcity', $fromcity);
        $this->assign('tocity', $tocity);
        $this->assign('company', $company);
        $this->assign('airnmb', $airnmb);
        $this->assign("catid", 1);
        $this->display('index');
    }

    public function getListJson() {
        $nmb = input("get.nmb", 5, 'intval');
        $len = input('get.len',4,'intval');
        $Data = $this->dbIn->field("CLNAME,AIRCORP,CONCAT(AIRCORP,FLTNO) as AIRCORPNO,"
                                . "CONCAT(CONCAT(TKFPNAME,'-'),ARRPNAME) as ARRPNAME ,FSTATUSNAME,"
                                . "to_char(ARRTM,'hh24:mi') as ARRTM,to_char(TKFTM, 'hh24:mi') as TKFTM")
                        ->order('TKFTM ASC')->limit($nmb)->select();
        foreach ($Data as $k => $v) {
            $Data[$k]['CLNAME']= str_cut($v['CLNAME'],$len,'');
            $Data[$k]["FSTATUSNAME"] = str_replace('null', '', $v['FSTATUSNAME']);
        }
        if ($Data) {
            echo json_encode($Data);
        }
    }

}

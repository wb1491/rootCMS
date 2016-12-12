<?php

// +----------------------------------------------------------------------
// | rootCMS 附件管理
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace Content\Controller;

use Content\Controller\FileManager;
use Content\Model\ContentModel;

class VideoController extends FileManager {
    
    //附件存在物理地址
    public $path = '';

    function _initialize() {
        parent::_initialize();
        //附件目录强制/d/file/ 后台设置的附件目录，只对网络地址有效
        $this->path = SITE_PATH ."tempvideo".DIRECTORY_SEPARATOR;//C("UPLOADFILEPATH") . "videofile/";
        
        if(!file_exists($this->path)){
            @mkdir($this->path,0777,true);
        }
        
        //赋值页面标题接连接
        $this->assign("title","视频文件管理");
        $this->assign("title_url", url('index'));
    }

    public function index(){
        //设置允许上传文件类型
        $this->fileExt = 'mp4|mp3';
        //启用附件自动解压
        $this->unzip = false;
        //初始化按钮 其中可以使用 _BTURL_ 表示当前连接
        $fieext = implode("|", $this->fileExt);
        $listButton = array(
            "local" => array(
                //"diradd" => "<a href=\"_ADDURL_\" class=\"btn\" title=\"在当前目录下新建目录\"><span class=\"add\"></span>新建目录</a>",
                //"delete" => "<a herf='javascript:void(0);' class='btn' onclick=\"javascript:flashupload('upload_file', '上传数据文件', '{$this->fileCount},{$fieext},0')\" title=\"上传数据文件到当前目录下\"><span class=\"upload\"></span>上传数据</a>",
            ),
            "dir" => array(
                "del" => "<a href=\"javascript:confirmurl('{:url('delete','dir=_DIR_')}','确认要删除此目录吗？')\"  class=\"btn\" ><span class=\"del\"></span>删除</a>"
            ),
            "file" => array(
                "import" => "<a href=\"javascript:videoimport('_DIR_')\"  class=\"btn\" ><span class=\"impload\"></span>导入</a>",
                //"del" => "<a href=\"javascript:confirmurl('{:url('delete','dir=_FILE_')}','确认要删除此文件吗？')\"  class=\"btn\" ><span class=\"del\"></span>删除</a>",
            )        
        );
        $this->setListButton($listButton);
        //扩展js
        $this->expand_js = "
function videoimport(path,file) {
    Wind.use('artDialog','iframeTools', function () {
         art.dialog.open(\"". url("import")."&path=\"+ path , {
            title: \"视频信息导入\"
        });
    });
}";
        
        
        //设置提示信息
        $local = $this->getLocalPath($this->path);
        $prompt = "<p><font color=red>通过ftp工具上传视频文件到{$local}目录，然后进行添加！</font></p>";
        $this->assign("prompt",$prompt);
        $this->assign("title","数据文件管理");
        $this->showFileList();
    }

    public function import(){
        if(IS_POST){
            //栏目ID
            $catid = intval($_POST['catid']);
            if (empty($catid)) {
                $this->error("请指定栏目ID！");
            }
            if (trim($_POST['title']) == '') {
                $this->error("标题不能为空！");
            }
            //获取当前栏目配置
            $category = getCategory($catid);
            if(empty($category)){
                $this->error("模型错误！");
            }
            //获取初始数据
            $data = input("post.");
            
            //处理视频文件及图片文件
            $newpath = config("UPLOADFILEPATH")."videofile".DIRECTORY_SEPARATOR.date("Ymd").DIRECTORY_SEPARATOR.date("Hi").DIRECTORY_SEPARATOR;
            
            $oldfile = $this->pressPath(SITE_PATH . $data['videourl'],false);
            $newfile = $this->pressPath($newpath.$data['file'],false);
            if(!empty($data['thumb'])){
                $othumb  = $this->pressPath(SITE_PATH . $data['path'].$data['thumb'],false);
                $nthumb  = $this->pressPath($newpath.$data['thumb'],false);
                //改变缩略图地址
                $data['thumb'] = $this->getLocalPath($nthumb);
            }
            //改变视频地址
            $data['videourl'] = $this->getLocalPath($newfile);
            //加入发布及录入时间
            $data['inputtime'] = $data['updatetime'] = time();
            
            require_once PROJECT_PATH . "Libs/Util/File.class.php";
            $File = new \File();
            
            //实例化内容模型开始保存视频信息
            $ConModel = \Content\Model\ContentModel::getInstance($category['modelid']);
            $dbdata = $ConModel->create($data);
            $status = $ConModel->add($dbdata);
            if ($status) {
                //扩展表数据更新
                $expdata = array(
                    "id"=>$status,
                    "content"=>$data['content'],
                );
                M($ConModel->getModelName()."_data")->add($expdata);
                
                if(!file_exists($newpath)){
                    mkdir($newpath,0777,true);
                }
                //移动需要导入的视频文件                
                $File->moveFile($oldfile,$newfile);
                if( !empty($data['thumb'])){
                    //移动缩略图
                    $File->moveFile($othumb,$nthumb);
                }
                
                //删除ini文件
                $filename = basename($data['file'],  ".".fileext($data['file']));
                @unlink($this->path. $filename . ".ini");
                
                $this->success("添加成功！");
            } else {
                $error = $ConModel->getError();
                $this->error($error ? $error : '添加失败！');
            }
        }else{
        
            $path = input("get.path",'',"dirDeCode");
            $local = $this->getLocalPath();

            $pathinfo = pathinfo($path);

            $infofile = $pathinfo['filename'].".ini";
            $videoinfo = array();
            //如果存在视频信息描述文件
            if(file_exists($this->path . $infofile)){
                //获取视频相关信息
                $videoinfo = $this->pressContent($this->path . $infofile);
            }else{
                $videoinfo = array(
                    "title" => $pathinfo['filename'],
                    "description" => $pathinfo['filename'],
                    "thumb" => "",
                    "content" => $pathinfo['basename'],
                );
            }
            //获取视频类栏目
            $catelist = array();
            $categorysList = cache('Category');
            foreach($categorysList as $v){
                $v = getCategory($v['catid']);
                if($v['modelid'] == 4){
                    $catelist[$v['catid']] = $v['catname'];
                }
            }

            $this->assign("data", $videoinfo);
            $this->assign("path",$local);
            $this->assign("file", $pathinfo['basename']);
            $this->assign("videofile",$local.$pathinfo['basename']);

            $this->assign("category",$catelist);
            $this->display();
        }
    }
    
    private function pressContent($file){
        //解析ini文件
        $matches = parse_ini_file($file);
        
        foreach($matches as $k => $v){
            $matches[$k] = siconv($v, "utf-8");
        }
        
        return $matches;
    }
}
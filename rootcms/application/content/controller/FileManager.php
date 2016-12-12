<?php

/**
 * 上传数据文件及文件管理
 *
 * @author wb23
 */

namespace Content\Controller;

use Common\Controller\AdminBase;

class FileManager extends AdminBase {

    /**
     * 附件存储路径
     * @var string
     */
    public $path = "";

    /**
     * 当前的目录名称
     * @var String 
     */
    public $dir = "";

    /**
     * 上传附件的用户名称
     * @var string
     */
    public $upname;

    /**
     * 上传文件的用户ID
     * @var integer
     */
    public $upuserid;

    /**
     * 上传的模块模块
     * @var string 
     */
    public $module = "Content";

    /**
     * 上传用户的组ID
     * @var integer
     */
    public $groupid;

    /**
     * 允许上传的文件类型
     * @var Array 
     */
    public $fileExt = array();

    /**
     * 允许上传的文件数
     * @var string
     */
    public $fileCount = 10;

    /**
     * 各模块调用的模板名称
     * @var Array
     */
    public $tpl = array(
        "listfile" => "Content@FileManager:index",
        "diradd" => "Content@FileManager:diradd",
        "swfupload" => "Content@FileManager:swfupload",
        "infobox"=>"Content@FileManager:infobox",
    );

    /**
     * 列表页面的按钮  
     *     其中有三个部分： 
     *         listButton['local'] 中包含但前栏目下的按钮
     *         listButton['dir'] 中包含目录操作按钮
     *         listButton['file'] 中包含文件操作按钮
     *     可以使用 
     *          _ADDURL_ 表示新增目录按钮连接 
     *          _CUR_ 表示但前目录 
     *          _DIR_ 表示所处目录 
     *          _FILE_ 所处文件
     *         {:url('','param')} 来处表示此处为U函数
     * @var Array 
     */
    public $listButton = array(
        "local" => array(),
        "dir" => array(),
        "file" => array(),
    );

    /**
     * 页面扩展JS
     * @var string
     */
    public $expand_js = "";

    /**
     * 是否需要解压，只有允许上传文件类型为zip时，开启此项才能自动解压上传的附件
     * @var boolean 
     */
    public $unzip = false;

    public function _initialize() {
        parent::_initialize();
        //设置路径
        $this->path = config('UPLOADFILEPATH');

        //默认图片类型 多个类型使用|分隔
        $this->fileExt = 'zip';

        //赋值页面标题接连接
        $this->assign("title", "数据文件管理");
        $this->assign("title_url",  url('index'));
    }

    /**
     * 设置列表页面按钮
     * @param type $name 如果name是listButton中的名称，则设置相应按钮，如果是个数组则直接设置
     * @param type $button button按钮串
     */
    public function setListButton($name, $button = '') {
        if (!empty($name) && is_array($name)) {
            $this->listButton = $name;
        } elseif (!empty($name) && !empty($button)) {
            if (is_array($button)) {
                foreach ($button as $k => $v) {
                    $this->listButton[$name][$k] = $v;
                }
            } else {
                $this->listButton[$name] = $button;
            }
        }
    }

    /**
     * 设置管理的根路径
     * @param String $path
     */
    public function setPath($path) {
        if (!empty($path)) {
            $this->path = $path;
        }
    }

    /**
     * 处理路径，如果参数path为空，则自动获取request的path参数
     * @param string $path 路径字符串
     * @param Boolean $isPast 是否加路径最后的/
     * @param Boolean $CreatDir 是否自动创建未存在目录
     * @return string
     */
    protected function pressPath($path = '', $isPast = true, $CreatDir=false) {
        if (empty($path)) {
            $path = input("request.dir", "", "trim");
            $dir = $path ? dirDeCode($path) : '';
            if (!empty($dir) && !is_file(SITE_PATH . $this->path . $dir) && substr($dir, -1) != DIRECTORY_SEPARATOR && $isPast) {
                $dir .= DIRECTORY_SEPARATOR;
            }
        } else {
            //直接处理$path参数时，有可能会有|这个字符
            $dir = str_replace("|", DIRECTORY_SEPARATOR, $path);

            if (!empty($dir) && !is_file($dir) && substr($dir, -1) != DIRECTORY_SEPARATOR && $isPast) {
                $dir .= DIRECTORY_SEPARATOR;
            }
        }
        //替换路径分隔符
        $dir = str_replace(array("/", '\\', "/\\", "\\/", '\\\\', '//'), DIRECTORY_SEPARATOR, $dir);
        //处理目录回溯
        if (substr($dir, -3) == ".." . DIRECTORY_SEPARATOR || substr($dir, -2) == "..") {
            $dir = str_replace(".." . DIRECTORY_SEPARATOR, "..", $dir);
            $pos = strrpos(substr($dir, 0, strlen($dir) - 3), DIRECTORY_SEPARATOR);
            $dir = substr($dir, 0, $pos);
        }

        //判断目录是否存在，不存在则新建
        if( $CreatDir && false == file_exists($dir)){
            //$tmppath = explode(DIRECTORY_SEPARATOR, $dir);
            @mkdir($dir);
        }
        return $dir;
    }

    /**
     * 获取相对路径
     * @param string $path 路径
     * @return type
     */
    protected function getLocalPath($path) {
        if (empty($path)) {
            $tmpPath = $this->pressPath();
        } else {
            $tmpPath = $path;
        }
        if(empty($tmpPath)){
            $tmpPath = $this->pressPath($this->path);
        }
        $site_path = str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, SITE_PATH);
        $local = DIRECTORY_SEPARATOR . str_replace($site_path, '', $tmpPath);
        return $local;
    }

    /**
     * 显示文件列表
     * @param String $tpl 显示模板名称可以是 [模块@][控制器:][操作] 常用写法，支持跨模块 模板主题可以和theme方法配合
     *                     或者是完整的模板文件名 直接使用完整的模板文件名（包括模板后缀）
     *                     为空则调用 $tpl['listfile']值 
     */
    public function showFileList($tpl = '') {
        if (!empty($tpl)) {
            $this->tpl['listfile'] = $tpl;
        }
        $ext = SITE_PATH . '/statics/images/ext/';
        $ExtUrl = CONFIG_SITEURL . 'statics/images/ext/';
        $extList = glob($ext . '*.*');
        $TplExtList = array();
        $dirico = 'dir.gif';

        //获取但前的目录名称
        $dir = $this->pressPath();
        //echo "DIR:".$dir."<br/>\n";
        $filepath = $this->pressPath( $this->path . $dir);
        //echo "PATH:".$filepath."<br/>\n";
        $local = $this->getLocalPath($filepath);
        //echo "LOCAL:".$local."<br/>\n";
        $fiter = str_replace("|",",",$this->fileExt);

        $list = glob($filepath . '*.{'.$fiter.'}',GLOB_BRACE);

        if (!empty($list)) {
            ksort($list);
        }
        foreach ($list as $k => $v) {
            $v = str_replace('/\\', "/", $v);
            $tmp = array("name" => sbasename(siconv($v, "utf-8")), "type" => "file", "oldname" => sbasename($v));

            if ($tmp['name'] == 'Config.php' || $tmp['name'] == 'Thumbs.db') {
                unset($list[$k]);
            } else {
                //获取拓展名
                $thisExt = pathinfo($filepath . $v, PATHINFO_EXTENSION);
                //如果获取为空说明这是文件夹
                $thisExt == '' && $thisExt = 'dir';
                $thisExt == "dir" && $tmp['type'] = 'dir';
                //检测是否有此类型的试图文件
                in_array($ext . $thisExt . '.jpg', $extList) && $TplExtList[$tmp['name']] = $ExtUrl . $thisExt . '.jpg';
                in_array($ext . $thisExt . '.gif', $extList) && $TplExtList[$tmp['name']] = $ExtUrl . $thisExt . '.gif';
                in_array($ext . $thisExt . '.png', $extList) && $TplExtList[$tmp['name']] = $ExtUrl . $thisExt . '.png';
                in_array($ext . $thisExt . '.bmp', $extList) && $TplExtList[$tmp['name']] = $ExtUrl . $thisExt . '.bmp';
                //兼容不存在视图的文件
                (!in_array($TplExtList[$tmp['name']], $TplExtList) || $TplExtList[$tmp['name']] == '') && $TplExtList[$tmp['name']] = $ExtUrl . 'hlp.gif';
            }
            $list[$k] = $tmp;
        }
        //添加页面列表按钮
        $this->assign("listButton", $this->listButton);
        //添加扩展js
        $this->assign("expand_js", $this->expand_js);

        $this->assign("tplist", $list);
        $this->assign("dir", $this->pressPath());
        $this->assign("local", siconv($local, "utf-8"));

        $this->assign("tplextlist", $TplExtList);
        $this->assign("dirico", $dirico);
        $this->assign("diricolen", strlen($dirico));
        $this->assign("unzip", $this->unzip);

        $this->display($this->tpl['listfile']);
    }

    /**
     * 删除数据文件
     */
    public function delete() {
        //获取dir参数
        $dir = $this->pressPath('',false);
        //处理路径
        $path = $this->pressPath(SITE_PATH . $this->path . $dir, false);
        //处理返回地址
        $retpath = $this->pressPath($retpath."..");
            
        if (file_exists($path)) {
            if (is_file($path)) {
                $status = unlink($path);
            } else {
                $status = rmdirs($path);
            }
            if ($status) {
                $this->success("删除成功！",  url('index',"dir=".  dirEnCode( $retpath )));
            } else {
                $this->error("删除失败，请检查文件权限是否设置为可写！",  url('index',"dir=".  dirEnCode( $retpath )));
            }
        } else {
            $this->error("需要删除的文件或目录不存在！<br/>" . $dir,  url('index',"dir=".  dirEnCode( $retpath )));
        }
    }

    /**
     * 添加目录
     */
    public function diradd() {
        $dir = $this->pressPath();
        $dirname = input("post.dirname");

        if (IS_POST) {
            if ($dirname) {
                //根据操作系统不同改变字符编码
                if (PHP_OS == 'WINNT') {
                    $dirname = siconv($dirname, 'gbk');
                }
                $path = $this->pressPath(SITE_PATH . $this->path . $dir . $dirname,true,false);

                $st = mkdir($path);
                if ($st) {
                    $this->success("新建目录成功！",  url("index", array("dir" => dirEnCode($dir))));
                } else {
                    $this->error("新建目录失败！{$path}");
                }
            }
        } else {
            //添加页面导航
            $this->assign("nav",$this->nav);
            //添加页面列表按钮
            $this->assign("listButton", $this->listButton);
            //添加扩展js
            $this->assign("expand_js", $this->expand_js);

            $this->assign("dir", $dir);
            $this->display($this->tpl['diradd']);
        }
    }

    /**
     * flash上传插件显示及文件上传功能
     */
    public function swfupload() {
        if (IS_POST) {
            $dir = $this->pressPath();

            //上传处理类
            $upload = new \UploadFile();

            //设置上传类型
            $allowext = empty($this->fileExt) ? explode("|",CONFIG_UPLOADALLOWEXT) : explode("|",$this->fileExt);
            //如果允许上传的文件类型为空，启用网站配置的 uploadallowext
            //允许上传的文件类型，直接使用网站配置的。20120708
            $upload->allowExts = $allowext;

            //设置上传大小
            $upload->maxSize = (int) CONFIG_UPLOADMAXSIZE * 1024; //单位字节
            //设置上传文件保存目录
            $upload->savePath = $this->pressPath(SITE_PATH . $this->path . $dir);
            //清除文件保存规则，以原文件名称保存
            $upload->saveRule = "";

            //开始上传
            if ($upload->upload()) {
                //上传成功
                $info = $upload->getUploadFileInfo();
                if (in_array($info[0]['extension'], $allowext)) {
                    // 附件ID 附件网站地址 图标(图片时为1) 文件名
                    echo "1," . $this->path . $info[0]['savename'] . "," . $info[0]['extension'] . "," . str_replace(array("\\", "/"), "", $info[0]['name']);
                    exit;
                } else {
                    exit("0,上传失败，无法识别的文件格式或者未知压缩文件格式！");
                }
            } else {
                //上传失败，返回错误
                exit("0,上传失败，" . $upload->getErrorMsg());
            }
        } else {
            //1,允许上传的文件类型,是否允许从已上传中选择,图片高度,图片高度,是否添加水印1是
            $dir = $this->pressPath();
            $args = input('args');

            $info = explode(",", $args);
            //参数补充完整
            if (empty($info[1])) {
                //如果允许上传的文件类型为空，启用网站配置的 uploadallowext
                $info[1] = $this->fileExt;
            }
            //$this->fileExt = $info[1];
            //var_dump($att);exit;
            $this->assign("initupload", $this->initUploadswf($dir,explode("|", $info[1])));
            //上传格式显示
            $this->assign("file_types", implode(",", explode("|", $info[1])));
            $this->assign("file_size_limit", CONFIG_UPLOADMAXSIZE);
            $this->assign("file_upload_limit", (int) $info[0]);
            $this->assign("att", $att);
            $this->assign("tab_status", $tab_status);
            $this->assign("div_status", $div_status);
            $this->assign("att_not_used", $att_not_used);
            $this->assign("watermark_enable", (int) $info[5]); //是否添加水印
            $this->display($this->tpl['swfupload']);
        }
    }

    /**
     * 初始化flash上传组件的js代码
     * @param String $dir 但前所处的目录名称
     * @return string
     */
    private function initUploadswf($dir,$fileExt) {
        $sess_id = time();
        $swf_auth_key = md5(C("AUTHCODE") . $sess_id);
        $file_size_limit = intval(C("uploadmaxsize"));
        $file_type = "";
        foreach ($fileExt as $v){
            $file_type .= "*.".$v.";";
        }

        $str = '
var swfu = \'\';
$(document).ready(function(){
    Wind.use("swfupload",GV.DIMAUB+"statics/js/swfupload/handlers.js",function(){
        swfu = new SWFUpload({
            flash_url:"' . CONFIG_SITEURL . 'statics/js/swfupload/swfupload.swf?"+Math.random(),
            upload_url:"' .  url('swfupload', 'dir=' . dirEnCode($dir) ) . '",
            file_post_name : "Filedata",
            post_params:{
                "SWFUPLOADSESSID":"' . $sess_id . '",
                "module":"PrintSetup",
                "isadmin":1,
                "filetype_post":"' . implode("，", $fileExt) . '",
                "swf_auth_key":"' . $swf_auth_key . '"
            },
            file_size_limit:"' . $file_size_limit . 'KB",
            file_types:"'. $file_type . '",
            file_types_description:"数据文件",
            file_upload_limit:"10",
            custom_settings : {progressTarget : "fsUploadProgress",cancelButtonId : "btnCancel"},

            button_image_url: "",
            button_width: 75,
            button_height: 28,
            button_placeholder_id: "buttonPlaceHolder",
            button_text_style: "",
            button_text_top_padding: 3,
            button_text_left_padding: 12,
            button_action: SWFUpload.BUTTON_ACTION.SELECT_FILES,
            button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
            button_cursor: SWFUpload.CURSOR.HAND,

            file_dialog_start_handler:fileDialogStart,
            file_queued_handler:fileQueued,
            file_queue_error_handler:fileQueueError,
            file_dialog_complete_handler:fileDialogComplete,
            upload_progress_handler:uploadProgress,
            upload_error_handler:uploadError,
            upload_success_handler:uploadSuccess,
            upload_complete_handler:uploadComplete
        });
    });
})';
        return $str;
    }

    /**
     * 解压附件
     */
    public function unzip() {
        $dir = $this->pressPath();
        //echo "unzip-DIR:{$dir}<br/>\n";
        $files = input("get.files", '', "trim");
        $files = $this->pressPath($files, false);
        //echo "unzip-FILE:{$files}<br/>\n";
        //包含zip解压文件，否则无法找到函数
        require_once PROJECT_PATH . 'Libs/Util/PclZip.class.php';
        $str = "";

        $filepath = $this->pressPath(SITE_PATH . $this->path . $dir);
        //echo "unzip-PATH:{$filepath}<br/>\n";
        $extpath = $filepath;
        if (PHP_OS == "WINNT") {
            $extpath = PclZipUtilTranslateWinPath($filepath, FALSE);
        }

        $files = substr($files, 0, 1) == "|" ? substr($files, 1) : $files;
        $files = explode("|", $files);

        foreach ($files as $file) {
            if (!empty($file)) {
                if(PHP_OS == 'WINNT'){
                    $file = siconv($file, "gbk");
                    $filepath = siconv($filepath, "gbk");
                }else{
                    $file = siconv($file, "utf-8");
                    $filepath = siconv($filepath, "utf-8");
                }
                $zipfile = $this->pressPath($filepath . $file, false);
                if (is_file($zipfile)) {
                    $zip = new \PclZip($zipfile);
                    $flg = $zip->extract(PCLZIP_OPT_PATH, $extpath);
                    if ($flg == 0) {
                        $str .= "Error : " . $zip->errorInfo(true) . "\n";
                    }
                    //不管解压是否成功都删除上传文档！
                    unlink($filepath . $file);
                }else{
                    echo "需要解压的文件非法！\n{$zipfile}";
                }
            }else{
                echo "需要解压的文件路径错误！\n{$file}";
            }
        }
        echo $str;
    }

    /**
     * 信息提示显示
     * @param type $message
     * @param type $button
     * @param type $jumpUrl
     */
    public function infobox($message, $button = "", $jumpUrl = '') {
        // 提示标题
        if (!empty($title)) {
            $this->assign("msgTitle", $title);
        } else {
            $this->assign('msgTitle', "信息提示");
        }
        if (!empty($button)) {
            $this->assign("button", $button);
        } elseif (!empty($jumpUrl)) {
            $this->assign('jumpUrl', $jumpUrl);
        }
        //保证输出不受静态缓存影响
        config('HTML_CACHE_ON', false);
        $this->assign("message", $message);
        $this->display($this->tpl['infobox']);
        exit;
    }
}

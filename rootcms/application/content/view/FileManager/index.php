<?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<style>
    .btn .upload{
        width:12px;
        height:12px;
        background:url("{$config_siteurl}statics/images/icon/Upload.png") top center no-repeat;
        display:inline-block;
        vertical-align:middle;
        margin:-3px 5px 0 -3px;
        line-height:9px;
    }
    .btn .del{
        width:14px;
        height:15px;
        background:url("{$config_siteurl}statics/images/icon/upload_del.png") 0 1px no-repeat;
        display:inline-block;
        vertical-align:middle;
        margin:-3px 5px 0 -3px;
        line-height:9px;
    }
    .btn .impload{
        width:16px;
        height:15px;
        background:url("{$config_siteurl}statics/images/icon/arrow.png") 0 1px no-repeat;
        display:inline-block;
        vertical-align:middle;
        margin:-3px 5px 0 -3px;
        line-height:9px;
    }
</style>
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <?php
        $getMenu = isset($Custom)?$Custom: model('admin/Menu')->getMenu();
        if($nav){ ?>
        <div class="nav">
            <ul class="cc">
            <volist name='nav' id='vo'>
              <li <?php
                $a = getUrlParam($vo['link'], 'a');
                $m = getUrlParam($vo['link'], "m");
                $m = empty($m)?"Index":$m;
                $cru = false;
                if(!empty($a)){
                    $cru = ($a==ACTION_NAME);
                }else{
                    $cru = ($m==CONTROLLER_NAME);
                }
                echo $cru?'class="current"':""; 
              ?>><a href="{$vo.link}">{$vo.title}</a></li>
            </volist>
            </ul>
        </div>
        <?php 
        }elseif( $getMenu ) {
        ?>
        <div class="nav">
          <?php
          if(!empty($menuReturn)){
                echo '<div class="return"><a href="'.$menuReturn['url'].'">'.$menuReturn['name'].'</a></div>';
          }
          ?>
          <ul class="cc">
            <?php
                foreach($getMenu as $r){
                    $app = $r['app'];
                    $controller = $r['controller'];
                    $action = $r['action'];
                    ?>
                    <li <?php echo $action == ACTION_NAME ?'class="current"':""; ?>><a href="<?php echo  url("".$app."/".$controller."/".$action."",$r['parameter']);?>"><?php echo $r['name'];?></a></li>
                    <?php
                }
                ?>
          </ul>
        </div>
        <?php }elseif(!empty($title)){ ?>
        <div class="nav">
            <ul class="cc">
                <li class="current"><a href="{$title_url}">{$title}</a></li>
            </ul>
        </div>
        <?php }?>
        <div class="h_a">温馨提示</div>
        <div class="prompt_text">
          {$prompt}
        </div>
        <form action="" method="post" class="J_ajaxForm">
            <div class="table_list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td align="left" width="30%">目录列表</td>
                            <td align="left"  width="15%">操作</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td align="left">当前目录：{$local}</td>
                            <td align="left">
                                <foreach name='listButton[local]' item="vo">
                                    <php>
                                    $search = array(
                                        "_ADDURL_",
                                        "_CUR_",
                                        "_DIR_",
                                        "_FILE_",
                                    );
                                    $replace = array(
                                         url("diradd","dir=".dirEnCode($dir) ),
                                        dirEnCode($dir),
                                        dirEnCode($dir),
                                        dirEnCode($dir),
                                    );
                                    $tmp = str_replace($search,$replace,$vo);
                                    $tmp = smallPress url( $tmp );
                                    echo $tmp."&nbsp;&nbsp;";
                                    </php>
                                </foreach>
                            </td>
                        </tr>
                    <if condition="$dir neq '' && $dir neq '.' ">
                        <tr>
                            <td align="left" colspan="2"><a href="{: url('',array('dir'=>dirEnCode( $dir . '..' )))}"><img src="{$config_siteurl}statics/images/folder-closed.gif" />上一层目录</a></td>
                        </tr>
                    </if>
                    <volist name="tplist" id="vo">
                        <tr>
                            <td align="left">
                        <if condition="$vo['type'] == 'file'" >
                            <img src="{$tplextlist[$vo['name']]}" width='18px' style="vertical-align: bottom;" />
                            <b>{$vo.name}</b>
                            </td>
                            <td>
                                <foreach name='listButton[file]' item="ls">
                                    <php>
                                    $search = array(
                                        "_ADDURL_",
                                        "_CUR_",
                                        "_DIR_",
                                        "_FILE_",
                                    );
                                    $replace = array(
                                        dirEnCode($dir .$vo[oldname]),
                                        dirEnCode($dir),
                                        dirEnCode($dir.$vo[oldname]),
                                        $vo[name],
                                    );
                                    $tmp = str_replace($search,$replace,$ls);
                                    $tmp = smallPress url( $tmp );
                                    echo $tmp."&nbsp;&nbsp;";
                                    </php>
                                </foreach>
                            </td>
                        <else />
                            <img src="{$tplextlist[$vo['name']]}" width='18px' style="vertical-align: bottom;" />
                            <a href="{: url('',array( 'dir'=>dirEnCode($dir.$vo['oldname']) ))}"><b>{$vo.name}</b></a>
                            </td>
                            <td>
                                <foreach name='listButton[dir]' item="ls">
                                    <php>
                                    $search = array(
                                        "_ADDURL_",
                                        "_CUR_",
                                        "_DIR_",
                                        "_FILE_",
                                    );
                                    $replace = array(
                                         url("diradd","&dir=".dirEnCode($dir .$vo[oldname]) ),
                                        dirEnCode($dir),
                                        dirEnCode($dir.$vo[oldname]),
                                        '',
                                    );
                                    $tmp = str_replace($search,$replace,$ls);
                                    $tmp = smallPress url( $tmp );
                                    echo $tmp."&nbsp;&nbsp;";
                                    </php>
                                </foreach>
                            </td>
                        </if>
                        </tr>
                    </volist>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    <script src="{$config_siteurl}statics/js/common.js?v"></script>
    <script type="text/javascript">
      /**
       swf上传完回调方法
       uploadid dialog id
       name dialog名称
       args 参数
       **/
      function flashupload(uploadid, name, args) {
          $("#flter_msk").attr('style', 'display:;position:absolute;width:100%; height:350px;filter:alpha(opacity=0);opacity:0;-moz-opacity:0;z-index:19;');
          var args = args ? '&args=' + args : '';
          Wind.use("artDialog", "iframeTools", function () {
              art.dialog.open('{: url("swfupload","&dir=".dirEnCode($dir) )}' + args, {
                  title: name,
                  id: uploadid,
                  width: '650px',
                  height: '350px',
                  lock: true,
                  fixed: true,
                  background: "#CCCCCC",
                  opacity: 0,
                  ok: function () {
                    <if condition="$unzip">
                      unzip(this);
                    <else/>
                      reloadPage(window);
                    </if>
                  },
                  cancel: function () {
                      reloadPage(window);
                  }
              });
          });
      }
      <if condition="$unzip">
      function unzip(opobj) {
          var d = opobj.iframe.contentWindow;
          var dir = "{:dirEnCode($dir)}";
          var files = d.$("#att-name").html();
          var url = "{: url('unzip')}&dir=" + dir + "&files=" + files;

          Wind.use("artDialog", "iframeTools", function () {
              art.dialog.tips("正在解压文件 ...");
              $.ajax({
                  url: url,
                  success: function (r) {
                      if (r && r.length > 0) {
                          alert(r);
                      } else {
                          art.dialog.tips("文件解压成功！");
                      }
                      reloadPage(window);
                  }
              });
          });
      }
      </if>
            
    {$expand_js}
    </script>
</body>
</html>
 <?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
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
        <form action="{:url('diradd',array('dir'=>dirEnCode($dir) ))}" method="post" name="myform" class="J_ajaxForm">
            <div class="table_full">
                <table cellpadding="2" cellspacing="1" class="table_form" width="100%">
                    <tr>
                        <th width="100" align="right">目录名称：</th>
                        <td><input type="text" name="dirname" size="30" value="" class="input"></td>
                    </tr>
                </table>
            </div>
            <div class="">
                <div class="btn_wrap_pd">             
                    <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">保 存</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="{$config_siteurl}statics/js/common.js?v"></script>
<script src="{$config_siteurl}statics/student/common.js?v"></script>
</body>
</html>
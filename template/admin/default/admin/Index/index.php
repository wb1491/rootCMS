<!DOCTYPE html>
<html lang="zh-cn">    

  <admintemplate file="admin/header_head.php"/>

  <body class="desktop-detected">

    <!-- #HEADER -->
    <header id="header">
      <div id="logo-group">

        <!-- PLACE YOUR LOGO HERE -->
        <span id="logo"> <img src="{$statics_admin}{$admin_theme}img/logo.png" alt="rootCMS"> </span>
        <!-- END LOGO PLACEHOLDER -->

      </div>

      <!-- #TOGGLE LAYOUT BUTTONS -->
      <div id="topMenu_bottons" class="project-context hidden-xs">
      </div>
      
      <!-- pulled right: nav area -->
      <div class="pull-right">

        <!-- collapse menu button -->
        <div id="hide-menu" class="btn-header pull-right">
          <span> <a href="javascript:void(0);" data-action="toggleMenu" title="菜单"><i class="fa fa-reorder"></i></a> </span>
        </div>
        <!-- end collapse menu -->

        <!-- #MOBILE -->
        <!-- Top menu profile link : this shows only when top menu is active -->
        <ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
          <li class="">
            <a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown"> 
              <img src="{$statics_admin}{$admin_theme}img/avatars/male.png" alt="{}" class="online" />  
            </a>
            <ul class="dropdown-menu pull-right">
              <li>
                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0"><i class="fa fa-cog"></i> 设置</a>
              </li>
              <li class="divider"></li>
              <li>
                <a href="#ajax/profile.html" class="padding-10 padding-top-0 padding-bottom-0"> <i class="fa fa-user"></i> 用户中心</a>
              </li>
              <li class="divider"></li>
              <li>
                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="toggleShortcut"><i class="fa fa-arrow-down"></i> 功能图标</a>
              </li>
              <li class="divider"></li>
              <li>
                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> 全屏</a>
              </li>
              <li class="divider"></li>
              <li>
                <a href="javascript:void(0);" class="padding-10 padding-top-5 padding-bottom-5" data-action="userLogout"><i class="fa fa-sign-out fa-lg"></i> <strong>退出</strong></a>
              </li>
            </ul>
          </li>
        </ul>
        
        <!-- logout button -->
        <div id="logout" class="btn-header transparent pull-right">
          <span> <a href="{:url('admin/Publics/logout')}" title="退出" data-action="userLogout" 
                    data-logout-msg="你可以进一步改善你的安全，在退出后关闭这个已经打开浏览器">
              <i class="fa fa-sign-out"></i></a> 
          </span>
        </div>
        <!-- end logout button -->
        
        <?php if(\System\RBAC::authenticate('admin/Index/cache')){ ?>
        <!-- updatecache button -->
        <div id="upcache" class="btn-header transparent pull-right">
          <span> <a href="javascript:void(0);" data-action="loadSUrl" data-url="{:url('admin/Index/cache')}" data-title="更新服务器缓存" title="更新服务器缓存">
              <i class="fa fa-history"></i></a> 
          </span>
        </div>
        <!-- end updatecache button -->
        <?php } ?>

        <!-- search mobile button (this is hidden till mobile view port) -->
        <div id="search-mobile" class="btn-header transparent pull-right">
          <span> <a href="javascript:void(0)" title="搜索"><i class="fa fa-search"></i></a> </span>
        </div>
        <!-- end search mobile button -->

        <!-- #SEARCH -->
        <!-- input: search field -->
        <form action="{:url('admin/Index/public_find')}" class="header-search pull-right">
          <input id="search-fld" type="text" name="keyword" placeholder="搜索系统功能">
          <button type="submit">
            <i class="fa fa-search"></i>
          </button>
          <a href="javascript:void(0);" id="cancel-search-js" title="取消搜索"><i class="fa fa-times"></i></a>
        </form>
        <!-- end input: search field -->

        <!-- fullscreen button -->
        <div id="fullscreen" class="btn-header transparent pull-right">
          <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="全屏"><i class="fa fa-arrows-alt"></i></a> </span>
        </div>
        <!-- end fullscreen button -->
        
        <!-- multiple lang dropdown : find all flags in the flags page -->
        <ul class="header-dropdown-list hidden-xs hidden">
          <li>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img src="{$statics_admin}{$admin_theme}img/blank.gif" class="flag flag-cn" alt="中国"> <span>中文 </span> <i class="fa fa-angle-down"></i> </a>
            <ul class="dropdown-menu pull-right">
              <li class="active">
                <a href="javascript:void(0);"><img src="{$statics_admin}{$admin_theme}img/blank.gif" class="flag flag-cn" alt="中国"> 中文 </a>
              </li>
            </ul>
          </li>
        </ul>
        <!-- end multiple lang -->

      </div>
      <!-- end pulled right: nav area -->

    </header>
    <!-- END HEADER -->

    <!-- #NAVIGATION -->
    <!-- Left panel : Navigation area -->
    <!-- Note: This width of the aside area can be adjusted through LESS/SASS variables -->
    <aside id="left-panel">

      <!-- User info -->
      <div class="login-info">
        <span> <!-- User image size is adjusted inside CSS, it should stay as is --> 

          <a href="javascript:void(0);" title="{$role_name}" id="show-shortcut" data-action="toggleShortcut">
            <img src="{$statics_admin}{$admin_theme}img/avatars/male.png" alt="$role_name" class="online" /> 
            <span>
              {$userInfo.username}
            </span>
            <i class="fa fa-angle-down"></i>
          </a> 

        </span>
      </div>
      <!-- end user info -->

      <!-- menu list -->
      <nav data-jsonurl="{:url('admin/Index/public_menu')}">
        <ul>
          
        </ul>
      </nav>
      <!-- end menu list -->
      
      <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>

    </aside>
    <!-- END NAVIGATION -->

    <!-- #MAIN PANEL -->
    <div id="main" role="main">

      <!-- RIBBON -->
      <div id="ribbon">

        <span class="ribbon-button-alignment"> 
          <span id="refresh" class="btn btn-ribbon" data-action="reloadPage" data-title="刷新"
                rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> 刷新页面。" 
                data-html="true"><i class="fa fa-refresh"></i></span> 
        </span>

        <!-- breadcrumb -->
        <ol class="breadcrumb">
          <!-- This is auto generated -->
        </ol>
        <!-- end breadcrumb -->

        <!-- You can also add more buttons to the
        ribbon for further usability

        Example below:

        <span class="ribbon-button-alignment pull-right" style="margin-right:25px">
            <a href="#" id="search" class="btn btn-ribbon hidden-xs" data-title="search"><i class="fa fa-grid"></i> Change Grid</a>
            <span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa fa-plus"></i> Add</span>
            <button id="search" class="btn btn-ribbon" data-title="search"><i class="fa fa-search"></i> <span class="hidden-mobile">Search</span></button>
        </span> -->

      </div>
      <!-- END RIBBON -->

      <!-- #MAIN CONTENT -->
      <div id="content">

      </div>

      <!-- END #MAIN CONTENT -->

    </div>
    <!-- END #MAIN PANEL -->

    <!-- #PAGE FOOTER -->
    <div class="page-footer">
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <span class="txt-color-white">{$config.version.appname} {$config.version.version} <span class="hidden-xs"> - {$config.version.description}</span> © {$config.version.authtime}</span>
        </div>

        <div class="col-xs-6 col-sm-6 text-right hidden-xs">
          <div class="txt-color-white inline-block">
            <i class="txt-color-blueLight hidden-mobile">最后登录时间 <i class="fa fa-clock-o"></i> <strong>{$userInfo.last_login_time|date="Y/m/d H:i:s",###} &nbsp;</strong> </i>
            
            <!-- end btn-group-->
          </div>
          <!-- end div-->
        </div>
        <!-- end col -->
      </div>
      <!-- end row -->
    </div>
    <!-- END FOOTER -->

    <!-- #SHORTCUT AREA : With large tiles (activated via clicking user name tag)
         Note: These tiles are completely responsive, you can add as many as you like -->
    <div id="shortcut">
      <ul>
        <li>
          <a href="javascript:void(0);" data-action="loadSUrl" data-url="{:url('admin/Adminmanage/myinfo')}" class="jarvismetro-tile big-cubes bg-color-blue"> <span class="iconbox"> <i class="fa fa-envelope fa-4x"></i> <span>管理员信息</span> </span> </a>
        </li>
        <li>
          <a href="javascript:void(0);" data-action="loadSUrl" data-url="{:url('admin/Adminmanage/chanpass')}" class="jarvismetro-tile big-cubes bg-color-orangeDark"> <span class="iconbox"> <i class="fa fa-calendar fa-4x"></i> <span>修改密码</span> </span> </a>
        </li>
        <li>
          <a href="javascript:void(0);" data-action="loadSUrl" data-url="{:url('admin/Adminmanage/chanavatar')}" class="jarvismetro-tile big-cubes bg-color-purple"> <span class="iconbox"> <i class="fa fa-map-marker fa-4x"></i> <span>修改头像</span> </span> </a>
        </li>
      </ul>
    </div>

    <admintemplate file="admin/footer_script.php">

  </body>

</html>
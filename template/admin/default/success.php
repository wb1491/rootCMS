<?php
  $SEO['title'] = $Config["sitename"]." - 提示信息";
?>
<!DOCTYPE html>
<html lang="zh-cn" id="extr-page">
    <head>
        <meta charset="utf-8">
        <title>{$config.sitename}</title>
        <meta name="keywords" content="{$config.sitekeywords}">
        <meta name="description" content="{$config.siteinfo}">
        <meta name="author" content="wb">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- #CSS Links -->
        <!-- Basic Styles -->
        <link rel="stylesheet" type="text/css" media="screen" href="{$statics_admin}{$admin_theme}css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="{$statics_admin}{$admin_theme}css/font-awesome.min.css">

        <!-- SmartAdmin Styles : Caution! DO NOT change the order -->
        <link rel="stylesheet" type="text/css" media="screen" href="{$statics_admin}{$admin_theme}css/smartadmin-production-plugins.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="{$statics_admin}{$admin_theme}css/smartadmin-production.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="{$statics_admin}{$admin_theme}css/smartadmin-skins.min.css">

        <!-- SmartAdmin RTL Support -->
        <link rel="stylesheet" type="text/css" media="screen" href="{$statics_admin}{$admin_theme}css/smartadmin-rtl.min.css"> 

        <!-- #FAVICONS -->
        <link rel="shortcut icon" href="{$statics_admin}{$admin_theme}img/favicon/favicon.ico" type="image/x-icon">
        <link rel="icon" href="{$statics_admin}{$admin_theme}img/favicon/favicon.ico" type="image/x-icon">

    </head>

    <body class="animated fadeInDown">

        <div id="main" role="main">

            <!-- MAIN CONTENT -->
            <div id="content" class="container">
                <!-- Modal -->
                <div class="modal fade modal-dialog" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title"><i class="fa fa-check-circle txt-color-orangeDark"></i> 信息提示 -- {$msgTitle} </h4>
                      </div>
                      <div class="modal-body">
                        <p class="error"><?php echo(strip_tags($msg));?></p>
                      </div>
                      <div class="modal-footer">
                        页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
                      </div>
                    </div>
                </div>
            </div>

        </div>

        <!--================================================== -->	

        <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
        <script src="{$statics_admin}{$admin_theme}js/plugin/pace/pace.min.js"></script>

        <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
        <script src="{$statics_admin}{$admin_theme}js/libs/jquery-2.1.1.min.js"></script>

        <script src="{$statics_admin}{$admin_theme}js/libs/jquery-ui-1.10.3.min.js"></script>

        <!-- IMPORTANT: APP CONFIG -->
        <script src="{$statics_admin}{$admin_theme}js/app.config.js"></script>

        <!-- JS TOUCH : include this plugin for mobile drag / drop touch events  -->	
        <script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script>

        <!-- BOOTSTRAP JS -->		
        <script src="{$statics_admin}{$admin_theme}js/bootstrap/bootstrap.min.js"></script>

        <!-- JQUERY VALIDATE -->
        <script src="{$statics_admin}{$admin_theme}js/plugin/jquery-validate/jquery.validate.min.js"></script>

        <!-- JQUERY MASKED INPUT -->
        <script src="{$statics_admin}{$admin_theme}js/plugin/masked-input/jquery.maskedinput.min.js"></script>

        <!--[if IE 8]>
            <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
        <![endif]-->

        <!-- MAIN APP JS FILE -->
        <script src="{$statics_admin}{$admin_theme}js/app.min.js"></script>
        
        <script>
            (function(){
                var wait = document.getElementById('wait'),
                    href = document.getElementById('href').href;
                var interval = setInterval(function(){
                    var time = --wait.innerHTML;
                    if(time <= 0) {
                        location.href = href;
                        clearInterval(interval);
                    };
                }, 1000);
                
                $("#myModal").modal("show");
                $("#mybtn").on("click",function(){
                    location.href = href;
                });
            })();
        </script>

    </body>
</html>
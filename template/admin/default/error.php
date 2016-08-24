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

        <link rel="stylesheet" type="text/css" media="screen" href="{$statics_admin}{$admin_theme}css/rootcms.css"> 

        <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
        <link rel="stylesheet" type="text/css" media="screen" href="{$statics_admin}{$admin_theme}css/demo.min.css">

        <!-- #FAVICONS -->
        <link rel="shortcut icon" href="{$statics_admin}{$admin_theme}img/favicon/favicon.ico" type="image/x-icon">
        <link rel="icon" href="{$statics_admin}{$admin_theme}img/favicon/favicon.ico" type="image/x-icon">



        <!-- #APP SCREEN / ICONS -->
        <!-- Specifying a Webpage Icon for Web Clip 
                 Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
        <link rel="apple-touch-icon" href="{$statics_admin}{$admin_theme}img/splash/sptouch-icon-iphone.png">
        <link rel="apple-touch-icon" sizes="76x76" href="{$statics_admin}{$admin_theme}img/splash/touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="120x120" href="{$statics_admin}{$admin_theme}img/splash/touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="152x152" href="{$statics_admin}{$admin_theme}img/splash/touch-icon-ipad-retina.png">

        <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">

        <!-- Startup image for web apps -->
        <link rel="apple-touch-startup-image" href="{$statics_admin}{$admin_theme}img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
        <link rel="apple-touch-startup-image" href="{$statics_admin}{$admin_theme}img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
        <link rel="apple-touch-startup-image" href="{$statics_admin}{$admin_theme}img/splash/iphone.png" media="screen and (max-device-width: 320px)">

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
                        <h4 class="modal-title"><i class="fa fa-check-circle txt-color-orangeDark"></i> 错误 -- {$msgTitle} </h4>
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

        <!-- JS TOUCH : include this plugin for mobile drag / drop touch events 		
        <script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

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
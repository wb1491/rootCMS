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

        <script>
        var resource_url = '{$statics_admin}{$admin_theme}';
        </script>
    </head>

    <body class="animated fadeInDown">

        <header id="header">

            <div id="logo-group">
                <span id="logo"> <img src="{$statics_admin}{$admin_theme}img/logo.png" alt="SmartAdmin"> </span>
            </div>

        </header>

        <div id="main" role="main">

            <!-- MAIN CONTENT -->
            <div id="content" class="container">

                <div class="row">
                    <div class="hidden-xs hidden-mobile col-md-4 col-lg-4"></div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="well no-padding">
                            <form id="login-form" name="loginform" class="smart-form client-form">
                                <header>
                                    系统登录
                                </header>

                                <fieldset>

                                    <section>
                                        <label class="label">账号</label>
                                        <label class="input"> <i class="icon-append fa fa-user"></i>
                                            <input type="text" id="username" name="username">
                                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> 请输入管理员账号</b>
                                        </label>
                                    </section>

                                    <section>
                                        <label class="label">密码</label>
                                        <label class="input"> <i class="icon-append fa fa-lock"></i>
                                            <input type="password" id="password" name="password">
                                            <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> 管理员密码</b> 
                                        </label>
                                    </section>

                                    <section>
                                        <label class="label">验证码</label>
                                        <label class="input">
                                            <i class="icon-vertify">
                                            <img id="code_img" src="{:url('api/Checkcode/index','type=adminlogin&code_len=4&font_size=14&width=130&height=22')}"
                                                 onClick="refreshs()" alt="看不清，换一张" />
                                            </i>
                                            <input type="text" id="code" name="code">
                                            <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> 输入图片验证码，点击更换</b> 
                                        </label>
                                    </section>
                                  
                                </fieldset>
                                <footer>
                                    <button type="submit" id="sbbt" class="btn btn-primary">
                                        登录
                                    </button>
                                </footer>
                            </form>

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

        <!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->	
        <script src="{$statics_admin}{$admin_theme}js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script>

        <!-- BOOTSTRAP JS -->		
        <script src="{$statics_admin}{$admin_theme}js/bootstrap/bootstrap.min.js"></script>

        <!-- JQUERY VALIDATE -->
        <script src="{$statics_admin}{$admin_theme}js/plugin/jquery-validate/jquery.validate.min.js"></script>

        <!-- JQUERY MASKED INPUT -->
        <script src="{$statics_admin}{$admin_theme}js/plugin/masked-input/jquery.maskedinput.min.js"></script>

        <!--[if IE 8]>
            <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
        <![endif]-->

        <!-- CUSTOM NOTIFICATION -->
        <script src="{$statics_admin}{$admin_theme}js/notification/SmartNotification.js"></script>
        
        <!-- MAIN APP JS FILE -->
        <script src="{$statics_admin}{$admin_theme}js/appv1.8.js"></script>

        <script type="text/javascript">
            $(function () {
                // Validation
                $("#login-form").validate({
                    // Rules for form validation
                    rules: {
                        username: {
                            required: true,
                            minlength: 3,
                            maxlength: 20
                        },
                        password: {
                            required: true,
                            minlength: 3,
                            maxlength: 20
                        },
                        code:{
                            required: true,
                            rangelength:[4,4]
                        }
                    },
                    // Messages for form validation
                    messages: {
                        username: {
                            required: '请输入账号名称',
                            maxlength: $.validator.format("最多可以输入 {0} 个字符"),
                            minlength: $.validator.format("最少要输入 {0} 个字符")
                        },
                        password: {
                            required: '请输入密码',
                            maxlength: $.validator.format("最多可以输入 {0} 个字符"),
                            minlength: $.validator.format("最少要输入 {0} 个字符")
                        },
                        code:{
                            required: '请输入验证码',
                            rangelength: '验证码输入错误'
                        }
                    },
                    // Do not change code below
                    errorPlacement: function (error, element) {
                        error.insertAfter(element.parent());
                    },
                    submitHandler: function(form){
                        //$(form).ajaxSubmit();
                        var data = $(form).serialize();
                        $.ajax({
                            type: "POST",
                            url: "{:url('admin/Publics/tologin')}",
                            data: data,
                            dataType: "json",
                            success:function(obj){
                                if(obj.status){
                                    var msg = obj.msg|| "登陆成功！",wait=obj.wait?(obj.wait*1000):2000;
                                    $("#sbbt").addClass("disabled").removeClass("btn-primary").addClass("btn-success").html(msg);
                                    setTimeout(function(){
                                        window.location.href=obj.jumpurl;
                                    },wait);
                                }else{
                                    var msg = obj.msg|| "登陆失败！",wait=obj.wait?(obj.wait*1000):4000;
                                    $("#sbbt").addClass("disabled").removeClass("btn-primary").addClass("btn-warning").html(msg);
                                    setTimeout(function(){
                                        window.location.href=obj.jumpurl;
                                    },wait);
                                }
                            },
                            error:function(obj){
                                alert("error:" + obj);
                            }
                        });
                    }
                });
                
                $("#sbbt").click(function(){
                    $("#login-form").submit();
                });
                
                $('#code').focus(function(){
                    refreshs();
                });
            });
            function refreshs(){
                $('#code_img').attr("src","{:url('api/Checkcode/index','type=adminlogin&code_len=4&font_size=14&width=130&height=22&refresh=1')}" + '&time=' + Math.random());
            }
        </script>


    </body>
</html>
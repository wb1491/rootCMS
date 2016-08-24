<?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<!DOCTYPE html>
<!--[if IE 8]><html class="ie ie8"><![endif]-->
<!--[if IE 9]><html class="ie ie9"><![endif]-->
<!--[if gt IE 9]>--><html><!--<![endif]-->
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <title>重设密码 - {$Config.sitename}</title>
        <meta name="description" content="{$SEO['description']}" />
        <meta name="keywords" content="{$SEO['keyword']}" />
        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- The prohibition of Baidu transcoding -->
        <meta http-equiv="Cache-Control" content="no-transform" />
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <!-- Google Fonts -->

        <!-- Library CSS -->
        <link rel="stylesheet" href="{$config_siteurl}statics/Szjyzx/css/bootstrap.css">
        <link rel="stylesheet" href="{$config_siteurl}statics/Szjyzx/css/bootstrap-theme.css">
        <link rel="stylesheet" href="{$config_siteurl}statics/Szjyzx/css/fonts/font-awesome/css/font-awesome.css">
        <link rel="stylesheet" href="{$config_siteurl}statics/Szjyzx/css/animations.css" media="screen">
        <link rel="stylesheet" href="{$config_siteurl}statics/Szjyzx/css/superfish.css" media="screen">
        <link rel="stylesheet" href="{$config_siteurl}statics/Szjyzx/css/revolution-slider/css/settings.css" media="screen">
        <link rel="stylesheet" href="{$config_siteurl}statics/Szjyzx/css/revolution-slider/css/extralayers.css" media="screen">
        <link rel="stylesheet" href="{$config_siteurl}statics/Szjyzx/css/prettyPhoto.css" media="screen">
        <!-- Theme CSS -->
        <link rel="stylesheet" href="{$config_siteurl}statics/Szjyzx/css/style.css">
        <!-- Skin -->
        <link rel="stylesheet" href="{$config_siteurl}statics/Szjyzx/css/colors/green.css" class="colors">
        <!-- Responsive CSS -->
        <link rel="stylesheet" href="{$config_siteurl}statics/Szjyzx/css/theme-responsive.css">
        <!-- Switcher CSS -->
        <link href="{$config_siteurl}statics/Szjyzx/css/switcher.css" rel="stylesheet">
        <link href="{$config_siteurl}statics/Szjyzx/css/spectrum.css" rel="stylesheet">
        <!-- custom -->
        <link href="{$config_siteurl}statics/Szjyzx/css/custom.css" rel="stylesheet">
        <!-- Favicons -->
        <link rel="shortcut icon" href="{$config_siteurl}statics/Szjyzx/img/ico/favicon.ico">
        <link rel="apple-touch-icon" href="{$config_siteurl}statics/Szjyzx/img/ico/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="{$config_siteurl}statics/Szjyzx/img/ico/apple-touch-icon-72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="{$config_siteurl}statics/Szjyzx/img/ico/apple-touch-icon-114.png">
        <link rel="apple-touch-icon" sizes="144x144" href="{$config_siteurl}statics/Szjyzx/img/ico/apple-touch-icon-144.png">
        
        <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="{$config_siteurl}statics/Szjyzx/js/respond.min.js"></script>
        <![endif]-->
        <!--[if IE]>
        <link rel="stylesheet" href="{$config_siteurl}statics/Szjyzx/css/ie.css">
        <![endif]-->
        <!-- carousel -->
        <script type="text/javascript" src="{$config_siteurl}statics/Szjyzx/js/jquery.min.js"></script>
        <!-- carousel -->
        <base target="_blank" />
        
        <template file="member/Publics/global_js.php"/>
        <template file="member/Publics/global_js.php"/>
        <script type="text/javascript" src="{$model_extresdir}js/common.js"></script>
        <style>
        /*tip message start*/
        .tip_message{display:none;background:none;position:absolute;font-family:Arial,Simsun,"Arial Unicode MS",Mingliu,Helvetica;font-size:14px;}
        .tip_message .tip_ico_succ
        {background-position:-6px 0;background-repeat:no-repeat;width:45px;}
        .tip_message .tip_ico_fail{background-position:-6px -108px;background-repeat:no-repeat;width:45px;}
        .tip_message .tip_ico_hits{background-position:-6px -54px;background-repeat:no-repeat;width:45px;}
        .tip_message .tip_end{background-position:0 0;background-repeat:no-repeat;width:6px;}
        .tip_content{background-position:0 -161px;background-repeat:repeat-x;padding:0 20px 0 8px; word-break:keep-all;white-space:nowrap;}
        .tip_message .tip_message_content{position:absolute; left:0; top:0; width:100%;height:100%;z-index:65530;}
        .ie6_mask{position:absolute; left:0; top:0; width:100%;height:100%;background-color:transparent;z-index:-1;filter:alpha(opacity=0);}
        /* tip message end */
        </style>
    </head>

    <body class="home">
        <div class="page-mask">
            <div class="page-loader">
                <div class="spinner"></div>
                加载中...
            </div>
        </div>
        <!-- Wrap -->
        <section class="wrap bgcolor">
            <template file="Content/header.php"/>
        </section>
        <!-- Main Section -->
        <section class="login_bg">
            <!-- Main Content -->
            <div class="content padding-top30 padding-bottom50 ">
                <div class="container">
                    <div class="row">
                        <!-- Login -->
                        <div class="featured-boxes login">
                            <!-- Login -->
                            <div class="col-md-2 pull-right hidden-xs"></div>
                            <div class="col-md-8 col-xs-12 pull-right">
                                <div class="featured-box featured-box-secundary default info-content">
                                    <h2 class="form-signin-heading">重设密码</h2>
                                    <div class="box-content ">
                                        <div class="text"><span> 重新设置您在&nbsp; <strong>{$Config.sitename}</strong> &nbsp;注册的会员账号登录密码。 </span></div>
                                        <div class="contenter margin-top30">
                                            <div class="row" id="resetPass">
                                                <div class="form-group clearfix">
                                                    <div class="col-md-2 text-right">您的昵称：</div>
                                                    <div class="col-md-6" >
                                                        <input class="form-control" type="hidden" id="key" name="key" value="{$key}"/>
                                                        {$userinfo.username}
                                                    </div>
                                                    <div class="col-md-4"></div>
                                                </div>
                                                <div class="form-group clearfix" style="margin-top: 40px;">
                                                    <div class="col-md-2 text-right">设置密码：</div>
                                                    <div class="col-md-4" id="rM_rpassword">
                                                        <input id="rpassword" class="form-control" type="password" maxlength="20" name="rpassword"/>
                                                    </div>
                                                    <div class="col-md-6" id="mpassword"></div>
                                                </div>
                                                <div class="form-group clearfix">
                                                    <div class="col-md-2 text-right">确认密码：</div>
                                                    <div class="col-md-4" id="rM_rpassword2">
                                                        <input id="rpassword2" class="form-control" type="password" maxlength="20" name="rpassword2"/>
                                                    </div>
                                                    <div class="col-md-6" id="mpassword2"></div>
                                                </div>
                                                <div class="form-group clearfix">
                                                    <div class="col-md-12 text-center">
                                                        <input id="submit" class="submit btn btn-color" type="submit" value="修改账号密码"/>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div id="errMessage" style="display: none;background-color:#FFB941;" class="alert alert-danger tip_message" role="alert"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <!-- /Login -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Main Content -->
        </section>
        
        <template file="Content/footer.php"/>
        
        <script type="text/javascript" src="{$model_extresdir}js/common.js"></script>
        <script type="text/javascript" src="{$model_extresdir}js/lostpass.js"></script>
        <script type="text/javascript">
            lostpass.resetInit();
        </script>
    </body>
</html>
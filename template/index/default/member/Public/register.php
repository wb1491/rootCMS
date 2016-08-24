<php>
    $SEO['title'] = "用户注册 -- ".$config['sitename'];
</php>
<template file='content/header_file.php'/>
<template file="content/header_nav.php"/>

<div class="container user">
  <div class="row indexblock">
    <div class="col-md-12 col-xs-12 fixlist3 border">
      <div class="userlogin">
        <form  class="form" id="form1" action="{:url('Publics/doRegister')}" method="post">
          <div class="form-group">
            <div class="col-md-12">
              <div id="errMessage" style="display: none;" class="alert alert-danger" role="alert"></div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-3 ">
                <span class="frmlabe hidden-xs hidden-sm">用户名</span>
              </div>
              <div class="col-md-9">
                <input id="username" type="text" name="username" minlength="4" maxlength="20" class="form-control" placeholder="登录用户名" required/>
              </div>
              <div class="col-md-3">
                <div id="musername"></div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-3 ">
                <span class="frmlabe hidden-xs hidden-sm">设置密码</span>
              </div>
              <div class="col-md-9">
                <input id="rpassword" type="password" value="" name="password" minlength="6" maxlength="20" class="form-control" placeholder="设置密码" required/>
              </div>
              <div class="col-md-3">
                <div id="mpassword"></div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-3 ">
                <span class="frmlabe hidden-xs hidden-sm">确认密码</span>
              </div>
              <div class="col-md-9">
                <input id="rpassword2" type="password" value="" name="password2" class="form-control" placeholder="确认密码" required/>
              </div>
              <div class="col-md-3">
                <div id="mpassword2"></div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-3 ">
                <span class="frmlabe hidden-xs hidden-sm">手机号码</span>
              </div>
              <div class="col-md-9">
                <input id="mobile" type="tel" value="" name="mobile" class="form-control" placeholder="输入手机号码" required/>
              </div>
              <div class="col-md-3">
                <div id="mpassword2"></div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-3 ">
                <span class="frmlabe hidden-xs hidden-sm">手机验证码</span>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-6">
                <input id="rvCode" class="form-control" type="text" name="mCode"  minlength="6" maxlength="6"  placeholder="请输入验证码"/>
              </div>
              <div class="col-md-5 col-sm-6 col-xs-6 paddingfix">
                <a class="btn btn-default" id="sendsms">发送短信验证码</a>
              </div>
              <div class="col-md-3">
                <div id="mvCode"></div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-3 ">
                <span class="frmlabe hidden-xs hidden-sm">验证码</span>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-6">
                <input id="rvCode" class="form-control" type="text" name="vCode"  minlength="4" maxlength="4"  placeholder="请输入验证码"/>
              </div>
              <div class="col-md-5 col-sm-6 col-xs-6 paddingfix">
                <img id="authCode" align="absmiddle" title="看不清？点击更换" src='{:url("api/Checkcode/index","type=userregister&code_len=4&font_size=18&width=100&height=32&font_color=&background=")}'> 
                <a class="hidden-sm hidden-xs" id="changeAuthCode" href="javascript:;">看不清？</a> 
              </div>
              <div class="col-md-3">
                <div id="mvCode"></div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-12">
                <div id="tipMessage"></div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-12 text-center">
                <input type="submit" value=" 立即注册 " id="submit" class="btn btn-default push-bottom" data-loading-text="Loading...">
              </div>
              <div class="col-md-12">
                <br/>
                <a href="{:url('member/Index/login')}">已有账号？</a>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<template file="content/footer.php"/>
<script type="text/javascript" src="{$model_extresdir}js/common.js"></script>
<script type="text/javascript">
    user.loginInit(1);
    user.loginCss();
    
    //验证码倒数
    var regsiter_vy_time = null; //定义时间
    var is_lock_send_vy = false; //解除锁定
    var left_rg_time = 0; //开始时间
    
    function left_time_to_send_regvy(){
        clearTimeout(regsiter_vy_time);
        if (left_rg_time > 0){
            regsiter_vy_time = setTimeout(left_time_to_send_regvy, 1000);
            $("#sendsms").addClass("disabled");
            $("#sendsms").html(left_rg_time + "秒后重新获取验证码");
            left_rg_time --;
        }
        else{
            is_lock_send_vy = false;
            $("#sendsms").removeClass("disabled");
            $("#sendsms").html("重新获取验证码");
            left_rg_time = 0;
        }
    }

    $(function(){
        $("#sendsms").click(function(){
            $.ajaxSettings.async = false;//同步执行 Todo异步修改

            is_lock_send_vy = true;
            if( $.trim($("#username").val()).length < 4 ){
                    is_lock_send_vy = false;
                    $.tipMessage("用户名没有输入或者格式错误，用户名必须4个字符以上");
                    return false;
            }
            
            if( $.trim($("#rpassword").val()).length == 0 ){
                    is_lock_send_vy = false;
                    $.tipMessage("密码没有输入");
                    return false;
            }
            if( $.trim($("#rpassword").val()) !== $.trim($("#rpassword2").val()) ){
                    is_lock_send_vy = false;
                    $.tipMessage("两次密码不相同");
                    return false;
            }
            if ($.trim($("#mobile").val()).length > 11){
                    is_lock_send_vy = false;
                    $.tipMessage("手机号码长度不能超过11位");
                    return false;
            }

            if ($.trim($("#mobile").val()).length == 0)
            {
                    is_lock_send_vy = false;
                    $.tipMessage("手机号码不能为空");
                    return false;
            }

            if (!$.checkMobilePhone($("#mobile").val()))
            {
                    is_lock_send_vy = false;
                    $.tipMessage("手机号码格式错误，请重新输入");
                    return false;
            }

            var ajaxurl = '{:url("api/Getcode/index")}';
            var query = new Object();
            query.user_mobile = $.trim($("#mobile").val());
            $.ajax({
                url: ajaxurl,
                data:query,
                type: "POST",
                dataType: "json",
                success: function(result){
                    if (result.status == 1)
                    {
                        left_rg_time = 60;
                        left_time_to_send_regvy();
                        $.tipMessage(result.info);
                        to_send_msg = true;
                    }
                    else
                    {
                        is_lock_send_vy = false;
                        $.tipMessage(result.info);
                        return false;
                    }
                }, 
                error:function(){
                    is_lock_send_vy = false;
                }
            });

        });
    });
</script>
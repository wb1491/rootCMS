<php>
    $SEO['title'] = "用户登录 -- ".$config['sitename'];
</php>
<template file='content/header_file.php'/>
<template file="content/header_nav.php"/>

<!-- section start -->
<!-- ================ -->
<div class="container user">
  <div class="row indexblock">
    <div class="col-md-12 col-xs-12 fixlist3 border">
      <div class="userlogin">
        <form class="form" id="form1" action="{:url('Publics/doLogin')}" method="post">

          <div class="form-group">
            <div class="col-md-12">
              <div id="errMessage" style="display: none;" class="alert alert-danger" role="alert"></div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-sm-3 ">
                <span class="frmlabe hidden-xs hidden-sm">用户名</span>
              </div>
              <div class="col-sm-9 ">
                <label class="sr-only" for="name2">用户名</label>
                <input type="text" name="loginName" id="loginName" class="form-control"
                       nullmsg="请输入登录的用户名！" datatype="*4-16" errormsg="请输入正确的用户名！"
                       placeholder="用户名"/>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-sm-3 ">
                <span class="frmlabe hidden-xs hidden-sm">密　码</span>
              </div>
              <div class="col-sm-9 ">
                <label class="sr-only" for="iPhone2">密码</label>
                <input type="password" value="" name="password" id="password" class="form-control"
                       nullmsg="请输入登录的密码！" datatype="*6-16" errormsg="请输入正确的密码！"
                       placeholder="密码"/>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-sm-3 ">
                <span class="frmlabe hidden-xs hidden-sm">验证码</span>
              </div>
              <div class="col-sm-9 ">
                <div class="col-md-6 col-sm-6 col-xs-6 paddingfix">
                  <input id="vCode" class="form-control" type="text" name="vCode"
                         nullmsg="请输入验证码！" datatype="*4-4" errormsg="请输入正确的验证码！" sucmsg=""
                         placeholder="验证码">
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                  <img id="authCode" align="absmiddle" title="看不清？点击更换" src='{:url("api/Checkcode/index","type=userlogin&code_len=4&font_size=18&width=90&height=35")}'/> 
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <span class="remember-box checkbox">
                  <label class="col-md-6" for="rememberme">
                    <input type="checkbox" id="setCookieTime" name="cookieTime">记住密码
                  </label>
                  <a class="col-md-6" href="{:url('member/Index/lostpassword')}">忘记密码？</a>
                </span>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-12 text-center">
              <input type="submit" value=" 登  录 " id="submit2" class="btn btn-default" data-loading-text="Loading...">
            </div>
          </div>
          <div class="form-group">
              <div class="col-md-6 paddingfix">
                <a href="{:url('member/Index/register')}">还未注册账号？</a>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<template file="content/footer.php"/>
<template file="content/script_file.php"/>
<script type="text/javascript" src="{$model_extresdir}js/common.js"></script>
<script type="text/javascript">
    user.loginInit(1);
    user.loginCss();
</script>

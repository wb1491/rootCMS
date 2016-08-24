<php>
  $SEO['title'] = "密码找回 -- ".$config['sitename'];
</php>
<template file='content/header_file.php'/>
<template file="member/Publics/global_js.php"/>
<template file="content/header_nav.php"/>

<div class="container user">
  <div class="row indexblock">
    <div class="col-md-12 col-xs-12 fixlist3 border">
      <div class="userlogin">
        <div class="featured-box featured-box-secundary default info-content">
          <div class="box-content ">
            <div class="text"><span> 找回您在&nbsp; <strong>{$Config.sitename}</strong> &nbsp;注册的会员账号登录密码。 </span></div>
            <div class="contenter margin-top30">
              <div class="row" id="lostPass">
                <div class="form-group clearfix">
                  <div class="col-md-3 text-right hidden-sm hidden-xs" style="line-height:42px;">登录账号：</div>
                  <div class="col-md-6" id="rM_loginName">
                    <input id="rloginName" class="form-control" type="text" maxlength="200" name="rloginName" placeholder="登录账号"/>
                  </div>
                  <div class="col-md-3 emsg" id="mloginName"></div>
                </div>
                <div class="form-group clearfix">
                  <div class="col-md-3 text-right hidden-sm hidden-xs" style="line-height:42px;">验证码：</div>
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <input id="rvCode" class="form-control" type="text" maxlength="4" name="rvCode" placeholder="验证码"/>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <img align="absmiddle" src="{:url("api/Checkcode/index","type=lostpassword&code_len=4&font_size=14&width=100&height=40&font_color=&background=")}" title="看不清？点击更换" id="authCode" />
                        <a class="hidden-sm hidden-xs" href="#" id="changeAuthCode">看不清?</a>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 emsg" id="rM_rvCode">
                    <div id="mvCode"></div>
                  </div>
                </div>
                <div class="form-group clearfix">
                  <div class="col-md-12"><span class="note">提示：如果您忘记了您的登录账号，请您联系网站管理员。</span></div>
                </div>
                <div class="form-group clearfix">
                  <div class="col-md-12 text-center">
                    <input id="submit" class="submit btn btn-color" type="submit" value="确定找回密码"/>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div id="errMessage" style="display: none;" class="alert alert-danger" role="alert"></div>
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

<template file="content/footer.php"/>

<script type="text/javascript" src="{$model_extresdir}js/common.js"></script>
<script type="text/javascript" src="{$model_extresdir}js/lostpass.js"></script>
<script type="text/javascript">
    lostpass.init();
</script>

</body>
</html>
<php>
  $SEO['title'] = "账户授权 -- ".$Config['sitename'];
</php>
<template file='member/Publics/homeHeader.php'/>
<template file="Content/header_nav.php"/>

<div class="container userprofile">
    <div class="row indexblock">
      <div class="col-md-2">
        <template file="member/Publics/homeUserMenu.php"/>
      </div>
      <div class="col-md-10 sm-paddingfix">
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="#tab_1" data-toggle="tab" href="{:url('Favorite/index')}">我的账户授权</a>
          </li>
        </ul>
        <div class="tab-content border" id="favoritesList">
          <div class="tab-pane fade active in" id="tab_1">
            <div class="nothing" style="padding-top: 20px;padding-left:40px;">您没有任何账户授权。</div>
          </div>
          <ul>
            <if condition=" $Member_config['qq_akey'] && $Member_config['qq_skey'] ">
              <li>
                <span class="qqBind"></span>
                <span class="BindText"><strong>腾讯QQ</strong><br />绑定腾讯QQ帐号后，您便可使用腾讯QQ帐号登录网站</span>
                <if condition=" $isqqlogin ">
                  <span class="BindBtn"><a href="{:url('Account/cancelbind',array('connectid'=>$isqqlogin['connectid']) )}"  class="binded">取消绑定</a></span>
                  <else/>
                  <span class="BindBtn"><a href="{:url('Account/authorize',array('type'=>'qq') )}">立即绑定</a></span>
                </if>
              </li>
            </if>
            <if condition=" $Member_config['sinawb_akey'] && $Member_config['sinawb_skey'] ">
              <li>
                <span class="sinaBind"></span>
                <span class="BindText"><strong>新浪微博</strong><br />绑定新浪微博帐号后，您便可使用新浪微博帐号登录网站</span>
                <if condition=" $isweibologin ">
                  <span class="BindBtn"><a href="{:url('Account/cancelbind',array('connectid'=>$isweibologin['connectid']) )}"  class="binded">取消绑定</a></span>
                  <else/>
                  <span class="BindBtn"><a href="{:url('Account/authorize',array('type'=>'sina_weibo') )}">立即绑定</a></span>
                </if>
              </li>	
            </if>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <template file="Content/footer.php"/>

  <script type="text/javascript" src="{$model_extresdir}js/account.js"></script>
  <script type="text/javascript">
      account.doAccountInit();
      account.exchangeMoneyInit();
  </script>

</body>
</html>
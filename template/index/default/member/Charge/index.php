<php>
  $SEO['title'] = "账户充值 -- ".$Config['sitename'];
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
        <li <if condition=" !$type ">class="active"</if>>
        <a target="_slef" href="{:url('Charge/index')}">我要充值</a>
        </li>
      </ul>
      <div class="tab-content border">
        <div class="row" id="favoritesList">
          <form id="doprofile" action="{:url('Charge/docharge')}" method="post">
            <div class="featured-box featured-box-secundary default info-content">
              <div class="box-content clearfix">
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-2 text-right" style="line-height: 42px;"><span color='red'>*</span>充值金额：</div>
                    <div class="col-md-6 text-left"> <input class="form-control" type="text" value="" id="price" name="price"></div>
                    <div class="col-md-4 text-left" id="mnickname"></div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-2 text-right" style="line-height: 42px;"><span color='red'>*</span>支付方式：</div>
                    <div class="col-md-6">
                      <img class="wxpaylog" src="{$Config_siteurl}statics/addons/wxpay/WePayLogo.png"/>
                    </div>
                    <div class="col-md-4 text-left" id="memail"></div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-12 text-center">
                      <button class="btn btn-default weixinPayBtn" type="button"> 充 值 </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" charset="UTF-8" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
$(document).ready(function(){
   $('.weixinPayBtn').click(function(){
        $(this).addClass("disabled");
        var price = $("#price").val();

        if(isNaN(price)){
            alert("请输入正确的充值金额！");
            $("#price").select().focus();
            return;
        }

        $("#doprofile").submit();

    }); 
});
</script>
<template file="Content/footer.php"/>
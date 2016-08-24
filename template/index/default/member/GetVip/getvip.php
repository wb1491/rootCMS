<php>
  $SEO['title'] = "成为VIP -- ".$Config['sitename'];
  $username = service("Passport")->username;
</php>
<template file='member/Publics/homeHeader.php'/>
<template file="Content/header_nav.php"/>

<div class="container userprofile">
  <div class="row indexblock">
    <div class="col-md-12 paddingfix">
      <ul class="nav nav-tabs">
        <li <if condition=" !$type ">class="active"</if>>
        <a target="_slef" href="{:url('GetVip/index')}">{$username} 成为VIP</a>
        </li>
      </ul>
      <div class="tab-content border">
        <div class="row" id="favoritesList">
            <div class="featured-box featured-box-secundary default info-content">
              <form action='{:url("updateVip")}' id="fm1" method="post">
                <input type="hidden" id="vipid" name="vipid" value=""/>
              <div class="box-content clearfix">
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-2 text-right" style="line-height: 42px;"><span color='red'>*</span>选择VIP套餐：</div>
                    <div class="col-md-6 text-left">
                      <volist name="viplist" id="vo" key="k">
                        <label class="vlist">
                          <input type="radio" id="vip" name="price" data="{$vo.id}" 
                                 <if condition="$k eq 1">checked</if> 
                            value="{$vo.amount}"/>{$vo.typename} <font color="red">{$vo.amount}元</font></label>&nbsp;&nbsp;
                      </volist>
                    </div>
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
                <div class="row">
                  <div class="col-md-12">
                    <div id="errMessage" style="display: none;" class="alert alert-danger" role="alert"></div>
                  </div>
                </div>
              </div>
              </form>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
   $('.weixinPayBtn').click(function(){
            
        var price = $("input[type='radio']:checked").val();
        var vipid = $("input[type='radio']:checked").attr("data");

        if(isNaN(price)){
            alert("未选择VIP套餐！");
            $("input[type='radio']:first").focus();
            return;
        }

        $("#vipid").val(vipid);

        $("#fm1").submit();

    }); 
});
</script>
<template file="Content/footer.php"/>
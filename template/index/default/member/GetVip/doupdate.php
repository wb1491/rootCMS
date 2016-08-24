<php>
  $SEO['title'] = "支付成功 -- ".$Config['sitename'];
</php>
<template file='Content/header_file.php'/>
<template file="Content/header_nav.php"/>
<div class="container userprofile">
  <div class="row indexblock">
    <div class="col-md-12 paddingfix">
      <ul class="nav nav-tabs">
        <li <if condition=" !$type ">class="active"</if>>
        <a target="_slef" href="{:url('')}">升级VIP成功</a>
        </li>
      </ul>
      <div class="tab-content border">
        <div class="row" id="favoritesList">
            <div class="featured-box featured-box-secundary default info-content">
              <div class="box-content clearfix" style="text-align: center;">
                <br/>
                <font color="#9ACD32">
                  <b>您已经成为:
                  <span style='color:#f00;font-size:16'>{$vipname}</span> 会员！
                  到期时间：<span style="color:#f00;">{$endtime|date="Y-m-d H:i:s",###}</span></b>
                </font>
                <br/>
                <br/>
                <div align="center">
                  <button class="btn-pay" type="button" onclick="back()" >访问网站</button>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
    function back(){
        location.href="{:url('Content/Index/index')}";
    }
</script>
<template file="Content/footer.php"/>
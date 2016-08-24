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
        <a target="_slef" href="{:url('')}">账户充值成功</a>
        </li>
      </ul>
      <div class="tab-content border">
        <div class="row" id="favoritesList">
            <div class="featured-box featured-box-secundary default info-content">
              <div class="box-content clearfix" style="text-align: center;">
                <br/>
                <font color="#9ACD32">
                  <b>您本次成功为账户充值:
                  <span style="color:#f00;font-size:50px">{$price}</span>元</b>
                </font>
                <br/>
                <br/>
                <div align="center">
                  <button class="btn-default" type="button" onclick="back()" >访问网站</button>
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
        <if condition="!empty($returl)">
        location.href="{$returl}";
        <else/>
        location.href="{:url('Content/Index/index')}";
        </if>
    }
</script>
<template file="Content/footer.php"/>
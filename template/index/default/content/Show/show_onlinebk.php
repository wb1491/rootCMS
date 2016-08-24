<php>
$useragent = $_SERVER['HTTP_USER_AGENT'];

$tzurl = $config_siteurl."tcProxy/index.php?roomID=1";
if(strpos($useragent,"Mobile") !== false){
    $tzurl = $config_siteurl."v/index.php?m3u8=tclive1";
}
$now = time();
</php>
<template file='Content/header_file.php'/>

<div class="conbox Mfont">
  <h4 class="con-cru">
    <a href="{$config_siteurl}">{$Config.sitename}</a> &gt; 
    <navigate catid="$catid" space=" &gt; " />
  </h4>
  <div class="content">
    <div class="content Mbreak">
      <if condition=" $now egt strtotime($startime) && $now lt strtotime($endtime) ">
      <iframe style="border:none;height: 100%;width: 600px;" src="http://www.arvte.com/{$tzurl}"></iframe>
      <else/>
      <div class="panel panel-default">
        <div class="panel-heading">信息提示</div>
        <div class="panel-body">
          <div class="msg">直播还未开始或者已经停止，请在直播时间段内观看！</div>
          <div class="error_return"><a href="{:url('index')}" class="btn">返回</a></div>
        </div>
      </div>
      </if>
    </div>
  </div>
</div>
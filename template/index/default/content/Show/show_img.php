<template file='Content/header_file.php'/>

<div class="conbox Mfont">
  <h4 class="con-cru">
    <a href="{$config_siteurl}">{$Config.sitename}</a> &gt; 
    <navigate catid="$catid" space=" &gt; " />
  </h4>
  <div class="content">
    <h2 class="con-tit">{$title}</h2>
	<p class="con-info">  <span id="hits">0</span>   {$updatetime}    </p>
    <h5 class="con-dis border Mbreak">{$description}</h5>
    <ul class="photocon" id="Gallery">
      <volist name="imgs" id="vo">
      <li><a title="{$vo.alt}" href="{$vo.url}"><img src="{$vo.url}" alt="{$vo.alt}" /></a></li>
      </volist>
    </ul>
    <div class="content Mbreak">
      <php>
        $tcontent = preg_replace("/font-family:[^;]+;/","",$content);
        $tcontent = preg_replace("/font:[^;]+;/","",$tcontent);
        echo $tcontent;
      </php>
    </div>
    <ul class="prenext">
      <li>    <pre target="1" msg="   " /></li>
      <li>   <next target="1" msg="   " /></li>
    </ul>
  </div>
</div>
<script type="text/javascript">
$(function (){
	//点击
	$.get("{$config_siteurl}api.php?m=Hits&catid={$catid}&id={$id}", function (data) {
	    $("#hits").html(data.views);
	}, "json");
});
$(document).ready(function(){
  var options = {};
  $("#Gallery a").photoSwipe(options);
});
</script>
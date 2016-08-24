<template file='content/header_file.php'/>

<div class="conbox Mfont">
  <h4 class="con-cru">
    <a href="{$config_siteurl}">{$Config.sitename}</a> &gt; 
    <navigate catid="$catid" space=" &gt; " />
  </h4>
  <div class="content">
    <h2 class="con-tit">{$title}</h2>
    <div class="content Mbreak">
      <php>
        $tcontent = preg_replace("/font-family:[^;]+;/","",$content);
        echo $tcontent;
      </php>
    </div>
  </div>
</div>
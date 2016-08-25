<?php if (!defined('SHUIPF_VERSION')) exit(); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>{$Config.sitename} - 提示信息</title>
<Admintemplate file="admin/Common/Cssjs"/>
</head>
<body>
<div class="wrap">
  <div id="error_tips">
    <h2>{$msgTitle}</h2>
    <div class="error_cont">
      <ul>
        <li>{$message}</li>
      </ul>
      <div class="error_return">
        <if condition="!empty($button)">
        {$button}    
        <else />
        <a href="{$jumpUrl}">如果您的浏览器没有自动跳转，请点击这里 </a>
        </if>
      </div>
    </div>
  </div>
</div>
<script src="{$config_siteurl}statics/js/common.js?v"></script>
<script src="{$config_siteurl}statics/student/common.js?v"></script>
<if condition="empty($button) && !empty($jumpUrl)">
<script language="javascript">setTimeout("redirect('{$jumpUrl}');",{$waitSecond});</script>
</if>
</body>
</html>
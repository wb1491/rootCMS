<!DOCTYPE html>
<!--[if IE 8]> <html lang="zh-cn" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="zh-cn" class="ie9"> <![endif]-->
<!--[if !IE]> <html lang="zh-cn"> <![endif]-->
<head>
<title><if condition=" isset($SEO['title']) && !empty($SEO['title']) ">{$SEO['title']}</if>{$SEO['site_title']}</title>
<meta name="description" content="{$SEO['description']}" />
<meta name="keywords" content="{$SEO['keyword']}" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="renderer" content="webkit|ie-stand|ie-comp">
<meta http-equiv ="X-UA-Compatible" content = "IE=edge,chrome=1"/>
<meta http-equiv="Cache-Control" content="no-siteapp" />
<meta name="format-detection" content="telephone=no"/>
<meta name="format-detection" content="address=no" />
<meta content="email=no" name="format-detection" />
<meta name="HandheldFriendly" content="true" />
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>
<link href="{$config_siteurl}statics/mgtv/css/bootstrap.css" rel='stylesheet' type='text/css'>
<link href="{$config_siteurl}statics/mgtv/css/customer.css" rel='stylesheet' type='text/css'>
<!--[if lt IE 9]>
    <script src="{$config_siteurl}statics/mgtv/js/html5shiv.min.js"></script>
    <script src="{$config_siteurl}statics/mgtv/js/respond.min.js"></script>
<![endif]-->
<script src="{$config_siteurl}statics/mgtv/js/jquery.min.js"></script>
<script src="{$config_siteurl}statics/mgtv/js/bootstrap.js"></script>
<script src="{$config_siteurl}statics/mgtv/js/jquery.tabs.js"></script>
<script src="{$config_siteurl}statics/mgtv/js/customer.js"></script>
<SCRIPT language=javascript type=text/javascript>
$(document).ready(function () {
   $('#tab1').Tabs({
		auto:3000
	});
})
</SCRIPT>
</head>
<body>
<php>
$useragent = $_SERVER['HTTP_USER_AGENT'];

$tzurl = $config_siteurl."tcProxy/index.php?roomID=1";
if(strpos($useragent,"Mobile") !== false){
    $tzurl = $config_siteurl."v/index.php?m3u8=tclive1";
}
$now = time();
</php>
<if condition=" $now egt strtotime($startime) && $now lt strtotime($endtime) ">
<script type="text/javascript">
    location.href = '{$tzurl}';
</script>
</if>
<?php
  $SEO['title'] = $Config["sitename"]." - 提示信息";
?>
<template file="Content/header_file.php"/>
<template file="Content/header_nav.php"/>
<!-- Modal -->
<div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">{$msgTitle}</h4>
      </div>
      <div class="modal-body">
        直播还未开始或者已经停止，请在直播时间段内观看！
      </div>
      <div class="modal-footer">
        <a href="{:url('index')}" class="btn btn-default" data-dismiss="modal"> 返 回 </a>
      </div>
    </div>
  </div>
</div>
<script src="{$config_siteurl}statics/js/common.js?v"></script>
<script language="javascript">
setTimeout(function(){
	location.href = '{:url('index')}';
},{$waitSecond});
$(function(){
    $("#myModal").modal("show");
    $("#mybtn").on("click",function(){
        location.href = "{:url('index')}";
    });
});
</script>
<template file="Content/footer.php"/>
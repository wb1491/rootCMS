<?php
  $SEO['title'] = $Config["sitename"]." - 提示信息";
?>
<template file="content/header_file.php"/>
<template file="content/header_nav.php"/>
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
        {$error}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="mybtn" data-dismiss="modal"> 返 回 </button>
      </div>
    </div>
  </div>
</div>
<script src="{$config_siteurl}statics/js/common.js?v"></script>
<script language="javascript">
setTimeout(function(){
	location.href = '{$jumpUrl}';
},{$waitSecond});
$(function(){
    $("#myModal").modal("show");
    $("#mybtn").on("click",function(){
        location.href = "{$jumpUrl}";
    });
});
</script>
<template file="content/footer.php"/>